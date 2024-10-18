<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/account')]
final class AccountApiController extends AbstractController
{
    #[Route(name: 'api_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository, SerializerInterface $serializer): JsonResponse
    {
        $listAccounts = $accountRepository->findAll();
        $jsonAccounts = $serializer->serialize($listAccounts, 'json', ["groups" => 'account']);
        return new JsonResponse($jsonAccounts, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_account_show', methods: ['GET'])]
    public function show(Account $account, SerializerInterface $serializer): JsonResponse
    {

        $jsonAccount = $serializer->serialize($account, 'json', ["groups" => 'account']);

        return new JsonResponse($jsonAccount, Response::HTTP_OK, [], true);
    }


    #[Route('/new', name: 'api_account_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $now = new \DateTime();

        //Objets de la requete
        // dd($request->getContent());
        // dd($request->toArray());


        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);


        /* Façon par Deserialisation */


        $newAccount = $serializer->deserialize($request->getContent(), Account::class, 'json', []);
        $newAccount
            ->setClient($client)
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
            ->setStatus("on")
        ;



        /* Façon par Ajout manuel */

        // $newAccount = new Account();
        // $newAccount
        //     ->setName($data["name"])
        //     ->setUrl($data["url"])
        //     ->setPassword($data["password"])
        //     ->setCreatedAt($now)
        //     ->setUpdatedAt($now)
        //     ->setStatus("on")
        //     ->setClient($client)
        // ;


        $entityManager->persist($newAccount);
        $entityManager->flush();
        $jsonAccount = $serializer->serialize($newAccount, 'json', ['groups' => ['account']]);

        return new JsonResponse($jsonAccount, Response::HTTP_CREATED, [], true);
    }


    #[Route('/{id}/edit', name: 'api_account_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Account $account, EntityManagerInterface $entityManager, ClientRepository $clientRepository, SerializerInterface $serializer): Response
    {
        $now = new \DateTime();


        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $createdAt = $account->getCreatedAt();
        /* Façon par Deserialisation */
        $updatedAccount = $serializer->deserialize($request->getContent(), Account::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $account]);
        $updatedAccount
            ->setClient($client)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($now)
            ->setStatus("on")
        ;


        /* Façon par Ajout manuel */

        // $account
        //     ->setName($data["name"])
        //     ->setUrl($data["url"])
        //     ->setPassword($data["password"])
        //     ->setUpdatedAt($now)
        //     ->setStatus("on")
        //     ->setClient($client)
        // ;

        $entityManager->persist($updatedAccount);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'api_account_delete', methods: ['DELETE'])]
    public function delete(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {


        $data = $request->toArray();
        if (isset($data['force']) && $data["force"] === true) {

            $entityManager->remove($account);
        } else {
            $account->setStatus('off');
            $account->setUpdatedAt(new \DateTime());
            $entityManager->persist($account);
        }

        $entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
