<?php

declare(strict_types=1);

namespace UI\Controller;

use Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PageController
 * @package UI\Controller
 */
class PageController extends WebController
{
    public function home(): Response
    {
        return$this->render('index.twig');
    }

    public function privacy(): Response
    {
        return $this->render('privacy.twig');
    }

    public function terms(): Response
    {
        return $this->render('terms.twig');
    }
}
