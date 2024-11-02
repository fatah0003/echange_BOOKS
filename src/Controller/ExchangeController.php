<?php

namespace App\Controller;

use App\Entity\Books;
use App\Entity\Exchange;
use App\Enum\ExchangeEnum;
use App\Repository\ExchangeRepository;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/exchange', name: 'app_exchange_')]
#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
class ExchangeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ExchangeRepository $exchangeRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        // Récupérer les 5 dernières demandes envoyées et reçues
        $latestSentRequests = $exchangeRepository->findLatestSentRequests($this->getUser());
        $latestReceivedRequests = $exchangeRepository->findLatestReceivedRequests($this->getUser());
        // Récupérer les 5 derniers échanges complétés
    $latestCompletedRequests = $exchangeRepository->findLatestCompletedRequests($this->getUser());

        return $this->render('exchange/index.html.twig', [
            'controller_name' => 'ExchangeController',
            'latest_sent_requests' => $latestSentRequests,
            'latest_received_requests' => $latestReceivedRequests,
            'latest_completed_requests' => $latestCompletedRequests,
            'received_requests_number' => $receivedRequestsNumber,
        ]);
    }

    #[Route('/sent', name: 'sent')]
    public function sentRequests(ExchangeRepository $exchangeRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        // Récupérer toutes les demandes envoyées
        $sentRequests = $exchangeRepository->findBy(['userRequester' => $this->getUser()]);

        return $this->render('exchange/sent.html.twig', [
            'sent_requests' => $sentRequests,
            'received_requests_number' => $receivedRequestsNumber,
        ]);
    }

    #[Route('/received', name: 'received')]
    public function receivedRequests(ExchangeRepository $exchangeRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        // Récupérer toutes les demandes reçues
        $receivedRequests = $exchangeRepository->findBy(['userReceiver' => $this->getUser()]);

        return $this->render('exchange/received.html.twig', [
            'received_requests' => $receivedRequests,
            'received_requests_number' => $receivedRequestsNumber,
        ]);
    }

    #[Route('/received/{id}', name: 'received_select_books')]
    public function selectBooks(int $id, ExchangeRepository $exchangeRepository, BooksRepository $booksRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        // Récupérer la demande reçue par son ID
        $exchangeRequest = $exchangeRepository->find($id);

        // Vérifier si la demande existe et si l'utilisateur est bien le destinataire
        if (!$exchangeRequest || $exchangeRequest->getUserReceiver() !== $this->getUser()) {
            throw $this->createNotFoundException('Demande non trouvée.');
        }

        // Récupérer tous les livres de l'utilisateur demandeur (userRequester)
        $books = $booksRepository->findBy(['user' => $exchangeRequest->getUserRequester()]);

        return $this->render('exchange/select_books.html.twig', [
            'exchange_request' => $exchangeRequest,
            'books' => $books, // Tous les livres de l'utilisateur demandeur
            'received_requests_number' => $receivedRequestsNumber,
        ]);
    }

    #[Route('/request/{id}', name: 'request', methods: ['POST'])]
    public function createRequest(Books $bookOne, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Récupérer le livre que l'utilisateur souhaite échanger
        if (!$bookOne) {
            throw $this->createNotFoundException('Livre non trouvé.');
        }

        // Créer une nouvelle demande d'échange
        $exchange = new Exchange();
        $exchange->setUserRequester($this->getUser()); // Utilisateur qui fait la demande
        $exchange->setBookOne($bookOne); // Livre que l'utilisateur propose dans l'échange
        $exchange->setUserReceiver($bookOne->getUser()); // Assigner l'utilisateur possesseur du livre comme destinataire
        $exchange->setStatus(ExchangeEnum::PENDING); // Statut par défaut

        // Définir la date de création avec DateTimeImmutable
        $exchange->setCreatedAt(new \DateTimeImmutable()); // Définit l'heure actuelle

        // Sauvegarder la demande d'échange
        $entityManager->persist($exchange);
        $entityManager->flush();

        // Envoie un email de confirmation au demandeur
        // $emailRequester = (new Email())
        //     ->from('admin@booksfinder.com')
        //     ->to($this->getUser()->getEmail())
        //     ->subject('Confirmation : Demande envoyée')
        //     ->html('Votre demande d\'échange a été envoyée avec succès.');

        // $mailer->send($emailRequester);

        // Envoie un email de notification au receveur
        $emailReceiver = (new Email())
            ->from('admin@booksfinder.com')
            ->to($bookOne->getUser()->getEmail()) // Assure-toi que cette méthode récupère bien l'email du receveur
            ->subject('Nouvelle demande d\'échange')
            ->html('Vous avez reçu une nouvelle demande d\'échange. Connectez-vous pour la consulter.');

        $mailer->send($emailReceiver);

        $this->addFlash('success', [
            'title' => 'Demande envoyée',
            'message' => 'La demande a été envoyée avec succès.'
        ]);

        return $this->redirectToRoute('app_exchange_index');
    }

    #[Route('/accept/{id}', name: 'accept', methods: ['POST'])]
public function acceptRequest(
    Exchange $exchange,
    BooksRepository $booksRepository,
    Request $request,
    EntityManagerInterface $entityManager,
    MailerInterface $mailer
): Response {
    // Vérifier si la demande existe et si l'utilisateur est bien le destinataire
    if (!$exchange || $exchange->getUserReceiver() !== $this->getUser()) {
        throw $this->createNotFoundException('Demande non trouvée.');
    }

    // Récupérer le livre sélectionné
    $bookTwoId = $request->request->get('selected_book');
    $bookTwo = $booksRepository->find($bookTwoId);

    if (!$bookTwo) {
        throw $this->createNotFoundException('Livre non trouvé.');
    }

    // Mettre à jour la demande d'échange
    $exchange->setBookTwo($bookTwo); // Livre choisi par le destinataire
    $exchange->setAcceptedAt(new \DateTimeImmutable()); // Date d'acceptation
    $exchange->setStatus(ExchangeEnum::VALIDATED); // Statut mis à jour

    // Sauvegarder les changements
    $entityManager->persist($exchange);
    $entityManager->flush();

    // Envoi d'un email de confirmation à l'utilisateur demandeur (requester)
    $emailRequester = (new Email())
        ->from('admin@booksfinder.com')
        ->to($exchange->getUserRequester()->getEmail())
        ->subject('Demande d\'échange acceptée')
        ->html('Votre demande d\'échange a bien été acceptée.');
    $mailer->send($emailRequester);

    // Envoi d'un email de confirmation au receveur (celui qui accepte l'échange)
    // $emailReceiver = (new Email())
    //     ->from('admin@booksfinder.com')
    //     ->to($this->getUser()->getEmail())
    //     ->subject('Confirmation : Echange effectué')
    //     ->html('Votre échange a été effectué avec succès.');
    // $mailer->send($emailReceiver);

    $this->addFlash('success', [
        'title' => 'Demande acceptée',
        'message' => 'La demande a été acceptée avec succès.'
    ]);

    return $this->redirectToRoute('app_exchange_received');
}


    #[Route('/cancel/{id}', name: 'cancel_request', methods: ['POST'])]
    public function cancelRequest(Exchange $exchange, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la demande d'échange appartient à l'utilisateur connecté
        if ($exchange->getUserRequester() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à annuler cette demande.');
        }

        // Supprimer la demande d'échange
        $entityManager->remove($exchange);
        $entityManager->flush();

        // Ajouter un message flash pour confirmer l'annulation
        // $this->addFlash('success', 'Demande de réservation annulée avec succès.');

        // Rediriger vers l'index des échanges
        return $this->redirectToRoute('app_exchange_index');
    }

    private function getReceivedRequestsNumber(ExchangeRepository $exchangeRepository): int
    {
        $receivedRequests = $exchangeRepository->findBy([
            'userReceiver' => $this->getUser(),
            'status' => ExchangeEnum::PENDING->value,
        ]);

        return count($receivedRequests);
    }

    #[Route('/reject-request/{id}', name: 'reject_request', methods: ['POST'])]
    public function rejectRequest(Exchange $exchange, EntityManagerInterface $entityManager, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = $request->request->get('_token');

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('reject' . $exchange->getId(), $token))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        // Change le statut à REJECTED et enregistre
        $exchange->setStatus(ExchangeEnum::REJECTED);
        $entityManager->persist($exchange);
        $entityManager->flush();

        return $this->redirectToRoute('app_exchange_received'); // Redirection vers la page appropriée
    }

    #[Route('/completed', name: 'completed')]
public function completedRequests(ExchangeRepository $exchangeRepository): Response
{
    $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
    // Récupérer toutes les demandes acceptées de l'utilisateur connecté
    $completedRequests = $exchangeRepository->findBy([
        'status' => ExchangeEnum::VALIDATED,
        'userRequester' => $this->getUser() // Assure-toi que tu veux filtrer par utilisateur demandeur
    ]);

    // Si tu veux afficher aussi les échanges où l'utilisateur est le récepteur
    $completedRequestsReceiver = $exchangeRepository->findBy([
        'status' => ExchangeEnum::VALIDATED,
        'userReceiver' => $this->getUser()
    ]);

    // Combine les deux résultats
    $completedRequests = array_merge($completedRequests, $completedRequestsReceiver);

    return $this->render('exchange/completed.html.twig', [
        'completed_requests' => $completedRequests,
        'received_requests_number' => $receivedRequestsNumber,
    ]);
}

}
