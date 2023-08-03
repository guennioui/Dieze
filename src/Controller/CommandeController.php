<?php

namespace App\Controller;

use DateTime;
use App\Classes\Cart;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\LigneCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/commande", name="commande")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('adresse_add ');
        }
        $form = $this->createForm(CommandeType::class, null, [
            'user' => $this->getUser()
        ]);
        return $this->render('commande/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="commande_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(CommandeType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commande = new Commande();
            $dateCommade = new DateTime();
            $transporteur = $form->get('transporteur')->getData();
            $commande->setUser($this->getUser());
            $commande->setDateCommande($dateCommade);
            $commande->setTransporteur($transporteur);
            $commande->setStatus('non payer');
            $commande->setReference($dateCommade->format('dmY') . "-" . uniqid());

            $this->entityManager->persist($commande);

            foreach ($cart->getFull() as $product) {
                $ligneCommande = new LigneCommande();
                $ligneCommande->setProduit($product['product']);
                $ligneCommande->setCommande($commande);
                $ligneCommande->setQuantity($product['quantity']);
                $ligneCommande->setMontant($product['product']->getPrix() * $product['quantity']);

                $this->entityManager->persist($ligneCommande);
            }

            $this->entityManager->flush();

            return $this->render('commande/add.html.twig', [
                'cart' => $cart->getFull(),
                'transporteur' => $transporteur,
                'delivery_address' => 'sale',
                'reference' => $commande->getReference()
            ]);
        }
        return $this->redirectToRoute('cart');
    }
}
