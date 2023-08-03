<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classes\Cart;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(Cart $cart)
    {

        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFull() as $product) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product['product']->getPrix(),
                    'product_data' => [
                        'name' => $product['product']->getLibelle(),
                    ],
                ],
                'quantity' => $product['quantity'],
            ];
        }
        Stripe::setApiKey('sk_test_51NaJ51Caf8vRlywGZLluN6Q54MffElyLwTxGzHr98FeKzCZu0ovuIhrzekkM0Kz7B2OZnbMaSlEnmYVyGOpHVWEU00RcMqg4eE');
        dd();    
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
