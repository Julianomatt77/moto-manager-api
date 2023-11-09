<?php

namespace App\Service;

class AnnuaireService
{
//	private $cookieName;
	
	public function __construct(
//		string $authCookieName,
	) {
//		$this->cookieName = $authCookieName;
//		$this->cookieName = 'auth_cookie';
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