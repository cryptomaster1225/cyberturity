<?php

declare(strict_types=1);

namespace UI\Controller;

use Application\Command\CreateOrder;
use Domain\Repository\OrderRepository;
use Domain\Repository\ProductRepository;
use Domain\Service\DiscountService;
use Domain\Service\PaymentService;
use Infrastructure\Symfony\Controller\WebController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use UI\Form\OrderStep1Type;
use UI\Form\OrderStep2Type;
use UI\Form\OrderStep3Type;

/**
 * Class PaymentController
 * @package UI\Controller
 */
class PaymentController extends WebController
{
    /**
     * @var PaymentService
     */
    private PaymentService $paymentService;

    /**
     * @var DiscountService
     */
    private DiscountService $discountService;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @param PaginatorInterface  $paginator
     * @param MessageBusInterface $commandBus
     * @param PaymentService      $paymentService
     * @param DiscountService     $discountService
     * @param OrderRepository     $orderRepository
     * @param ProductRepository   $productRepository
     */
    public function __construct(
        PaginatorInterface $paginator,
        MessageBusInterface $commandBus,
        PaymentService $paymentService,
        DiscountService $discountService,
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        parent::__construct($paginator, $commandBus);

        $this->paymentService = $paymentService;
        $this->discountService = $discountService;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return Response
     */
    public function info(): Response
    {
        $product = $this->productRepository->findAll()[0];

        return $this->render('Payment/info.twig', [
            'product' => $product
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function order(Request $request): Response
    {
        if ($request->request->has('order2')) {
            return $this->handleOrderStep2($request);
        }

        if ($request->request->has('order3')) {
            return $this->handleOrderStep3($request);
        }

        return $this->handleOrderStep1($request);
    }

    private function handleOrderStep1(Request $request): Response
    {
        $form = $this->createForm(OrderStep1Type::class, [
            'quantity' => 1,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('order1', $form->getData());

            return $this->handleOrderStep2($request);
        }

        return $this->render('Payment/order1.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function handleOrderStep2(Request $request): Response
    {
        $form = $this->createForm(OrderStep2Type::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = \array_merge($request->getSession()->get('order1', []), $form->getData());

            $request->getSession()->set('order2', $data);

	    // create session at stripe
            // $id = $this->paymentService->createSession($request);
	    
	    // @todo passing $id to template, then use for redirection to Stripe Checkout page
	    
            return $this->handleOrderStep3($request);
        }

        return $this->render('Payment/order2.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function handleOrderStep3(Request $request): Response
    {
        $form = $this->createForm(OrderStep3Type::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderId = $this->uuid();
            $data = $request->getSession()->get('order2');

            $this->dispatch(new CreateOrder(
                $orderId,
                $data['product']->id(),
                $data['quantity'],
                $data['discountCode'],
                $data['email'],
                $data['firstName'],
                $data['lastName'],
                $data['companyName'],
                $data['addressLine1'],
                $data['addressLine2'],
                $data['city'],
                $data['postalCode'],
                $data['country']
            ));

            $order = $this->orderRepository->find($orderId);

            $url = $this->paymentService->createOrder($order);

            return new RedirectResponse($url);
        }

        $data = $request->getSession()->get('order2');

        return $this->render('Payment/order3.twig', [
            'form'    => $form->createView(),
            'data'    => $data,
            'summary' => $this->discountService->calculateDiscount(
                $data['product'],
                $data['quantity'],
                $data['discountCode'] ?? null
            ),
        ]);
    }

    public function discount(Request $request): Response
    {
        $product = $this->productRepository->find($request->request->get('product'));
        $quantity = $request->request->getInt('quantity', 1);

        if (!$product || !$quantity) {
            return new JsonResponse(['message' => 'product and quantity is required'], 400);
        }

        return new JsonResponse(
            $this->discountService->calculateDiscount($product, $quantity, $request->request->get('discountCode'))
        );
    }

    public function cancel(): Response
    {
        return $this->render('Payment/cancel.twig');
    }

    public function result(Request $request): Response
    {
        if ($request->query->has('token')) {
            $this->paymentService->captureOrder($request->query->get('token'));

            $order = $this->orderRepository->findByPaypalId($request->query->get('token'));
            $this->paymentService->createInvoice($order);
        }

        return $this->render('Payment/result.twig');
    }

    public function paypalWebhook(Request $request): Response
    {
        if ($request->request->has('event_type')) {
            $this->paymentService->handleEvent($request->request->get('event_type'), $request->request->all());
        }

        return new Response(null, 204);
    }
}
