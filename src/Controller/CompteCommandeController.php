<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteCommandeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/mes-commandes", name="account_order")
     */
    public function index()
    {
        $orders = $this->entityManager->getRepository(Commande::class)->findBy(["user" => $this->getUser(), "status" => "payer"]);
        dd($orders);
        return $this->render('account/commande.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/compte/mes-commandes/{reference}", name="account_order_show")
     */
    public function show($reference)
    {

        $order = $this->entityManager->getRepository(Commande::class)->findOneByReference($reference);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}
