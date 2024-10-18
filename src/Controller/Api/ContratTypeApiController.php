<?php

namespace App\Controller\Api;

use App\Entity\ContratType;
use App\Form\ContratTypeType;
use App\Repository\ContratTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('api/contrattype')]
final class ContratTypeApiController extends AbstractController
{
    #[Route(name: 'api_contrattype_index', methods: ['GET'])]
    public function index(ContratTypeRepository $contratTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $listContratsType = $contratTypeRepository->findAll();
        $jsonContratsType = $serializer->serialize($listContratsType, 'json', ["groups" => 'contratType']);
        return new JsonResponse($jsonContratsType, Response::HTTP_OK, [], true);

    }

    #[Route('/{id}', name: 'api_contrat_show', methods: ['GET'])]
    public function show(ContratType $contratType, SerializerInterface $serializer): JsonResponse
    {
        $jsonContratType = $serializer->serialize($contratType, 'json', ["groups" => 'contratType']);

        return new JsonResponse($jsonContratType, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'api_contratType_new', methods: ['POST'])]
     public function new(Request $request, EntityManagerInterface $entityManager,SerializerInterface $serializer): JsonResponse

     {
        $now = new \DateTime();

        //$data = $request->toArray();
        //$contratType = $contratTypeRepository->find($data["contratType"]);
        

        $newContratType = $serializer->deserialize($request->getContent(), ContratType::class, 'json', []);
        $newContratType
            //->setType($contratType)
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
            ->setStatus("on")
        ;

        
        $entityManager->persist($newContratType);
        $entityManager->flush();

        $jsonContrat = $serializer->serialize($newContratType, 'json', ['groups' => ['contrat']]);

        return new JsonResponse($jsonContrat, Response::HTTP_CREATED, [], true);
     }


     #[Route('/{id}', name: 'api_contrat_delete', methods: ['DELETE'])]
     public function delete(Request $request, ContratType $contratType, EntityManagerInterface $entityManager): JsonResponse
    {


        $data = $request->toArray();
        if (isset($data['force']) && $data["force"] === true) {

            $entityManager->remove($contratType);
        } else {
            $contratType->setStatus('off');
            $entityManager->persist($contratType);
        }


        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/edit', name: 'api_contrat_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, ContratType $contratType, EntityManagerInterface $entityManager ,SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();


        $data = $request->toArray();
        //$contratType = $contratTypeRepository->find($data["contratType"]);
        $createdAt = $contratType->getCreatedAt();

        /* Façon par Deserialisation */
        $updatedContratType = $serializer->deserialize($request->getContent(), ContratType::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contratType]);
        $updatedContratType
            //->setType($contratType)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($now)
            ->setStatus("on")
        ;

        $entityManager->persist($updatedContratType);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
