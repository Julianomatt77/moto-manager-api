<?php

namespace App\Service;

use App\Entity\Depense;
use Symfony\Component\HttpFoundation\Response;

class CsvService
{
    public function createCsv(array $list, string $fileName): Response
    {
        $fp = fopen('php://temp', 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $fileName . '.csv');

        return $response;
    }

    public function exportDepenses(array $depenses)
    {
        $list = [[
            'date',
            'depenseType',
            'montant',
            'kmParcouru',
            'essenceConsomme',
            'kilometrage',
            'commentaire',
            'essencePrice',
            'moto'
        ]];

        foreach ($depenses as $depense){
            $date = $depense->getDate()->format('Y/m/d');
            $list[]=[
                $date,
                $depense->getDepenseType()->getName(),
                $depense->getMontant(),
                $depense->getKmParcouru(),
                $depense->getEssenceConsomme(),
                $depense->getKilometrage(),
                $depense->getCommentaire(),
                $depense->getEssencePrice(),
                $depense->getMoto()->getModele()
            ];
        }

        return $this->createCsv($list, 'export_depenses');
    }

    public function exportEntretiens(array $entretiens)
    {
        $list = [[
            'date',
            'graissage',
            'lavage',
            'pressionAv',
            'pressionAr',
            'kilometrage',
            'moto'
        ]];

        foreach ($entretiens as $entretien){
            $date = $entretien->getDate()->format('Y/m/d');
            $graissage = $entretien->getGraissage() ? 'oui' : null;
            $lavage = $entretien->isLavage() ? 'oui' : null;

            $list[]=[
                $date,
                $graissage,
                $lavage,
                $entretien->getPressionAv(),
                $entretien->getPressionAr(),
                $entretien->getKilometrage(),
                $entretien->getMoto()->getModele()
            ];
        }

        return $this->createCsv($list, 'export_depenses');
    }
}