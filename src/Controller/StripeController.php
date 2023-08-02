<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Classes\Cart;
use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {

        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Commande::class)->findOneByReference($reference);
        if(!$order){
            new JsonResponse(['error' => 'order']);
        }

        foreach ($cart->getFull() as $product) {  
            $product_object = $entityManager->getRepository(Produit::class)->findOneByLibelle($product->getLibelle());          
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product->getPrix(),
                    'product_data' => [
                        'name' => $product->getLibelle(),
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
         $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $order->getTransporteur()->getPrix() * 100,
                    'product_data' => [
                        'name' => $product->getLibelle(),
                    ],
                ],
                'quantity' => 1,
            ];        
        Stripe::setApiKey('sk_test_51NaJ51Caf8vRlywGZLluN6Q54MffElyLwTxGzHr98FeKzCZu0ovuIhrzekkM0Kz7B2OZnbMaSlEnmYVyGOpHVWEU00RcMqg4eE');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
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
