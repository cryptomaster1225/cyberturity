<?php

declare(strict_types=1);

namespace UI\Controller\Admin;

use Application\Command\CreateProduct;
use Application\Command\DeleteProduct;
use Application\Command\EditProduct;
use Domain\Model\Product;
use Domain\PaginatedQuery\ProductFilter;
use Domain\PaginatedQuery\ProductQuery;
use Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UI\Form\ProductType;

/**
 * Class ProductController
 * @package UI\Controller\Admin
 */
class ProductController extends WebController
{
    public function collection(ProductQuery $query, Request $request): Response
    {
        $filter = new ProductFilter();

        if ($request->query->has('page')) {
            $filter->changePage($request->query->getInt('page'));
        }

        $pagination = $this->paginate(
            $query->find($filter),
            $filter->page(),
            $filter->perPage()
        );

        return $this->render('Admin/Product/list.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatch(new CreateProduct(
                $this->uuid(),
                $form->get('name')->getData(),
                $form->get('price')->getData(),
            ));

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('Admin/Product/create.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, [
            'name'  => $product->name(),
            'price' => $product->price(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatch(new EditProduct(
                $product->id(),
                $form->get('name')->getData(),
                $form->get('price')->getData(),
            ));

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('Admin/Product/edit.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Product $product): Response
    {
        $this->dispatch(new DeleteProduct($product->id()));

        return $this->redirectToRoute('admin_product_list');
    }
}
