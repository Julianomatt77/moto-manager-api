<?php

namespace App\Controller;

use App\Entity\Moto;
use App\Form\MotoType;
use App\Repository\MotoRepository;
use App\Service\AnnuaireService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Cookie;


#[Route('/api/moto')]
class MotoController extends AbstractController
{
	
	public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
	{
		$this->jwtManager = $jwtManager;
		$this->tokenStorageInterface = $tokenStorageInterface;
	}
	
	
    #[Route('/', name: 'app_moto_index', methods: ['GET'])]
    public function index(MotoRepository $motoRepository, AnnuaireService $annuaire, Request $request): Response
    {
		$token = $request->headers->get('Authorization');

		// $decodedJwtToken["username"]
		// Marche avec bearer Token
		$decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
		$decodedJwtToken['token'] = $token;
		
		$json = json_encode($decodedJwtToken);
		
		return new JsonResponse($json, 200, [], true);
//        return $this->render('moto/index.html.twig', [
//            'motos' => $motoRepository->findAll(),
//        ]);
    }

    #[Route('/new', name: 'app_moto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $moto = new Moto();
        $form = $this->createForm(MotoType::class, $moto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($moto);
            $entityManager->flush();

            return $this->redirectToRoute('app_moto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('moto/new.html.twig', [
            'moto' => $moto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moto_show', methods: ['GET'])]
    public function show(Moto $moto): Response
    {
        return $this->render('moto/show.html.twig', [
            'moto' => $moto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_moto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Moto $moto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MotoType::class, $moto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_moto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('moto/edit.html.twig', [
            'moto' => $moto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moto_delete', methods: ['POST'])]
    public function delete(Request $request, Moto $moto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($moto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_moto_index', [], Response::HTTP_SEE_OTHER);
    }
}
