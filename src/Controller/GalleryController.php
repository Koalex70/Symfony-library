<?php

namespace App\Controller;

use App\Entity\Books;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="gallery")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository(Books::class)->findAll();

        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'books' => $books,
        ]);
    }
}
