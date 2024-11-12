<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        EmailVerifier $emailVerifier
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashage du mot de passe et définition du statut initial
            $user
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                )
                ->setStatus(UserStatusEnum::INACTIF);

            try {
                $entityManager->persist($user);
                $entityManager->persist($user->getInfosUser());
                $entityManager->flush();

                // Envoi de l'e-mail de confirmation
                $emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('booksinder@registration.com', 'Booksinder'))
                        ->to($user->getEmail())
                        ->subject('Merci de confirmer votre e-mail')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $this->addFlash('success', [
                    'title' => 'mail de confirmation',
                    'message' => 'un mail de confirmation a été envoyé sur votre boite mail.'
                ]);

                return $this->redirectToRoute('app_login');
            } catch (UniqueConstraintViolationException $e) {
                // Gestion des erreurs d'unicité pour le nom d'utilisateur et le téléphone
                if (str_contains($e->getMessage(), 'UNIQ_AA81A6EA24A232CF')) {
                    $this->addFlash('error', [
                        'title' => 'Nom d\'utilisateur déjà utilisé',
                        'message' => 'Ce nom d\'utilisateur est déjà associé à un autre compte.'
                    ]);
                } elseif (str_contains($e->getMessage(), 'UNIQ_AA81A6EA6B01BC5B')) {
                    $this->addFlash('error', [
                        'title' => 'Téléphone déjà utilisé',
                        'message' => 'Ce numéro de téléphone est déjà associé à un autre compte.'
                    ]);
                }

                return $this->redirectToRoute('app_register');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/resend-verification/email', name: 'app_resend_verification_email')]
    public function resendVerification(Request $request, UserRepository $userRepository, EmailVerifier $emailVerifier): Response
    {
        $userId = $request->query->get('id');
        $user = $userRepository->find($userId);

        if (!$user || $user->getStatus() === UserStatusEnum::ACTIF) {
            $this->addFlash('error', [
                'title' => 'Erreur',
                'message' => 'Ce compte est déjà vérifié ou n\'existe pas.'
            ]);

            return $this->redirectToRoute('app_login');
        }

        // Envoyer un nouvel e-mail de vérification
        $emailVerifier->resendVerificationEmail($user, 'app_verify_email');

        $this->addFlash('success', [
            'title' => 'Succès',
            'message' => 'Un nouveau lien de vérification vous a été envoyé.'
        ]);

        return $this->redirectToRoute('app_login');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        EmailVerifier $emailVerifier
    ): Response {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // Valider le lien de confirmation de l'e-mail
        try {
            $emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', [
                'title' => 'Erreur de vérification',
                'message' => 'Le lien a expiré, veuillez redemander un nouveau lien.'
            ]);

            return $this->redirectToRoute('app_resend_verification_email', ['id' => $user->getId()]);
        }

        $this->addFlash('success', [
            'title' => 'Succès',
            'message' => 'Votre adresse e-mail a été vérifiée.'
        ]);


        return $this->redirectToRoute('app_login');
    }
}
