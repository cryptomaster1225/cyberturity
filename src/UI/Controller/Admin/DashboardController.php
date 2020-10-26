<?php

declare(strict_types=1);

namespace UI\Controller\Admin;

use Infrastructure\Symfony\Controller\WebController;

/**
 * Class DashboardController
 * @package UI\Controller\Admin
 */
class DashboardController extends WebController
{
    public function __invoke()
    {
        return $this->redirectToRoute('admin_product_list');
    }
}
