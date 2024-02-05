<?php

namespace App\Controller;

use App\Repository\DepenseRepository;
use App\Repository\EntretienRepository;
use App\Repository\UserRepository;
use App\Service\AnnuaireService;
use App\Service\CsvService;
use App\Service\EntityJsonSerialize;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
class ExportController extends AbstractController
{
    public function __construct(AnnuaireService $annuaire, CsvService $csvService, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->annuaire = $annuaire;
        $this->userRepository = $userRepository;
        $this->csvService = $csvService;
    }

    #[Route('/api/exportDepenses', name: 'app_export_depenses')]
    public function exportDepenses(Request $request, DepenseRepository $depenseRepository): Response
    {
        $user = $this->annuaire->getUser($request);
        $depenses = $depenseRepository->findByUser($user);

        $response = $this->csvService->exportDepenses($depenses);

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        return $response;
    }

    #[Route('/api/exportEntretiens', name: 'app_export_entretiens')]
    public function exportEntretiens(Request $request, EntretienRepository $entretienRepository): Response
    {
        $user = $this->annuaire->getUser($request);
        $entretiens = $entretienRepository->findByUser($user);

        $response = $this->csvService->exportEntretiens($entretiens);

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        return $response;
    }
}
