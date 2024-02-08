<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/series', name: 'app_series')]
class SerieController extends AbstractController
{
    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(Serie $serie): Response
    {
        return $this->render('serie/detail.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(Serie $serie, EntityManagerInterface $em): Response
    {
        $serie->setName('Friends');

        $em->persist($serie);
        $em->flush();

        return new Response ('C\'est updated !');
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete(serie $serie, EntityManagerInterface $em): Response
    {

        $em->remove($serie);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }





















}
