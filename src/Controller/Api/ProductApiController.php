<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/product')]
final class ProductApiController extends AbstractController
{
    #[Route(name: 'api_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $listProducts = $productRepository->findAll();
        $jsonProducts = $serializer->serialize($listProducts, 'json', ["groups" => 'product']);
        return new JsonResponse($jsonProducts, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_product_show', methods: ['GET'])]
    public function show(Product $product, SerializerInterface $serializer): JsonResponse
    {

        $jsonProduct = $serializer->serialize($product, 'json', ["groups" => 'product']);

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }


    #[Route('/new', name: 'api_product_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();

        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);

        /* Façon par Deserialisation */
        $newProduct = $serializer->deserialize($request->getContent(), Product::class, 'json', []);
        $newProduct
            ->setClient($client)
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
            ->setStatus("on");

        $entityManager->persist($newProduct);
        $entityManager->flush();
        $jsonProduct = $serializer->serialize($newProduct, 'json', ['groups' => ['product']]);

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, [], true);
    }


    #[Route('/{id}/edit', name: 'api_product_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, ClientRepository $clientRepository, SerializerInterface $serializer): Response
    {
        $now = new \DateTime();

        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $createdAt = $product->getCreatedAt();

        $updatedProduct = $serializer->deserialize($request->getContent(), Product::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $product]);
        $updatedProduct
            ->setClient($client)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($now)
            ->setStatus("on");

        $entityManager->persist($updatedProduct);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'api_product_delete', methods: ['DELETE'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data["force"] === true) {
            $entityManager->remove($product);
        } else {
            $product->setStatus('off');
            $product->setUpdatedAt(new \DateTime());
            $entityManager->persist($product);
        }

        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
