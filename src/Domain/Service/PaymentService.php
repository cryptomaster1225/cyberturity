<?php

declare(strict_types=1);

namespace Domain\Service;

use Domain\Model\Order;
use Domain\Model\OrderItem;
use Domain\Repository\OrderRepository;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;
use PayPalHttp\HttpRequest;
use Stripe\Stripe;
use Stripe\Checkout;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class PaymentService
 * @package Domain\Service
 */
class PaymentService
{
    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @var PayPalHttpClient
     */
    private PayPalHttpClient $client;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @param PayPalHttpClient $client
     * @param RouterInterface  $router
     * @param OrderRepository  $orderRepository
     */
    public function __construct(
        PayPalHttpClient $client,
        RouterInterface $router,
        OrderRepository $orderRepository
    ) {
        $this->client = $client;
        $this->router = $router;
        $this->orderRepository = $orderRepository;
    }

    public function createSession(Request $request): string
    {
        $data = $request->getSession()->get('order2');
	Stripe::setApiKey(env('STRIPE_API_KEY'));
	$stripeData = [
          'payment_method_types' => ['card'],
          'line_items' => [[
            'price_data' => [
              'currency' => 'AUD',
              'product_data' => [
                'name' => $data['product']->name()
              ],
              'unit_amount' => $data['product']->price() * $data['quantity']
            ],
            'quantity' => $data['quantity'],
          ]],
          'mode' => 'payment',
	  'cancel_url'  => $this->router->generate('order_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
	  'success_url' => $this->router->generate('order_result', [], UrlGeneratorInterface::ABSOLUTE_URL),
	];
        $session = Checkout\Session::create($stripeData);

	return $session->id;
    }

    public function createOrder(Order $order): string
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $data = [
            'intent'              => 'CAPTURE',
            'payer'               => [
                'name'          => [
                    'given_name' => $order->firstName(),
                    'surname'    => $order->lastName(),
                ],
                'email_address' => $order->email(),
                'address'       => [
                    'address_line_1' => $order->addressLine1(),
                    'address_line_2' => $order->addressLine2(),
                    'admin_area_2'   => $order->city(),
                    'postal_code'    => $order->postalCode(),
                    'country_code'   => $order->country(),
                ],
            ],
            'purchase_units'      => [],
            'application_context' => [
                'cancel_url' => $this->router->generate('order_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'return_url' => $this->router->generate('order_result', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
        ];

        if ($order->discountAmount() > 0 && $order->discountKind() === $order->currency()) {
            $data['amount_breakdown'] = [
                'discount' => [
                    'currency_code' => $order->discountKind(),
                    'value'         => $order->discountAmount(),
                ],
            ];
        }

        $data['purchase_units'][] = [
            'reference_id' => $order->id(),
            'amount'       => [
                'value'         => $order->total(),
                'currency_code' => $order->currency(),
            ],
        ];

        $request->body = $data;

        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);

            if ($response->statusCode === 201 && $response->result->status === 'CREATED') {
                foreach ($response->result->links as $link) {
                    if ('approve' === $link->rel) {
                        $approveLink = (string)$link->href;
                    }
                }

                $order->changePaypalId($response->result->id);
                $order->changePaypalStatus($response->result->status);

                $this->orderRepository->save($order);

                if (!isset($approveLink)) {
                    throw new \InvalidArgumentException('Approval link is not defined');
                }

                return $approveLink;
            }
        } catch (HttpException $e) {
//            \dump($e->getMessage());
        }

        throw new \InvalidArgumentException('Approval link is not defined');
    }

    public function captureOrder(string $id): void
    {
        $request = new OrdersCaptureRequest($id);
        $request->prefer('return=representation');

        try {
            $response = $this->client->execute($request);
            $order = $this->orderRepository->findByPaypalId($id);
            if ($response->statusCode === 201) {
                $order->changePaypalStatus($response->result->status);
            }

            $this->orderRepository->save($order);
        } catch (HttpException $e) {
//            dump($e);
        }
    }

    public function accessToken(): string
    {
        if ($this->accessToken === null) {

            $authorization = sprintf('Basic %s', \base64_encode(env('PAYPAL_CLIENT_ID') . ':' . env('PAYPAL_CLIENT_SECRET')));

            $request = new HttpRequest('/v1/oauth2/token', 'POST');
            $request->headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $request->headers['Accept'] = 'application/json';
            $request->headers['Accept-Language'] = 'en_US';
            $request->headers['Authorization'] = $authorization;
            $request->body = [
                'grant_type' => 'client_credentials',
            ];

            $response = $this->client->execute($request);

            $this->accessToken = $response->result->access_token;
        }

        return $this->accessToken;
    }

    public function createInvoice(Order $order): void
    {
        $accessToken = $this->accessToken();

        $request = new HttpRequest('/v2/invoicing/invoices', 'POST');
        $request->headers['Content-Type'] = 'application/json';
        $request->headers['Authorization'] = sprintf('Bearer %s', $accessToken);

        $amount = null;

        if ($order->discountAmount() > 0 && $order->discountKind() === $order->currency()) {
            $amount['breakdown'] = [
                'discount' => [
                    'invoice_discount' => [
                        'amount' => [
                            'currency_code' => $order->currency(),
                            'value'         => $order->discountAmount(),
                        ],
                    ],
                ],
            ];
        }

        $detail = [
            'reference'     => $order->id(),
            'currency_code' => $order->currency(),
            'invoice_date'  => date('Y-m-d'),
        ];

        $items = [];
        /** @var OrderItem $item */
        foreach ($order->items() as $item) {
            $items[] = [
                'name'            => $item->product()->name(),
                'quantity'        => $item->quantity(),
                'unit_amount'     => [
                    'currency_code' => $order->currency(),
                    'value'         => $item->unitPrice(),
                ],
                'unit_of_measure' => 'QUANTITY',
            ];
        }

        $primaryRecipients = [];
        $primaryRecipients[] = [
            'billing_info' => [
                'business_name' => $order->companyName(),
                'name'          => [
                    'given_name' => $order->firstName(),
                    'surname'    => $order->lastName(),
                ],
                'address'       => [
                    'address_line_1' => $order->addressLine1(),
                    'address_line_2' => $order->addressLine2(),
                    'admin_area_2'   => $order->city(),
                    'postal_code'    => $order->postalCode(),
                    'country_code'   => $order->country(),
                ],
                'email_address' => $order->email(),
            ],
        ];

        $body = [
            'detail'             => $detail,
            'primary_recipients' => $primaryRecipients,
            'items'              => $items,
            'amount'             => $amount,
        ];

        $request->body = $body;

        try {
            $response = $this->client->execute($request);
            if ($response->statusCode === 201 && $response->result->href) {
                $invoice = $this->getInvoice($response->result->href);

                $order->changeInvoiceId($invoice->id);
                $order->changeInvoiceStatus($invoice->status);

                $this->sendInvoice($order);
                $order->changeInvoiceStatus('SENT');

                $this->markPayment($order);
                $order->changeInvoiceStatus('PAID');

                $this->orderRepository->save($order);
            }
        } catch (HttpException $e) {
//            dump($e);
        }
    }

    private function getInvoice(string $href)
    {
        $path = \parse_url($href, \PHP_URL_PATH);

        $request = new HttpRequest($path, 'GET');
        $request->headers['Content-Type'] = 'application/json';
        $request->headers['Authorization'] = sprintf('Bearer %s', $this->accessToken());

        return $this->client->execute($request)->result;
    }

    private function sendInvoice(Order $order)
    {
        $path = sprintf('/v2/invoicing/invoices/%s/send', $order->invoiceId());
        $request = new HttpRequest($path, 'POST');
        $request->headers['Content-Type'] = 'application/json';
        $request->headers['Authorization'] = sprintf('Bearer %s', $this->accessToken());
        $request->body = [
            'send_to_invoicer' => true,
        ];

        return $this->client->execute($request)->result;
    }

    private function markPayment(Order $order)
    {
        $path = sprintf('/v2/invoicing/invoices/%s/payments', $order->invoiceId());
        $request = new HttpRequest($path, 'POST');
        $request->headers['Content-Type'] = 'application/json';
        $request->headers['Authorization'] = sprintf('Bearer %s', $this->accessToken());
        $request->body = [
            'method'       => 'PAYPAL',
            'payment_id'   => $order->paypalId(),
            'payment_date' => date('Y-m-d'),
            'amount'       => [
                'currency_code' => $order->currency(),
                'value'         => $order->total(),
            ],
        ];

        return $this->client->execute($request)->result;
    }

    public function handleEvent(string $eventType, array $data): void
    {
        if ('PAYMENT.CAPTURE.COMPLETED' === $eventType) {
            $this->handleEventPaymentCaptureCompleted($data);
        }
    }

    private function handleEventPaymentCaptureCompleted(array $data): void
    {
//        \dump($data);
    }
}
