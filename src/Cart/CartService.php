<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    // Refactoring pour récupérer la cart panier
    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    //Refactoring pour sauvegarder la carte panier
    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    public function add(int $id)
    {
        //1. Retrouver le panier dans la session (sous forme de tableau)
        //2. Si il n'existe pas encore, alors prendre un tableau vide
        $cart = $this->getCart();

        //3. Voir si le produit ($id) existe déjà dans le tableau
        //4. Si c'est le cas, simplement augmenter la quantité
        //5. Sinon, ajouter le produit avec la quantité 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        //6. Enregistrer le tableau mis à jour dans la session
        $this->saveCart($cart);
        // dd($session);
    }

    // ---- supprimer un produit au panier -----
    public function remove(int $id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    // ----- décrémenté le panier -----
    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        //Si panier est à 1 alors suppirmer
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        //Si panier + de 1 alors décrémenté
        $cart[$id]--;

        $this->saveCart($cart);
    }

    // -------- Calcul du total du panier -------
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }

    public function getDetailedCartItem(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }
        return $detailedCart;
    }
}
