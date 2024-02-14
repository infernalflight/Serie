<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    #[Route('/season/create', name: 'app_season_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $season = new Season();

        $form = $this->createForm(SeasonType::class, $season);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash('success', 'La saison est dans la boite');
            return $this->redirectToRoute('app_series_detail', ['id' => $season->getSerie()->getId()]);
        }


        return $this->render('season/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
