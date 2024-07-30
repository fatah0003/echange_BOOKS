<?php

namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ListingType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: "/listings", name: "listings_")]
class ListingsController extends AbstractController
{
  //Méthode pour afficher la liste des annonces sur la page annonce 
  #[Route(path: "", name: "show")]
  public function listings(Request $request, BooksRepository $booksRepository): Response
  {

    $books = $booksRepository->findAll();

    return $this->render('listings/listings.html.twig', [
      'books' => $books,
    ]);
  }
  //Méthode pour creer une annonce (page formulaire de création) 
  #[Route(path: "/add", name: "add")]
  #[IsGranted('ROLE_USER')]
  public function addListings(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
  {
    $books = new Books();
    $form = $this->createForm(ListingType::class, $books);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $books->setUser($this->getUser());
      $entityManager->persist($books);
      $entityManager->flush();

      $email = (new Email())
        ->from('admin@booksinder.com')
        ->to($books->getUser()->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Confirmation : Votre nouvelle annonce a été créée avec succès')
        //->text('Sending emails is fun again!')
        ->html($this->renderView('email/index.html.twig', ['book' => $books]));
        
        $mailer->send($email);

      $this->addFlash('success', [
        'title' => 'Success title',
        'message' => 'Message de notification'
      ]);
      return $this->redirectToRoute('listings_show');
    }

    return $this->render('listings/add.html.twig', [
      'form' => $form
    ]);
  }
  // Méthode pour afficher une seule annonce
  #[Route(path: "/showone/{id}", name: "showone")]
  public function showone(Books $books): Response
  {
    return $this->render('listings/showone.html.twig', [
      'book' => $books,
    ]);
  }

  //Méthode pour supprimer une annonce
  #[Route(path: "/remove/{id}", name: "remove")]
  #[IsGranted('ROLE_USER')]
  public function remove(Request $request, Books $books, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    if (!$user->getBooks()->contains($books)) {
      return $this->redirectToRoute('listings_show');
    }
    $token = $request->getPayload()->get('token');

    if ($this->isCsrfTokenValid('delete-book' . $books->getId(), $token)) {
      $entityManager->remove($books);
      $entityManager->flush();

      $email = (new Email())
        ->from('admin@booksinder.com')
        ->to($books->getUser()->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Confirmation : Votre annonce a été supprimée avec succès')
        //->text('Sending emails is fun again!')
        ->html('Annonce supprimée');

        $mailer->send($email);

      $this->addFlash('success', [
        'title' => 'Success title',
        'message' => 'Message de notification'
      ]);
      return $this->redirectToRoute('listings_show');
    }

    return $this->redirectToRoute('listings_show');
  }

  //Méthode pour modifier une annonce (page formulaire de modification) 
  #[Route(path: "/update/{id}", name: "update")]
  #[IsGranted('ROLE_USER')]
  public function update(Books $books, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    if (!$user->getBooks()->contains($books)) {
      return $this->redirectToRoute('listings_show');
    }
    $form = $this->createForm(ListingType::class, $books);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $books->setUpdatedAt(new \DateTimeImmutable());
      $entityManager->persist($books);
      $entityManager->flush();

      $email = (new Email())
        ->from('admin@booksinder.com')
        ->to($books->getUser()->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Confirmation : Votre annonce a été modifiée avec succès')
        //->text('Sending emails is fun again!')
        ->html('Annonce modifiée');

        $mailer->send($email);

      $this->addFlash('success', [
        'title' => 'Success title',
        'message' => 'Message de notification'
      ]);
      return $this->redirectToRoute('listings_show');
    }

    return $this->render('listings/add.html.twig', [
      'form' => $form,
      'book' => $books
    ]);
  }
  //Méthode pour les favoris
  #[Route('/favorite/{id}', name: 'toggle_favorite')]
  public function toggleFavorite(Books $books, EntityManagerInterface $entityManager): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $user->toggleFavorite($books);

    $entityManager->flush();
    $this->addFlash('success', [
      'title' => 'Success title',
      'message' => 'Message de notification'
    ]);
    return $this->redirectToRoute('listings_show');
  }
}
