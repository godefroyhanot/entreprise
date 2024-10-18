<?php

namespace App\Controller\Api;

use App\Entity\Contrat;
use App\Form\ContratEntityType;
use App\Repository\ContratRepository;
use App\Repository\ClientRepository;
use App\Repository\ContratTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('api/contrat')]
final class ContratApiController extends AbstractController
{
    #[Route(name: 'api_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository, SerializerInterface $serializer): JsonResponse
    {
        $listContrats = $contratRepository->findAll();
        $jsonContrats = $serializer->serialize($listContrats, 'json', ["groups" => 'contrat']);
        return new JsonResponse($jsonContrats, Response::HTTP_OK, [], true);

    }

    #[Route('/{id}', name: 'api_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat, SerializerInterface $serializer): JsonResponse
    {
        $jsonContrat = $serializer->serialize($contrat, 'json', ["groups" => 'contrat']);

        return new JsonResponse($jsonContrat, Response::HTTP_OK, [], true);
    }

     #[Route('/new', name: 'api_contrat_new', methods: ['POST'])]
     public function new(Request $request, EntityManagerInterface $entityManager
     ,ClientRepository $clientRepository, ContratTypeRepository $contratTypeRepository
     ,SerializerInterface $serializer): JsonResponse

     {
        $now = new \DateTime();

        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $contratType = $contratTypeRepository->find($data["contratType"]);
        

        $newContrat = $serializer->deserialize($request->getContent(), Contrat::class, 'json', []);
        $newContrat
            ->setClient($client)
            ->setType($contratType)
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
            ->setStatus("on")
        ;

        
        $entityManager->persist($newContrat);
        $entityManager->flush();

        $jsonContrat = $serializer->serialize($newContrat, 'json', ['groups' => ['contrat']]);

        return new JsonResponse($jsonContrat, Response::HTTP_CREATED, [], true);
     }


     #[Route('/{id}', name: 'api_contrat_delete', methods: ['DELETE'])]
     public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {


        $data = $request->toArray();
        if (isset($data['force']) && $data["force"] === true) {

            $entityManager->remove($contrat);
        } else {
            $contrat->setStatus('off');
            $entityManager->persist($contrat);
        }


        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/edit', name: 'api_contrat_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager
    ,ClientRepository $clientRepository, ContratTypeRepository $contratTypeRepository
    ,SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();


        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $contratType = $contratTypeRepository->find($data["contratType"]);
        $createdAt = $contrat->getCreatedAt();

        /* Façon par Deserialisation */
        $updatedContrat = $serializer->deserialize($request->getContent(), Contrat::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contrat]);
        $updatedContrat
            ->setClient($client)
            ->setType($contratType)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($now)
            ->setStatus("on")
        ;

        $entityManager->persist($updatedContrat);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
