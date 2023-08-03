<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classes\Cart;
use App\Entity\Commande;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session", methods={"POST"})
     */
    public function index(Cart $cart, $reference, EntityManagerInterface $em)
    {
        $productsForStripe = [];

        $DOMAIN = $_ENV['DOMAIN'];

        $order = $em->getRepository(Commande::class)->findOneByReference($reference);

        if (!$order) {
            return new JsonResponse(['error' => 'order']);
        }

        foreach ($cart->getFull() as $product) {

            $productsForStripe[] = [
                'price_data' => [
                    'currency' => 'MAD',
                    'unit_amount' => $product['product']->getPrix(),
                    'product_data' => [
                        'name' => $product['product']->getLibelle(),
                    ],
                ],
                'quantity' => $product['quantity'],
            ];
        }

        $productsForStripe[] = [
            'price_data' => [
                'currency' => 'MAD',
                'unit_amount' => $order->getTransporteur()->getPrix(),
                'product_data' => [
                    'name' => $order->getTransporteur()->getCompanyName()
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51NaJ51Caf8vRlywGZLluN6Q54MffElyLwTxGzHr98FeKzCZu0ovuIhrzekkM0Kz7B2OZnbMaSlEnmYVyGOpHVWEU00RcMqg4eE');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [$productsForStripe],
            'mode' => 'payment',
            'success_url' => $DOMAIN . '/success.html',
            'cancel_url' => $DOMAIN . '/cancel.html',
        ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
