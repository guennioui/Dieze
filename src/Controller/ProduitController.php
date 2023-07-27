<?php

namespace App\Controller;

use App\Classes\Search;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/our-products", name="products")
     */
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $this->manager->getRepository(Produit::class)->findWithSearch($search);
        }
        $products = $this->manager->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product_show")
     */
    public function show($slug)
    {
        $product = $this->manager->getRepository(Produit::class)->findOneBySlug($slug);

        if (!$product) {
            return $this->redirectToRoute('our-products');
        }
        return $this->render('produit/show.html.twig', [
            'product' => $product
        ]);
    }
}
