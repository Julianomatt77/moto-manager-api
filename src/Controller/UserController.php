<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
	private $em;
	private $user;
	
	
	public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
	{
		$this->em   = $manager;
		$this->user = $userRepository;
	}
	
	//Création d’un utilisateur
	#[Route('api/register', name: 'api_register', methods: 'POST')]
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
	
	
}
