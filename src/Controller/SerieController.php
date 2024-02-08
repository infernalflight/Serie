<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'app_series')]
class SerieController extends AbstractController
{
    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        return $this->render('serie/detail.html.twig', [
            'serie' => $serie
        ]);
    }






















}
