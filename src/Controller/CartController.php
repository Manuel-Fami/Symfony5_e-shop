<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements ={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session, FlashBagInterface $flashBag)
    {
        //0. Sécurisation du produit
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        //1. Retrouver le panier dans la session (sous forme de tableau)
        //2. Si il n'existe pas encore, alors prendre un tableau vide
        $cart = $session->get('cart', []);

        //3. Voir si le produit ($id) existe déjà dans le tableau
        //4. Si c'est le cas, simplement augmenter la quantité
        //5. Sinon, ajouter le produit avec la quantité 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        //6. Enregistrer le tableau mis à jour dans la session
        $session->set('cart', $cart);
        // dd($session);

        $flashBag->add('success', "Le produit a bien été ajouté au panier !");

        // dump($flashBag->get('success'));
        // dd($flashBag);

        //Permet de supprimer la session - remise à zéro
        // $request->getSession()->remove('cart');

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(SessionInterface $session, ProductRepository $productRepository)
    {
        $detailedCart = [];
        $total = 0;

        foreach ($session->get('cart', []) as $id => $qty) {
            $product = $productRepository->find($id);

            $detailedCart[] = [
                'product' => $product,
                'qty' => $qty
            ];

            $total += ($product->getPrice() * $qty);
        }

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }
}
