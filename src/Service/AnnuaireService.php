<?php

namespace App\Service;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AnnuaireService
{
//	private $cookieName;
	
	public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository
//		string $authCookieName,
	) {
		$this->jwtManager = $jwtManager;
		$this->tokenStorageInterface = $tokenStorageInterface;
		$this->userRepository = $userRepository;
//		$this->cookieName = $authCookieName;
//		$this->cookieName = 'auth_cookie';
	}
	
	public function getUser(Request $request){
		$token = $request->headers->get('Authorization');
		// Décoder le token pour récupérer le user
		// Marche avec bearer Token
//		$userInfos = json_encode($decodedJwtToken);
		$decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
//		$decodedJwtToken['token'] = $token;
		$username = $decodedJwtToken["username"];
		
		return $this->userRepository->findOneBy(['email' => $username]);
	}
	
	/**
	 * Decodes a formerly validated JWT token and returns the data it contains
	 * (payload / claims)
	 */
	public function decodeToken($token) {
		$parts = explode('.', $token);
		$payload = $parts[1];
		$payload = $this->urlsafeB64Decode($payload);
		$payload = json_decode($payload, true);
		
		return $payload;
	}
	
	/**
	 * Method compatible with "urlsafe" base64 encoding used by JWT lib
	 */
	public function urlsafeB64Decode($input) {
		$remainder = strlen($input) % 4;
		if ($remainder) {
			$padlen = 4 - $remainder;
			$input .= str_repeat('=', $padlen);
		}
		return base64_decode(strtr($input, '-_', '+/'));
	}
	
	public function getCookieName(): string
	{
		return $this->cookieName;
	}
}