<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AddAdresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/adresses", name="app_adresse")
     */
    public function index(): Response
    {
        return $this->render('account/adresse.html.twig');
    }

    /**
     * @Route("/account/ajouter-une-adresse", name="adresse_add")
     */
    public function add(Request $request): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AddAdresseType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUser($this->getUser());
            $this->entityManager->persist($adresse);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_adresse');
        }
        return $this->render('account/adresse_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/modifier-une-adresse/{id}", name="addresse_edit")
     */
    public function edit(Request $request, $id)
    {
        $adresse = $this->entityManager->getRepository(Adresse::class)->findOneById($id);
        if (!$adresse || $adresse->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddAdresseType::class, $adresse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_adresse');
        }

        return $this->render('account/adresse_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/supprimer-une-adresse/{id}", name="addresse_delete")
     */
    public function delete($id)
    {
        $adresse = $this->entityManager->getRepository(Adresse::class)->findOneById($id);
        if ($adresse && $adresse->getUser() === $this->getUser()) {
            $this->entityManager->remove($adresse);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_adresse');
    }
}
