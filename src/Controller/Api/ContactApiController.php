<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Repository\ClientRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/contact')]
final class ContactApiController extends AbstractController
{
    #[Route(name: 'api_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository, SerializerInterface $serializer): Response
    {
        $listContacts = $contactRepository->findAll();
        $jsonContacts = $serializer->serialize($listContacts, 'json', ['groups' => ['contact']]);
        return new JsonResponse($jsonContacts, Response::HTTP_OK, [], true);
    }


    #[Route('/{id}', name: 'api_contact_show', methods: ['GET'])]
    public function show(Contact $contact, SerializerInterface $serializer): Response
    {
        $jsonContact = $serializer->serialize($contact, 'json', ['groups' => ['contact']]);
        return new JsonResponse($jsonContact, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'api_contact_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();

        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);

        $newContact = $serializer->deserialize($request->getContent(), Contact::class, 'json', []);
        $newContact
            // ->setClient($client)
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
            ->setStatus("on")

        ;

        $entityManager->persist($newContact);
        $entityManager->flush();

        $jsonContact = $serializer->serialize($newContact, 'json', ['groups' => ['contact']]);

        return new JsonResponse($jsonContact, Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}/edit', name: 'api_contact_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager, ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();

        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $createdAt = $contact->getCreatedAt();

        $updateContact = $serializer->deserialize($request->getContent(), Contact::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contact]);
        $updateContact
            // ->setClient($client) 
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($now);

        $entityManager->persist($updateContact);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'api_contact_delete', methods: ['DELETE'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data["force"] === true) {
            $entityManager->remove($contact);
        } else {
            $contact->setStatus("off");
            $entityManager->persist($contact);
        }

        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
