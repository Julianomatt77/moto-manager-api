<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
//use App\Entity\user;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\AnnuaireService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
	private $em;
	private $userRepository;
	private $jwtManager;
	private $tokenStorageInterface;
	private $annuaire;
	
	
	public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $manager, UserRepository $userRepository, AnnuaireService $annuaire)
	{
		$this->em   = $manager;
		$this->userRepository = $userRepository;
		$this->jwtManager = $jwtManager;
		$this->tokenStorageInterface = $tokenStorageInterface;
		$this->annuaire = $annuaire;
	}
	
	//Création d’un utilisateur
	#[Route(
		path: '/register', name: 'api_register', defaults: ['_api_resource_class' => User::class,], methods: ['POST']
	)]
	public function register(Request $request): JsonResponse
	{
		$data     = json_decode($request->getContent(), true);
		$email    = $data["email"];
		$password = $data["password"];

		//Vérification de l’email
		$checkEmail = $this->user->findOneBy(['email' => $email]);
		if ($checkEmail) {
			return new JsonResponse([
										"status"  => false,
										"message" => "Cet email existe déjà, vous pouvez choisir un autre !"
									]);
		} else {
			$user = new User();
			$user->setEmail($email)->setPassword(sha1($password))->setRoles(["ROLE_USER"]);
			
			$this->em->persist($user);
			$this->em->flush();
			
			return new JsonResponse([
										"status"  => true,
										"message" => "L’utilisateur a été créé avec succès !"
									]);
		}
	}
	
	#[Route(
		path: '/api/users/{id}', name: 'app_user_show', defaults: ['_api_resource_class' => User::class,], methods: ['GET'],
	)]
	public function show( User $user, Request $request, SerializerInterface $serializer): Response
	{
		$connectedUser = $this->annuaire->getUser($request);

		$user = $this->userRepository->findOneBy(['id'=>$user->getId()]);
		
		if ($user == $connectedUser){
			$json = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
			
			return new JsonResponse($json, 200, [], true);
		} else {
			return new JsonResponse(['error' => 'Utilisateur introuvable ou utilisateur non authorisé'], 401);
		}
		
		
	}
	
	/*
	#[Route(
		path: '/api/users/{id}', name: 'app_user_edit', defaults: ['_api_resource_class' => User::class,], methods: ['PATCH'],
	)]
	public function edit(Request $request, user $user, EntityManagerInterface $entityManager, userRepository $userRepository, SerializerInterface $serializer): Response
	{
		$connectedUser = $this->annuaire->getUser($request);
		// Si l'utilisateur force l'url avec une user ne lui appartenant pas -> $user sera null donc on ne met pas à jour
		$user = $userRepository->findOneBy(['id'=>$user->getId()]);
		
		if ($user && $user == $connectedUser){
			$content = $request->getContent();
			$user = $serializer->deserialize($content, User::class, 'json', ['groups' => 'user:write', 'object_to_populate' => $user]);
			$entityManager->persist($user);
			$entityManager->flush();
			
			return new JsonResponse($serializer->serialize($user, 'json'), 200, [], true);
		} else {
			return new JsonResponse(['error' => 'user introuvable'], 401);
		}
	}
	*/
	/*
	#[Route(
		path: '/api/users/{id}', name: 'app_user_delete', defaults: ['_api_resource_class' => User::class,], methods: ['DELETE'],
	)]
	public function delete(Request $request, user $user, EntityManagerInterface $entityManager, userRepository $userRepository, SerializerInterface $serializer): Response
	{
		$user = $this->annuaire->getUser($request);
		// Si l'utilisateur force l'url avec une user ne lui appartenant pas -> $user sera null donc on ne supprime pas
		$user = $userRepository->findOneBy(['id'=>$user->getId(), 'user'=>$user]);
		
		if ($user){
			$entityManager->remove($user);
			$entityManager->flush();
			return new JsonResponse('user supprimée', 202,);
		} else {
			return new JsonResponse(['error' => 'user introuvable'], 401);
		}
	}
	*/
	
}
