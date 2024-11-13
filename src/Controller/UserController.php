<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ExchangeEnum;
use App\Enum\UserStatusenum;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\BooksRepository;
use App\Repository\ExchangeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
class UserController extends AbstractController
{
    // Méthode pour voir tous les utilisateurs
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security, ExchangeRepository $exchangeRepository, BooksRepository $booksRepository): Response
    {
        $sentRequests = $exchangeRepository->findAll();
        $receivedRequests = $exchangeRepository->findAll();
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        // Vérifier si l'utilisateur n'a pas le rôle d'administrateur
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Rediriger vers la page d'accueil
            return $this->redirectToRoute('home');
        }
    // Si l'utilisateur est admin, afficher la liste des utilisateurs
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'books' => $booksRepository->findAll(),

            'received_requests_number' => $receivedRequestsNumber,
            'sentRequests' => $sentRequests,
            'receivedRequests' => $receivedRequests,
        ]);
    }

    // Méthode pour voir les infos d'un utilisateur
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, ExchangeRepository $exchangeRepository, BooksRepository $booksRepository): Response
    {
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);
        $publishedBooks = $booksRepository->findByUser($user); // Récupère les livres publiés par cet utilisateur

        return $this->render('user/show.html.twig', [
        'user' => $user,
        'received_requests_number' => $receivedRequestsNumber,
        'published_books' => $publishedBooks, // Inclut les livres publiés dans le template
        ]);
    }


// Méthode pour modifier les infos d'un utilisateur
    #[Route('/update/{id}', name: 'update')]
    public function update(
        User $user,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {

        // Redirection si l'utilisateur actuel n'est pas autorisé
        if ($this->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $isModifyingOwnProfile = $this->getUser() === $user;

        // Le formulaire : l'admin n'a pas besoin de mot de passe sauf s'il modifie son propre profil
        $form = $this->createForm(UserType::class, $user, ['is_admin' => $isAdmin && !$isModifyingOwnProfile]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validation du mot de passe pour les utilisateurs non admin OU l'admin modifiant son propre profil
            if (!$isAdmin || $isModifyingOwnProfile) {
                $password = $form->get('password')->getData();

                if (!$password || !$userPasswordHasher->isPasswordValid($user, $password)) {
                    $this->addFlash('error', [
                    'title' => 'Erreur',
                    'message' => 'Mot de passe incorrect ou non fourni'
                    ]);

                    return $this->render('user/edit.html.twig', [
                        'UserType' => $form,
                        'user' => $user,
                    ]);
                }
            }

            // Gestion des rôles si l'utilisateur est admin et modifie un autre profil
            if ($isAdmin && !$isModifyingOwnProfile) {
                $roles = $form->get('roles')->getData();
                $user->setRoles($roles);
            }

            // Persistance des modifications
            $entityManager->persist($user);
            $entityManager->persist($user->getInfosUser());
            $entityManager->flush();

            // Envoi de l'email de confirmation
            $email = (new Email())
            ->from('admin@booksinder.com')
            ->to($user->getEmail())
            ->subject('Confirmation : Compte modifié')
            ->html('Votre compte a été modifié avec succès');
            $mailer->send($email);

            $this->addFlash('success', [
                'title' => 'Profil mis à jour !',
                'message' => 'Vos informations ont été mises à jour.'
            ]);
            // Redirection après modification
            return $this->redirectToRoute('app_user_show', [
            'id' => $user->getId(),
            ]);
        }

        // Rendu du formulaire si le formulaire n'est pas soumis ou est invalide
        return $this->render('user/edit.html.twig', [
        'UserType' => $form,
        'user' => $user,
        ]);
    }





   // Méthode pour désactiver un compte
    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    #[IsGranted(
        attribute: new Expression(
            'user === subject or is_granted("ROLE_ADMIN")'
        ),
        subject: 'user'
    )]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
    // Récupérer le token CSRF à partir des paramètres de requête
        $token = $request->request->get('_token');

    // Vérifier la validité du token CSRF
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $token)) {
            // Mettre à jour le statut de l'utilisateur en "supprimé"
            $user->setStatus(UserStatusenum::SUPPRIME);

            // Sauvegarder les modifications dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoyer un email à l'utilisateur pour lui notifier que son compte est désactivé
            $email = (new Email())
            ->from('admin@booksinder.com')
            ->to($user->getEmail())
            ->subject('Votre compte a été désactivé avec succès.')
            ->html('
                Votre compte a été désactivé avec succès. Si vous souhaitez le réactiver, veuillez nous contacter. Nous serons heureux de vous aider.
                <br><br>
                À bientôt :)
            ');
            $mailer->send($email);

            // Si l'utilisateur supprime son propre compte, déconnexion et invalidation de la session
            if ($this->getUser() === $user) {
                $this->container->get('security.token_storage')->setToken(null);
                $request->getSession()->invalidate();

                $this->addFlash('success', [
                    'title' => 'Compte désactivé',
                    'message' => 'Votre compte a été désactivé avec succès.'
                ]);

                return $this->redirectToRoute('app_login');
            }

            // Rediriger vers la page d'accueil après la mise à jour du statut (par un admin)
            return $this->redirectToRoute('home');
        }

    // Si le token CSRF n'est pas valide, rediriger vers la page d'accueil
        return $this->redirectToRoute('home');
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
