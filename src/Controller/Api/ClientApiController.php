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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/client')]
final class ClientApiController extends AbstractController
{
    #[Route(name: 'api_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $listClients = $clientRepository->findAll();
        $jsonClients = $serializer->serialize($listClients, 'json', ['groups' => ['client']]);
        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_client_show', methods: ['GET'])]
    public function show(Client $client, SerializerInterface $serializer): JsonResponse
    {
        $jsonClient = $serializer->serialize($client, 'json', ['groups' => ['client']]);

        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'api_client_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();

        // Récupérer les données de la requête
        $data = $request->toArray();

        /* Façon par Désérialisation */
        $newClient = $serializer->deserialize($request->getContent(), Client::class, 'json', []);
        $newClient
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
            ->setStatus('on');

        $entityManager->persist($newClient);
        $entityManager->flush();

        $jsonClient = $serializer->serialize($newClient, 'json', ['groups' => ['client']]);

        return new JsonResponse($jsonClient, Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}/edit', name: 'api_client_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();

        /* Façon par Désérialisation */
        $updatedClient = $serializer->deserialize($request->getContent(), Client::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $client]);
        $updatedClient->setUpdatedAt($now);

        $entityManager->persist($updatedClient);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'api_client_delete', methods: ['DELETE'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): JsonResponse
    {
        // Désactiver le client en changeant son statut

        $data = $request->toArray();
        if (isset($data['force']) && $data["force"] === true) {
            $entityManager->remove($client);
        } else {
            $client->setStatus('off');
            $client->setUpdatedAt(new \DateTime());
            $entityManager->persist($client);
        }
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
