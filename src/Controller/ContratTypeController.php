<?php

namespace App\Controller;

use App\Entity\ContratType;
use App\Form\ContratTypeType;
use App\Repository\ContratTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contrat/type')]
final class ContratTypeController extends AbstractController
{
    #[Route(name: 'app_contrat_type_index', methods: ['GET'])]
    public function index(ContratTypeRepository $contratTypeRepository): Response
    {
        return $this->render('contrat_type/index.html.twig', [
            'contrat_types' => $contratTypeRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_type_show', methods: ['GET'])]
    public function show(ContratType $contratType): Response
    {
        return $this->render('contrat_type/show.html.twig', [
            'contrat_type' => $contratType,
        ]);
    }

    // #[Route('/new', name: 'app_contrat_type_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $contratType = new ContratType();
    //     $form = $this->createForm(ContratTypeType::class, $contratType);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($contratType);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_contrat_type_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('contrat_type/new.html.twig', [
    //         'contrat_type' => $contratType,
    //         'form' => $form,
    //     ]);
    // }

    

    // #[Route('/{id}/edit', name: 'app_contrat_type_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, ContratType $contratType, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ContratType1Type::class, $contratType);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_contrat_type_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('contrat_type/edit.html.twig', [
    //         'contrat_type' => $contratType,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_contrat_type_delete', methods: ['POST'])]
    // public function delete(Request $request, ContratType $contratType, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$contratType->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($contratType);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_contrat_type_index', [], Response::HTTP_SEE_OTHER);
    // }
}
