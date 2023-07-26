<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $User = new User();
        $form = $this->createForm(RegistrationType::class, $User);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $User = $form->getData();
            $hashedPassword = $hasher->hashPassword($User, $User->getPassword());
            $User->setPassword($hashedPassword);
            $manager->persist($User);
            $manager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
