<?php

namespace App\Controller;

use App\Entity\DepenseType;
use App\Repository\DepenseRepository;
use App\Repository\DepenseTypeRepository;
use App\Repository\UserRepository;
use App\Service\AnnuaireService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DepenseTypeController extends AbstractController
{
    private JWTTokenManagerInterface $jwtManager;
    private TokenStorageInterface $tokenStorageInterface;
    private UserRepository $userRepository;
    private AnnuaireService $annuaire;
    private DepenseTypeRepository $depenseTypeRepository;

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository, AnnuaireService $annuaire, DepenseTypeRepository $depenseTypeRepository)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->userRepository = $userRepository;
        $this->annuaire = $annuaire;
        $this->depenseTypeRepository = $depenseTypeRepository;
    }

    #[Route(
        path: '/api/depensesTypes', name: 'app_depensesTypes_all', defaults: ['_api_resource_class' => DepenseType::class,], methods: ['GET'],
    )]
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $depensesTypes = $this->depenseTypeRepository->findByUser($user);

        $json = $serializer->serialize($depensesTypes, 'json', ['groups' => 'depensesTypes:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(
        path: '/api/depensesTypes', name: 'app_depensesTypes_new', defaults: ['_api_resource_class' => DepenseType::class,], methods: ['POST'],
    )]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $content = $request->getContent();

        $existingType = $this->depenseTypeRepository->findBy(['user' => $user, 'name'=>json_decode($content, true)['name']]);
        if ($existingType){
            return new JsonResponse(['error' => 'Ce type de dépense existe déjà'], 409);
        }

        $depenseType = $serializer->deserialize($content, DepenseType::class, 'json', ['groups' => 'depensesTypes:write']);
        $depenseType->setUser($user);

        $entityManager->persist($depenseType);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($depenseType, 'json'), 201, [], true);

    }

    #[Route(
        path: '/api/depensesTypes/{id}', name: 'app_depensesTypes_show', defaults: ['_api_resource_class' => DepenseType::class,], methods: ['GET'],
    )]
    public function show(DepenseType $depenseType, Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $depenseType = $this->depenseTypeRepository->findOneBy(['id'=>$depenseType->getId(), 'user'=>$user]);

        $json = $serializer->serialize($depenseType, 'json', ['groups' => 'depensesTypes:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(
        path: '/api/depensesTypes/{id}', name: 'app_depensesTypes_edit', defaults: ['_api_resource_class' => DepenseType::class,], methods: ['PATCH'],
    )]
    public function edit(Request $request, DepenseType $depenseType, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $content = $request->getContent();

        $depenseType = $this->depenseTypeRepository->findOneBy(['id'=>$depenseType->getId(), 'user'=>$user]);
        if (!$depenseType){
            return new JsonResponse(['error' => 'Type de dépense introuvable'], 401);
        }

        $existingType = $this->depenseTypeRepository->findBy(['user' => $user, 'name'=>json_decode($content, true)['name']]);
        if ($existingType){
            return new JsonResponse(['error' => 'Ce type de dépense existe déjà'], 409);
        }

        $depenseType = $serializer->deserialize($content, DepenseType::class, 'json', ['groups' => 'depensesTypes:write', 'object_to_populate' => $depenseType]);

        $entityManager->persist($depenseType);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($depenseType, 'json'), 200, [], true);

    }

    #[Route(
        path: '/api/depensesTypes/{id}', name: 'app_depensesTypes_delete', defaults: ['_api_resource_class' => DepenseType::class,], methods: ['DELETE'],
    )]
    public function delete(Request $request, DepenseType $depenseType, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $depenseType = $this->depenseTypeRepository->findOneBy(['id'=>$depenseType->getId(), 'user'=>$user]);

        if ($depenseType){
            $entityManager->remove($depenseType);
            $entityManager->flush();
            return new JsonResponse('Type de dépense supprimée', 202,);
        } else {
            return new JsonResponse(['error' => 'Dépense introuvable'], 401);
        }
    }
}
