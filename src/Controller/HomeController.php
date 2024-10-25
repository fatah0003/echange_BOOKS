<?php

namespace App\Controller;

use App\Enum\ExchangeEnum;
use App\Repository\ExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route(path: "/", name: "home")]
    public function index(ExchangeRepository $exchangeRepository): Response
    {
        // Appelle la méthode pour compter les demandes reçues
        $receivedRequestsNumber = $this->getReceivedRequestsNumber($exchangeRepository);

        return $this->render('index.html.twig', [
            'received_requests_number' => $receivedRequestsNumber, // Envoie le nombre
        ]);
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

