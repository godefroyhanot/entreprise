<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Utilisation de l'annotation Route pour les contraintes
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/client')]
final class ClientApiController extends AbstractController
{
    #[Route('/', name: 'api_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, SerializerInterface $serializer): Response
    {
        $listClients = $clientRepository->findAll();
        $jsonClients = $serializer->serialize($listClients, 'json', ['groups' => ['client']]);
        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_client_show', methods: ['GET'])]
    public function show(Client $client, SerializerInterface $serializer): Response
    {
        $jsonClient = $serializer->serialize($client, 'json', ['groups' => ['client']]);

        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }

    // Les autres méthodes restent commentées
}
