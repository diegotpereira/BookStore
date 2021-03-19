<?php

namespace App\Controller;

use App\Entity\Book; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController{

    /** 
      * @Route("/books/author") 
   */
    public function authorAction(){
        
        return $this->render('books/author.html.twig'); 
    }

    /**
     *  @Route("/books/display", name="app_book_display")
    */
    public function displayAction(){
        $bk = $this->getDoctrine()
        ->getRepository('App:Book')
        ->findAll();
        return $this->render('books/display.html.twig', array('data' => $bk));

    }
    /**
       * @Route("/books/new", name="app_book_new")
    */
    public function newAction(Request $request){
        $book = new Book();
        $form = $this->createFormBuilder($book)
            ->add('nome', TextType::class)
            ->add('autor', TextType::class)
            ->add('preco', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            $book = $form->getData();
            $doct = $this->getDoctrine()->getManager();

            $doct->persist($book);

            $doct->flush();

            return $this->redirectToRoute('app_book_display');
        } else {
            # code...
            return $this->render('books/new.html.twig', array(
               'form' => $form->createView(),
            ));
        }
    }
    /** 
       * @Route("/books/update/{id}", name = "app_book_update" ) 
    */ 
    public function updateAction($id, Request $request) { 
    $doct = $this->getDoctrine()->getManager(); 
    $bk = $doct->getRepository('App:Book')->find($id);  
        
    if (!$bk) { 
        throw $this->createNotFoundException( 
            'No book found for id '.$id 
        ); 
    }  
    $form = $this->createFormBuilder($bk) 
        ->add('nome', TextType::class) 
        ->add('autor', TextType::class) 
        ->add('preco', TextType::class) 
        ->add('save', SubmitType::class, array('label' => 'Submit')) 
        ->getForm();  
    
    $form->handleRequest($request);  
    
    if ($form->isSubmitted() && $form->isValid()) { 
        $book = $form->getData(); 
        $doct = $this->getDoctrine()->getManager();  
        
        $doct->persist($book);  

        $doct->flush(); 
        return $this->redirectToRoute('app_book_display'); 
    } else {  
        return $this->render('books/new.html.twig', array(
            'form' => $form->createView(), 
        )); 
    } 
    }
    /** 
       * @Route("/books/delete/{id}", name="app_book_delete") 
    */ 
    public function deleteAction($id) { 
    $doct = $this->getDoctrine()->getManager(); 
    $bk = $doct->getRepository('App:Book')->find($id); 
    
    if (!$bk) { 
        throw $this->createNotFoundException('Nenhum livro encontrado com id '.$id); 
    } 
    $doct->remove($bk); 
    $doct->flush(); 
    return $this->redirectToRoute('app_book_display'); 
    } 
}