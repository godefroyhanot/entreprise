<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/account')]
final class AccountApiController extends AbstractController
{

    #[Route(name: 'api_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository, SerializerInterface $serializer): Response
    {
        $listAccounts = $accountRepository->findAll();
        $jsonAccounts = $serializer->serialize($listAccounts, 'json', []);
        return new JsonResponse($jsonAccounts, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_account_show', methods: ['GET'])]
    public function show(Account $account, SerializerInterface $serializer): Response
    {

        $jsonAccount = $serializer->serialize($account, 'json', []);

        return new JsonResponse($jsonAccount, Response::HTTP_OK, [], true);
    }

    // #[Route('/new', name: 'api_account_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response {}


    // #[Route('/{id}/edit', name: 'api_account_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Account $account, EntityManagerInterface $entityManager): Response {}

    // #[Route('/{id}', name: 'api_account_delete', methods: ['POST'])]
    // public function delete(Request $request, Account $account, EntityManagerInterface $entityManager): Response {}
}
