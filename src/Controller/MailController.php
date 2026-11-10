<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailController extends AbstractController
{
    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    private function sendEmail($from, $subject, $message): Response
    {
        $email = new Email()
            ->from('contact@martin-julien-dev.fr')
//            ->from($from)
            ->to('contact@martin-julien-dev.fr')
            ->subject($subject)
            ->html($message);

        $this->mailer->send($email);

        return new JsonResponse(['ok' => 'mail transmis'], Response::HTTP_OK, []);
    }

    #[Route('/contact', name: 'send_mail')]
    public function contactMe(MailerInterface $mailer, Request $request): Response
    {
        $content = json_decode($request->getContent(), true);

        $from = $content['from'];
        $subject = $content['subject'];
        $message = '<h1>Envoyé depuis: '. $from.'</h1><p> '. $content['message'] .'</p>';

        try {
            $this->sendEmail($from, $subject, $message);
            return new JsonResponse(['ok' => 'mail envoyé'], Response::HTTP_OK, []);
        } catch (Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
