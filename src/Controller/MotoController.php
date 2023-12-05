<?php

namespace App\Controller;

use App\Entity\Moto;
use App\Repository\MotoRepository;
use App\Repository\UserRepository;
use App\Service\AnnuaireService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class MotoController extends AbstractController
{
	public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository, AnnuaireService $annuaire)
	{
		$this->jwtManager = $jwtManager;
		$this->tokenStorageInterface = $tokenStorageInterface;
		$this->userRepository = $userRepository;
		$this->annuaire = $annuaire;
	}

	#[Route(
		path: '/api/motos', name: 'app_moto_all', defaults: ['_api_resource_class' => Moto::class,], methods: ['GET'],
	)]
    public function index(MotoRepository $motoRepository, Request $request, SerializerInterface $serializer): Response
    {
		$user = $this->annuaire->getUser($request);
		$motos = $motoRepository->findByUser($user);
		
		// Utilisez le composant Serializer pour personnaliser la sortie
		$json = $serializer->serialize($motos, 'json', ['groups' => 'moto:read']);
		
		return new JsonResponse($json, 200, [], true);
    }

	#[Route(
		path: '/api/motos', name: 'app_moto_new', defaults: ['_api_resource_class' => Moto::class,], methods: ['POST'],
	)]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
		$user = $this->annuaire->getUser($request);
		$content = $request->getContent();
		
		// Transforme le contenu JSON en un objet Moto
		$moto = $serializer->deserialize($content, Moto::class, 'json', ['groups' => 'moto:write']);
		$moto->setUser($user);
		
		// Enregistre l'objet Moto en base de données
		$entityManager->persist($moto);
		$entityManager->flush();
		
		return new JsonResponse($serializer->serialize($moto, 'json'), 201, [], true);
    }

	#[Route(
		path: '/api/motos/{id}', name: 'app_moto_show', defaults: ['_api_resource_class' => Moto::class,], methods: ['GET'],
	)]
    public function show(MotoRepository $motoRepository, Moto $moto, Request $request, SerializerInterface $serializer): Response
    {
		$user = $this->annuaire->getUser($request);
		$moto = $motoRepository->findOneBy(['id'=>$moto->getId(), 'user'=>$user]);
		
		$json = $serializer->serialize($moto, 'json', ['groups' => 'moto:read']);
		
		return new JsonResponse($json, 200, [], true);
    }

	#[Route(
		path: '/api/motos/{id}', name: 'app_moto_edit', defaults: ['_api_resource_class' => Moto::class,], methods: ['PATCH'],
	)]
    public function edit(Request $request, Moto $moto, EntityManagerInterface $entityManager, MotoRepository $motoRepository, SerializerInterface $serializer): Response
    {
		$user = $this->annuaire->getUser($request);
		// Si l'utilisateur force l'url avec une moto ne lui appartenant pas -> $moto sera null donc on ne met pas à jour
		$moto = $motoRepository->findOneBy(['id'=>$moto->getId(), 'user'=>$user]);
		
		if ($moto){
			$content = $request->getContent();
			$moto = $serializer->deserialize($content, Moto::class, 'json', ['groups' => 'moto:write', 'object_to_populate' => $moto]);
			$entityManager->persist($moto);
			$entityManager->flush();
			
			return new JsonResponse($serializer->serialize($moto, 'json'), 200, [], true);
		} else {
			return new JsonResponse(['error' => 'La moto introuvable ou ne vous appartient pas'], 401);
		}
    }
	
	#[Route(
		path: '/api/motos/{id}', name: 'app_moto_delete', defaults: ['_api_resource_class' => Moto::class,], methods: ['DELETE'],
	)]
    public function delete(Request $request, Moto $moto, EntityManagerInterface $entityManager, MotoRepository $motoRepository, SerializerInterface $serializer): Response
    {
		$user = $this->annuaire->getUser($request);
		// Si l'utilisateur force l'url avec une moto ne lui appartenant pas -> $moto sera null donc on ne supprime pas
		$moto = $motoRepository->findOneBy(['id'=>$moto->getId(), 'user'=>$user]);
		
		if ($moto){
			$entityManager->remove($moto);
			$entityManager->flush();
			return new JsonResponse('moto supprimée', 202,);
		} else {
			return new JsonResponse(['error' => 'La moto introuvable ou ne vous appartient pas'], 401);
		}
    }
}
