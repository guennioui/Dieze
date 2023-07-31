<?php

namespace App\Controller;

use App\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(): Response
    {
        if(!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('adresse_add ');
        }
        $form = $this->createForm(CommandeType::class, null, [
            'user'=>$this->getUser()
        ]);
        return $this->render('commande/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
