<?php

namespace App\Controller\Api;

use App\Entity\QuantityType;
use App\Form\QuantityTypeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuantityTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/quantity/type')]
final class QuantityTypeApiController extends AbstractController
{
    #[Route(name: 'api_quantity_type_index', methods: ['GET'])]
    public function index(QuantityTypeRepository $quantityTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $listQuantityType = $quantityTypeRepository->findAll();
        $jsonQuantityType = $serializer->serialize($listQuantityType, 'json', ["groups" => 'quantityType']);
        return new JsonResponse($jsonQuantityType, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_quantity_type_show', methods: ['GET'])]
    public function show(QuantityType $quantityType, SerializerInterface $serializer): Response
    {
        $jsonQuantityType = $serializer->serialize($quantityType, 'json', ["groups" => 'quantityType']);
        return new JsonResponse($jsonQuantityType, Response::HTTP_OK, [], true);
    }

    // #[Route('/new', name: 'api_quantity_type_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {

    // }

    // #[Route('/{id}/edit', name: 'api_quantity_type_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, QuantityType $quantityType, EntityManagerInterface $entityManager): Response
    // {

    // }

    // #[Route('/{id}', name: 'api_quantity_type_delete', methods: ['POST'])]
    // public function delete(Request $request, QuantityType $quantityType, EntityManagerInterface $entityManager): Response
    // {

    // }
}
