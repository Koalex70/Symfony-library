<?php

namespace App\Controller;

use App\Entity\Books;
use App\Form\BooksType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/gallery/single/{book}", name="single_book")
     * @param Books $book
     * @return Response
     */
    public function single(Books $book)
    {
        return $this->render('gallery/form.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/gallery/add", name="add_book")
     * @return Response
     */
    public function add(Request $request)
    {
        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('gallery');
        }

        return $this->render('gallery/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gallery/delete/{book}", name="delete_book")
     * @param Books $book
     * @return Response
     */
    public function delete(Books $book)
    {
        return $this->render('gallery/form.html.twig', [
            'book' => $book,
        ]);
    }
}
