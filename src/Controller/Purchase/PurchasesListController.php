<?php

namespace App\Controller\Purchase;

use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends AbstractController
{
    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $router, Environment $twig)
    {
        $this->security = $security;
        $this->router = $router;
        $this->twig = $twig;
    }
    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        // Connection ok ?
        /** @var User */
        $user = $this->security->getUser();

        //Vérification connexion user + création d'une url pour rediriger sur la page principale
        if (!$user) {
            $url = $this->router->generate('homepage');
            return new RedirectResponse($url);
        }

        // Qui est connecté ?
        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);

        return new Response($html);
    }
}
