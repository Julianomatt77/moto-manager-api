<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\DepenseType;
use App\Entity\Moto;
use App\Repository\DepenseRepository;
use App\Repository\UserRepository;
use App\Service\AnnuaireService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class DepenseController extends AbstractController
{
    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository, AnnuaireService $annuaire)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->userRepository = $userRepository;
        $this->annuaire = $annuaire;
    }

    #[Route(
        path: '/api/depenses', name: 'app_depenses_all', defaults: ['_api_resource_class' => Depense::class,], methods: ['GET'],
    )]
    public function index(Request $request, SerializerInterface $serializer, DepenseRepository $depenseRepository): Response
    {
        $user = $this->annuaire->getUser($request);
        $depenses = $depenseRepository->findByUser($user);

        // Utilisez le composant Serializer pour personnaliser la sortie
        $json = $serializer->serialize($depenses, 'json', ['groups' => 'depenses:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(
        path: '/api/depenses', name: 'app_depenses_new', defaults: ['_api_resource_class' => Depense::class,], methods: ['POST'],
    )]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $content = json_decode($request->getContent(), true);

        // On récupère les id moto et depenseType et on les transforme en objet
        $moto_id = $content['moto'];
        $depenseType_id = $content['depense_type'];

        //TODO Vérifier si la moto lui appartient ?
        $moto = $entityManager->getRepository(Moto::class)->findOneBy(['id'=>$moto_id, 'user'=>$user]);
        if (!$moto){
            return new JsonResponse(['error' => 'La moto introuvable ou ne vous appartient pas'], 401);
        }
        $depenseType = $entityManager->getRepository(DepenseType::class)->find($depenseType_id);

        // On supprime moto et depense-type sinon pbl de deserialization
        unset($content['moto']);
        unset($content['depense_type']);

        // Transforme le contenu JSON en un objet Dépense
        $depense = $serializer->deserialize(json_encode($content), Depense::class, 'json', ['groups' => 'depenses:write']);

        if ($depense->getEssenceConsomme() && $depense->getKmParcouru()){
            $conso = ($depense->getEssenceConsomme() * 100) / $depense->getKmParcouru();
            $depense->setConsoMoyenne(round($conso, 2));
        }

        $depense->setUser($user);
        $depense->setMoto($moto);
        $depense->setDepenseType($depenseType);
        // Enregistre en base de données
        $entityManager->persist($depense);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($depense, 'json'), 201, [], true);
    }

    #[Route(
        path: '/api/depenses/{id}', name: 'app_depenses_show', defaults: ['_api_resource_class' => Depense::class,], methods: ['GET'],
    )]
    public function show(DepenseRepository $depenseRepository, Depense $depense, Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $moto = $depenseRepository->findOneBy(['id'=>$depense->getId(), 'user'=>$user]);

        $json = $serializer->serialize($depense, 'json', ['groups' => 'depenses:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(
        path: '/api/depenses/{id}', name: 'app_depenses_edit', defaults: ['_api_resource_class' => Depense::class,], methods: ['PATCH'],
    )]
    public function edit(Request $request, Depense $depense, EntityManagerInterface $entityManager, DepenseRepository $depenseRepository, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $moto = null;
        $depenseType = null;
        $depense = $depenseRepository->findOneBy(['id'=>$depense->getId(), 'user'=>$user]);

        if ($depense){
            $content = json_decode($request->getContent(), true);

            // On récupère les id moto et depenseType et on les transforme en objet
            if (isset($cntent['moto'])){
                $moto_id = $content['moto'];
                $moto = $entityManager->getRepository(Moto::class)->findOneBy(['id'=>$moto_id, 'user'=>$user]);
                if (!$moto){
                    return new JsonResponse(['error' => 'La moto introuvable ou ne vous appartient pas'], 401);
                }
                unset($content['moto']);
            }

            if (isset($content['depense_type'])){
                $depenseType_id = $content['depense_type'];
                $depenseType = $entityManager->getRepository(DepenseType::class)->find($depenseType_id);
                unset($content['depense_type']);
            }

            $depense = $serializer->deserialize(json_encode($content), Depense::class, 'json', ['groups' => 'depenses:write', 'object_to_populate' => $depense]);

            if ($depense->getEssenceConsomme() && $depense->getKmParcouru()){
                $conso = ($depense->getEssenceConsomme() * 100) / $depense->getKmParcouru();
                $depense->setConsoMoyenne(round($conso, 2));
            }

            if ($moto){
                $depense->setMoto($moto);
            }

            if ($depenseType){
                $depense->setDepenseType($depenseType);
            }

            $entityManager->persist($depense);
            $entityManager->flush();

            return new JsonResponse($serializer->serialize($depense, 'json'), 200, [], true);
        } else {
            return new JsonResponse(['error' => 'Dépense introuvable'], 401);
        }
    }

    #[Route(
        path: '/api/depenses/{id}', name: 'app_depenses_delete', defaults: ['_api_resource_class' => Depense::class,], methods: ['DELETE'],
    )]
    public function delete(Request $request, Depense $depense, EntityManagerInterface $entityManager, DepenseRepository $depenseRepository, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $depense = $depenseRepository->findOneBy(['id'=>$depense->getId(), 'user'=>$user]);

        if ($depense){
            $entityManager->remove($depense);
            $entityManager->flush();
            return new JsonResponse('dépense supprimée', 202,);
        } else {
            return new JsonResponse(['error' => 'Dépense introuvable'], 401);
        }
    }
}
