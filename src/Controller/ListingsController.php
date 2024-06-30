<?php
namespace App\Controller;

use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
    public function addListings(): Response
    {
       return $this->render('listings/add.html.twig');
    }
   //Méthode pour modifier une annonce (page formulaire de modification) 
    #[Route(path:"/update", name:"uodate")]
    public function updateListings(): Response
    {
       return $this->render('listings/update.html.twig');
    }
    #[Route(path:"/remove", name:"remove")]
    public function removeListings(): Response
    {
       return $this->render('listings/remove.html.twig');
    }
}
    