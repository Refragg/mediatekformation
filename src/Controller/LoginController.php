<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur gérant la connexion / déconnexion des utilisateurs
 */
class LoginController extends AbstractController
{
    /**
     * Route gérant la connexion d'un utilisateur sur le site
     * @param AuthenticationUtils $authenticationUtils Injecté par Symfony
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupération éventuelle de l'erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupération éventuelle du dernier nom d'utilisateur utilisé
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Route gérant la déconnexion d'un utilisateur
     * @return void
     */
    #[Route('/logout', name: 'logout')]
    public function logout() {
        // Géré par le bundle security de Symfony
    }
}
