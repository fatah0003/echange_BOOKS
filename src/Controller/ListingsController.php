<?php

namespace App\Controller;

use App\Entity\Books;
use App\Entity\Exchange;
use App\Entity\User;
use App\Enum\ExchangeEnum;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ListingType;
use App\Form\SearchType;
use App\Repository\ExchangeRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: "/listings", name: "listings_")]
class ListingsController extends AbstractController
{
  //Méthode pour afficher la liste des annonces sur la page annonce
    #[Route(path: "", name: "show")]
    public function listings(Request $request, BooksRepository $booksRepository, PaginatorInterface $paginator, ExchangeRepository $exchangeRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 12);

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez le QueryBuilder pour la pagination
            $queryBuilder = $booksRepository->searchQueryBuilder(
                $form->get('title')->getData(),
                $form->get('author')->getData(),
                $form->get('location')->getData(),
                $form->get('exchangeType')->getData(),
                $form->get('bookCategorie')->getData()
            );

            $books = $paginator->paginate($queryBuilder, 1, $limit);
        } else {
            // Pagination des livres sans filtre
            $books = $booksRepository->pagination($page, $limit);
        }

        return $this->render('listings/listings.html.twig', [
            'books' => $books,
            'form' => $form,
            'received_requests_number' => $receivedRequestsNumber,
        ]);
    }

  //Méthode pour creer une annonce (page formulaire de création)
    #[Route(path: "/add", name: "add")]
    #[IsGranted('ROLE_USER')]
    public function addListings(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
      /**
       * @var User $user
       */
        $user = $this->getUser();
      // dd($request);
        $books = new Books();
        $form = $this->createForm(ListingType::class, $books);
        $form->handleRequest($request);
        // dd($form);
        // dd($form->isValid());
        if ($form->isSubmitted() && $form->isValid()) {
            $books->setUser($user);

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
            return $this->redirectToRoute('listings_showone', ['id' => $books->getId()]);
        }

        return $this->render('listings/add.html.twig', [
        'form' => $form
        ]);
    }
  // Méthode pour afficher une seule annonce
    #[Route(path: "/showone/{id}", name: "showone")]
    public function showone(Books $books, EntityManagerInterface $entityManager, ExchangeRepository $exchangeRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        $user = $books->getUser();
        $currentUser = $this->getUser();

        // Vérifier si l'utilisateur a une demande d'échange pour ce livre
        $requestExists = $entityManager->getRepository(Exchange::class)
          ->findOneBy(['bookOne' => $books, 'userRequester' => $currentUser]);

        return $this->render('listings/showone.html.twig', [
          'book' => $books,
          'user' => $user,
          'requestExists' => $requestExists !== null, // true si la demande existe, sinon false
          'exchange' => $requestExists,
          'received_requests_number' => $receivedRequestsNumber,
        ]);
    }


  // Méthode pour supprimer une annonce
#[Route(path: "/remove/{id}", name: "remove")]
#[IsGranted('ROLE_USER')]
public function remove(
    Request $request,
    Books $books,
    EntityManagerInterface $entityManager,
    MailerInterface $mailer
): Response {
    /** @var User $user */
    $user = $this->getUser();

    // Vérifie si l'utilisateur n'est admin ou propriétaire de l'annonce
    if (!$this->isGranted('ROLE_ADMIN') && !$user->getBooks()->contains($books)) {
        return $this->redirectToRoute('listings_show');
    }

    $token = $request->getPayload()->get('token');

    if ($this->isCsrfTokenValid('delete-book' . $books->getId(), $token)) {
        $entityManager->remove($books);
        $entityManager->flush();

        // Envoie un email de confirmation
        $email = (new Email())
            ->from('admin@booksfinder.com')
            ->to($books->getUser()->getEmail())
            ->subject('Confirmation : Votre annonce a été supprimée avec succès')
            ->html('Annonce supprimée');

        $mailer->send($email);

        $this->addFlash('success', [
            'title' => 'Annonce supprimée',
            'message' => 'L\'annonce a été supprimée avec succès.'
        ]);

        return $this->redirectToRoute('listings_show');
    }

    return $this->redirectToRoute('listings_show');
}


  // Méthode pour modifier une annonce (page formulaire de modification)
#[Route(path: "/update/{id}", name: "update")]
#[IsGranted('ROLE_USER')]
public function update(
    Books $books,
    Request $request,
    EntityManagerInterface $entityManager,
    MailerInterface $mailer
): Response {
    /** @var User $user */
    $user = $this->getUser();

    // Vérifie si l'utilisateur est admin ou propriétaire de l'annonce
    if (!$this->isGranted('ROLE_ADMIN') && !$user->getBooks()->contains($books)) {
        return $this->redirectToRoute('listings_show');
    }

    $form = $this->createForm(ListingType::class, $books);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $books->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($books);
        $entityManager->flush();

        // Envoie un email de confirmation
        $email = (new Email())
            ->from('admin@booksfinder.com')
            ->to($books->getUser()->getEmail())
            ->subject('Confirmation : Votre annonce a été modifiée avec succès')
            ->html('Annonce modifiée');

        $mailer->send($email);

        $this->addFlash('success', [
            'title' => 'Annonce modifiée',
            'message' => 'L\'annonce a été modifiée avec succès.'
        ]);

        return $this->redirectToRoute('listings_showone', ['id' => $books->getId()]);
    }

    return $this->render('listings/add.html.twig', [
        'form' => $form,
        'book' => $books
    ]);
}

  //Méthode pour les favoris
    #[Route('/favorite/{id}', name: 'toggle_favorite')]
    public function toggleFavorite(Books $books, EntityManagerInterface $entityManager, Request $request): Response
    {
      /** @var User $user */
        $user = $this->getUser();
        $user->toggleFavorite($books);

        $entityManager->flush();
        $this->addFlash('success', [
        'title' => 'Success title',
        'message' => 'Message de notification'
        ]);

        //return $this->redirectToRoute('listings_show');
        return $this->redirect($request->headers->get('referer'));
    }

    private function getReceivedRequestsNumber(ExchangeRepository $exchangeRepository): int
    {
        $receivedRequests = $exchangeRepository->findBy([
            'userReceiver' => $this->getUser(),
            'status' => ExchangeEnum::PENDING->value,
        ]);

        return count($receivedRequests);
    }
}
