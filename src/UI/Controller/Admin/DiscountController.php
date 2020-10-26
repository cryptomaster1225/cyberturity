<?php

declare(strict_types=1);

namespace UI\Controller\Admin;

use Application\Command\CreateDiscount;
use Application\Command\DeleteDiscount;
use Application\Command\EditDiscount;
use Domain\Model\Discount;
use Domain\PaginatedQuery\DiscountFilter;
use Domain\PaginatedQuery\DiscountQuery;
use Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UI\Form\DiscountType;

/**
 * Class DiscountController
 * @package UI\Controller\Admin
 */
class DiscountController extends WebController
{
    public function collection(DiscountQuery $query, Request $request): Response
    {
        $filter = new DiscountFilter();

        if ($request->query->has('page')) {
            $filter->changePage($request->query->getInt('page'));
        }

        $pagination = $this->paginate(
            $query->find($filter),
            $filter->page(),
            $filter->perPage()
        );

        return $this->render('Admin/Discount/list.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(DiscountType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatch(new CreateDiscount(
                $this->uuid(),
                $form->get('code')->getData(),
                $form->get('kind')->getData(),
                $form->get('amount')->getData(),
            ));

            return $this->redirectToRoute('admin_discount_list');
        }

        return $this->render('Admin/Discount/create.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Discount $discount, Request $request): Response
    {
        $form = $this->createForm(DiscountType::class, [
            'code'   => $discount->code(),
            'kind'   => $discount->kind(),
            'amount' => $discount->amount(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatch(new EditDiscount(
                $discount->id(),
                $form->get('code')->getData(),
                $form->get('kind')->getData(),
                $form->get('amount')->getData(),
            ));

            return $this->redirectToRoute('admin_discount_list');
        }

        return $this->render('Admin/Discount/edit.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Discount $discount): Response
    {
        $this->dispatch(new DeleteDiscount($discount->id()));

        return $this->redirectToRoute('admin_discount_list');
    }
}
