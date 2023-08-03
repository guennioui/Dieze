<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classes\Cart;
use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManager;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManager $entityManager, Cart $cart, $reference)
    {

        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $commande = $entityManager->getRepository(Commande::class)->findOneByReference($reference);
        if (!$commande) {
            return new JsonResponse(['error' => 'order']);
        }

        foreach ($cart->getFull() as $product) {
            $productObject = $entityManager->getRepository(Produit::class)->findOneByLibelle($product->getProduit());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'MAD',
                    'unit_amount' => $product->getPrix(),
                    'product_data' => [
                        'name' => $product->getProduit(),
                    ],
                ],
                'quantity' => $product->getQuantity,
            ];
        }
        Stripe::setApiKey('sk_test_51NaJ51Caf8vRlywGZLluN6Q54MffElyLwTxGzHr98FeKzCZu0ovuIhrzekkM0Kz7B2OZnbMaSlEnmYVyGOpHVWEU00RcMqg4eE');

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                $product_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
