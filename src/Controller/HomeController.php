<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/{page}', name: 'app_home', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    #[IsGranted('ROLE_USER')]
    public function index(?int $page, SerieRepository $serieRepository): Response
    {
        //$series = $serieRepository->findAll();

        // Récupérer un tableau de Séries qui répond à plusieurs critères
        //$series = $serieRepository->findBy(['status' => 'ended', 'genres' => 'SF'], ['firstAirDate' => 'DESC'], 3, 3);

        // Récupérer un tableau de séries avec le QueryBuilder
        //$series = $serieRepository->findSerieBySophisticatedCriterias('canceled', 'ended');
        $series = $serieRepository->getAllSeriesWithSeasons($page);

        // Récupérer un tableau avec DQL
        //$series = $serieRepository->getSeriesByDql();

        // Récupérer un tableau avec SQL
        //$series = $serieRepository->getSeriesBySql();

        $maxPage = ceil($serieRepository->count([]) / 15 ) ;


        $i = 5;


        $i += 8;


        return $this->render('serie/list.html.twig', [
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage,
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
        $serie->setVote(7.5);

        $em->persist($serie);
        $em->flush();

        return new Response('Serie crée !');
    }

    public function nouvelleMethode(): String {

        return 'ceci est ma feature';

    }

    #[Route('/maps', name: 'app_maps')]
    public function testMap(): Response
    {
        return $this->render('map/map.html.twig');
    }
}
