<?php

namespace App\DataFixtures;

use App\Entity\DepenseType;
use App\Entity\Moto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	private $userPasswordHasher;
	
	public function __construct(UserPasswordHasherInterface $userPasswordHasher)
	{
		$this->userPasswordHasher = $userPasswordHasher;
	}
	
	public function load(ObjectManager $manager): void
	{
		$user = new User();
		$user->setEmail("user@moto.com");
		$user->setRoles(["ROLE_USER"]);
		$user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
		$manager->persist($user);
		
		$moto = new Moto();
		$moto->setMarque('Honda');
		$moto->setModele('Hornet');
		$manager->persist($moto);
		
		$moto2 = new Moto();
		$moto2->setMarque('Kawasaki');
		$moto2->setModele('er-6');
		$manager->persist($moto2);
		
		$depenseType = new DepenseType();
		$depenseType->setName('essence');
		$manager->persist($depenseType);
		
		$depenseType2 = new DepenseType();
		$depenseType2->setName('entretien');
		$manager->persist($depenseType2);
		
		$manager->flush();
	}
}
