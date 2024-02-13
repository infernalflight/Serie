<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    #[Route('/season/create', name: 'app_season_create')]
    public function create(): Response
    {

        $season = new Season();

        $form = $this->createForm(SeasonType::class, $season);

        return $this->render('season/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
