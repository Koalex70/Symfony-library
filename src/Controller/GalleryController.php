<?php

namespace App\Controller;

use App\Entity\Books;
use App\Form\BooksType;
use Doctrine\ORM\Mapping\Entity;
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
     * @Route("/gallery/edit/{book}", name="edit_book")
     * @param Request $request
     * @param Books $book
     * @return Response
     */
    public function edit(Request $request, Books $book)
    {
        $form = $this->createForm(BooksType::class, $book, [
            'action' => $this->generateUrl('edit_book', [
                'book' => $book->getId(),
            ]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            $em = $this->getDoctrine()->getManager();
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('gallery');
    }
}
