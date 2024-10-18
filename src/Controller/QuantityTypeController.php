<?php

namespace App\Controller;

use App\Entity\QuantityType;
use App\Form\QuantityTypeType;
use App\Repository\QuantityTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quantity/type')]
final class QuantityTypeController extends AbstractController
{
    #[Route(name: 'app_quantity_type_index', methods: ['GET'])]
    public function index(QuantityTypeRepository $quantityTypeRepository): Response
    {
        return $this->render('quantity_type/index.html.twig', [
            'quantity_types' => $quantityTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quantity_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantityType = new QuantityType();
        $form = $this->createForm(QuantityTypeType::class, $quantityType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quantityType);
            $entityManager->flush();

            return $this->redirectToRoute('app_quantity_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quantity_type/new.html.twig', [
            'quantity_type' => $quantityType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quantity_type_show', methods: ['GET'])]
    public function show(QuantityType $quantityType): Response
    {
        return $this->render('quantity_type/show.html.twig', [
            'quantity_type' => $quantityType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quantity_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuantityType $quantityType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuantityTypeType::class, $quantityType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quantity_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quantity_type/edit.html.twig', [
            'quantity_type' => $quantityType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quantity_type_delete', methods: ['POST'])]
    public function delete(Request $request, QuantityType $quantityType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quantityType->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($quantityType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quantity_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
