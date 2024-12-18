<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conversation', name: 'app_conversation')]
class ConversationController extends AbstractController
{
    #[Route('/new{id}', name: '_new')]
    public function index(
        User $recipient,
        EntityManagerInterface $entityManager,
        ConversationRepository $conversationRepository
    ): Response {
        /** @var User $sender */
        $sender = $this->getUser();

        //verification de conv existante
        $existingConversation = $conversationRepository->findConvBetweenusers($recipient, $sender);

        if ($existingConversation) {
            return $this->redirectToRoute('app_conversation_show', ['id' => $existingConversation->getId()]);
        }

        $conversation = new Conversation();
        $conversation->addParticipant($sender);
        $conversation->addParticipant($recipient);

        $entityManager->persist($conversation);
        $entityManager->flush();

        return $this->redirectToRoute('app_conversation_show', ['id' => $conversation->getId()]);
    }

    #[Route('/{id}', name: '_show')]
    public function show(
        Request $request,
        Conversation $conversation,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifie si l'utilisateur connecté fait partie des participants de la conversation
        if (!$conversation->getParticipants()->contains($this->getUser())) {
            // Si l'utilisateur n'est pas un participant, redirige-le ou affiche une erreur
            $this->addFlash('error', [
                'title' => 'Accés refusé',
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette conversation!'
            ]);
            return $this->redirectToRoute('home'); // Ou la route de ton choix
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message
                ->setWriter($this->getUser())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setConversation($conversation)
            ;

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_conversation_show', ['id' => $conversation->getId()]);
        }

        return $this->render('conversation/index.html.twig', [
            'conversation' => $conversation,
            'form' => $form->createView(),
        ]);
    }
}
