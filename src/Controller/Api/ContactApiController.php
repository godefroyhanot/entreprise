<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
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

    // #[Route('/new', name: 'api_contact_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
  
    // }

    // #[Route('/{id}/edit', name: 'api_contact_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    // {

    // }

    // #[Route('/{id}', name: 'api_contact_delete', methods: ['POST'])]
    // public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    // {

    // }
}
