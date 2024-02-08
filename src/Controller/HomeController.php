<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();

        return $this->render('serie/list.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/demo', name: 'app_demo', methods: ['GET'])]
    public function demo(EntityManagerInterface $em): Response
    {
        $serie = new Serie();
        $serie->setName('Friends');
        $serie->setOverview('Une bande de copains vivent à New York. Que d\'aventures ...');
        $serie->setFirstAirDate(new \DateTime('1990-07-21'));
        $serie->setLastAirDate(new \DateTime('2004-05-06'));
        $serie->setDateCreated(new \DateTime());
        $serie->setVote(7.5);

        $em->persist($serie);
        $em->flush();

        return new Response('Serie crée !');

    }

}
