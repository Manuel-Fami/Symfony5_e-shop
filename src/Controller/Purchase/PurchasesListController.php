<?php

namespace App\Controller\Purchase;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PurchasesListController extends AbstractController
{
    protected $security;
    protected $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }
    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        // Connection ok ?
        $user = $this->security->getUser();

        //Vérification connexion user + création d'une url pour rediriger sur la page principale
        if (!$user) {
            $url = $this->router->generate('homepage');
            return new RedirectResponse($url);
        }

        // Qui est connecté ?

    }
}
