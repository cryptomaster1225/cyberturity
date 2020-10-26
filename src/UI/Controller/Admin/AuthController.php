<?php

declare(strict_types=1);

namespace UI\Controller\Admin;

use Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AuthController
 * @package UI\Controller\Admin
 */
class AuthController extends WebController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('Admin/login.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }
}
