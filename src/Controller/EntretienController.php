<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\Entretien;
use App\Entity\Moto;
use App\Repository\DepenseRepository;
use App\Repository\EntretienRepository;
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

class EntretienController extends AbstractController
{
    private JWTTokenManagerInterface $jwtManager;
    private TokenStorageInterface $tokenStorageInterface;
    private UserRepository $userRepository;
    private AnnuaireService $annuaire;
    private EntretienRepository $entretienRepository;

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository, AnnuaireService $annuaire, EntretienRepository $entretienRepository)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->userRepository = $userRepository;
        $this->annuaire = $annuaire;
        $this->entretienRepository = $entretienRepository;
    }

    #[Route(
        path: '/api/entretiens', name: 'app_entretiens_all', defaults: ['_api_resource_class' => Entretien::class,], methods: ['GET'],
    )]
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $entretiens = $this->entretienRepository->findByUser($user);

        // Utilisez le composant Serializer pour personnaliser la sortie
        $json = $serializer->serialize($entretiens, 'json', ['groups' => 'entretiens:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(
        path: '/api/entretiens', name: 'app_entretiens_new', defaults: ['_api_resource_class' => Entretien::class,], methods: ['POST'],
    )]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $content = json_decode($request->getContent(), true);

        // On récupère les id moto et depenseType et on les transforme en objet
        $moto_id = $content['moto'];
        unset($content['moto']);

        $moto = $entityManager->getRepository(Moto::class)->findOneBy(['id' => $moto_id, 'user' => $user]);
        if (!$moto) {
            return new JsonResponse(['error' => 'La moto introuvable ou ne vous appartient pas'], 401);
        }

        // Transforme le contenu JSON en un objet Dépense
        $entretien = $serializer->deserialize(json_encode($content), Entretien::class, 'json', ['groups' => 'entretiens:write']);

        $entretien->setUser($user);
        $entretien->setMoto($moto);

        $entityManager->persist($entretien);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($entretien, 'json'), 201, [], true);
    }

    #[Route(
        path: '/api/entretiens/{id}', name: 'app_entretiens_show', defaults: ['_api_resource_class' => Entretien::class,], methods: ['GET'],
    )]
    public function show(Entretien $entretien, Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $entretien = $this->entretienRepository->findOneBy(['id' => $entretien->getId(), 'user' => $user]);

        if (!$entretien) {
            return new JsonResponse(['error' => "L'entretien recherché n'existe pas ou ne vous appartient pas"], 401);
        }
        $json = $serializer->serialize($entretien, 'json', ['groups' => 'entretiens:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(
        path: '/api/entretiens/{id}', name: 'app_entretiens_edit', defaults: ['_api_resource_class' => Entretien::class,], methods: ['PATCH'],
    )]
    public function edit(Request $request, Entretien $entretien, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $this->annuaire->getUser($request);
        $moto = null;
        $entretien = $this->entretienRepository->findOneBy(['id' => $entretien->getId(), 'user' => $user]);

        if (!$entretien) {
            return new JsonResponse(['error' => 'Entretien introuvable ou ne vous appartenant pas'], 401);
        }

        $content = json_decode($request->getContent(), true);

        if (isset($content['moto'])) {
            $moto_id = $content['moto'];
            $moto = $entityManager->getRepository(Moto::class)->findOneBy(['id' => $moto_id, 'user' => $user]);
            if (!$moto) {
                return new JsonResponse(['error' => 'La moto introuvable ou ne vous appartient pas'], 401);
            }
            unset($content['moto']);
        }

        $entretien = $serializer->deserialize(json_encode($content), Entretien::class, 'json', ['groups' => 'entretiens:write', 'object_to_populate' => $entretien]);

        $entretien->setMoto($moto ?? $entretien->getMoto());

        $entityManager->persist($entretien);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($entretien, 'json'), 200, [], true);
    }

    #[Route(
        path: '/api/entretiens/{id}', name: 'app_entretiens_delete', defaults: ['_api_resource_class' => Entretien::class,], methods: ['DELETE'],
    )]
    public function delete(Request $request, Entretien $entretien, EntityManagerInterface $entityManager): Response
    {
        $user = $this->annuaire->getUser($request);
        $entretien = $this->entretienRepository->findOneBy(['id'=>$entretien->getId(), 'user'=>$user]);

        if ($entretien){
            $entityManager->remove($entretien);
            $entityManager->flush();
            return new JsonResponse('Entretien supprimée', 202,);
        } else {
            return new JsonResponse(['error' => 'Entretien introuvable ou ne vous appartenant pas'], 401);
        }
    }
}
