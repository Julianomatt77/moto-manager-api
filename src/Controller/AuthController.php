<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
//	private $jwtManager;
//	private $passwordEncoder;
//
//	public function __construct(JWTTokenManagerInterface $jwtManager, UserPasswordEncoderInterface $passwordEncoder)
//	{
//		$this->jwtManager = $jwtManager;
//		$this->passwordEncoder = $passwordEncoder;
//	}

//	#[Route('/api/login_check', name: 'api_login', methods: ['POST'])]
//	public function apiLogin(Request $request)
//	{
//		// Récupérer les identifiants de l'utilisateur à partir de la requête
//		$data = json_decode($request->getContent(), true);
//		$username = $data['username'];
//		$password = $data['password'];
//
//		// Recherchez l'utilisateur dans votre système (par exemple, en utilisant Doctrine)
//		$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
//
//		if (!$user) {
//			return new JsonResponse(['message' => 'Utilisateur non trouvé'], 404);
//		}
//
//		// Vérifiez le mot de passe
//		if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
//			return new JsonResponse(['message' => 'Mot de passe incorrect'], 401);
//		}
//
//		// Générer un token JWT pour l'utilisateur authentifié
//		$token = $this->jwtManager->create($user);
//
//		return new JsonResponse(['token' => $token]);
//	}
}
