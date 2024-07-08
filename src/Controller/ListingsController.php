<?php
namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ListingType;
#[Route(path:"/listings", name:"listings_")]
class ListingsController extends AbstractController
{
   //Méthode pour afficher la liste des annonces sur la page annonce 
    #[Route(path:"", name:"show")]
    public function listings(BooksRepository $booksRepository): Response
    {
      $books = $booksRepository->findAll();
       return $this->render('listings/listings.html.twig', [
         'books'=>$books,
       ]);
    }
    //Méthode pour creer une annonce (page formulaire de création) 
    #[Route(path:"/add", name:"add")]
    public function addListings(Request $request, EntityManagerInterface $entityManager): Response
    {
      $books = new Books();
      $form = $this->createForm(ListingType::class, $books);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $books
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setFavorite(false)
         ;
         
         $entityManager->persist($books);
         $entityManager->flush();
         return $this->redirectToRoute('listings_show');
      }

       return $this->render('listings/add.html.twig',[
         'form'=> $form
       ]);
    }
    // Méthode pour afficher une seule annonce
    #[Route(path:"/showone/{id}", name:"showone")]
    public function showone(Books $books): Response
    {
      return $this->render('listings/showone.html.twig', [
         'book'=> $books,
      ]);
    }

    //Méthode pour supprimer une annonce
    #[Route(path:"/remove/{id}", name:"remove")]
    public function remove(Books $books, EntityManagerInterface $entityManager): Response
    {
      $entityManager->remove($books);
      $entityManager->flush();
      return $this->redirectToRoute('listings_show');
    }

   //Méthode pour modifier une annonce (page formulaire de modification) 
    #[Route(path:"/update", name:"uodate")]
    public function updateListings(): Response
    {
       return $this->render('listings/update.html.twig');
    }
}
    