<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Nelmio\CorsBundle\NelmioCorsBundle;
use ApiPlatform\Symfony\Bundle\ApiPlatformBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;

return [
    FrameworkBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    MakerBundle::class => ['dev' => true],
    SensioFrameworkExtraBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    NelmioCorsBundle::class => ['all' => true],
    ApiPlatformBundle::class => ['all' => true],
    DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    LexikJWTAuthenticationBundle::class => ['all' => true],
    StofDoctrineExtensionsBundle::class => ['all' => true],
];
