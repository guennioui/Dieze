<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/commande/merci/{stripeSessionId}", name="commande_validate")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->entityManager->getRepository(Commande::class)->findOneByStripeSessionId($stripeSessionId);
        //dd($order);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($order->getStatus() === 'non payer') {
            $order->setStatus('payer');
            $this->entityManager->flush();
            $cart->remove();
        }
        //     // $mail = new Mail();
        //     // $content = sprintf('Bonjour %s <br> Merci pour votre commande.', $order->getUser()->getFirstName());
        //     // $mail->send(
        //     //     $order->getUser()->getEmail(),
        //     //     sprintf('%s %s', $order->getUser()->getFirstName(), $order->getUser()->getLastName()),
        //     //     'Commande validée - "La boutique"',
        //     //     $content
        //     // );
        // }
        return $this->render(
            'commande_validate/index.html.twig',
            [
                'order' => $order
            ]
        );
    }
}
