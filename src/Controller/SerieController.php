<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/series', name: 'app_series')]
#[IsGranted('ROLE_USER')]
class SerieController extends AbstractController
{
    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(Serie $serie): Response
    {
        return $this->render('serie/detail.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: '_create')]
    #[IsGranted('ROLE_CONTRIB')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, HttpClientInterface $httpClient): Response
    {
        $serie = new Serie();

        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('poster_file')->getData() instanceof UploadedFile) {
                $posterFile = $form->get('poster_file')->getData();
                $fileName = $slugger->slug($serie->getName()) . '-' . uniqid() . '.' . $posterFile->guessExtension();
                $posterFile->move($this->getParameter('poster_dir'), $fileName);
                $serie->setPoster($fileName);
            }

            $em->persist($serie);
            $em->flush();

            $this->addFlash('success', 'La série a été enregistrée');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('serie/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/create_ajax', name: '_create_ajax')]
    #[IsGranted('ROLE_CONTRIB')]
    public function createAjax(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $serie = new Serie();

        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('poster_file')->getData() instanceof UploadedFile) {
                $posterFile = $form->get('poster_file')->getData();
                $fileName = $slugger->slug($serie->getName()) . '-' . uniqid() . '.' . $posterFile->guessExtension();
                $posterFile->move($this->getParameter('poster_dir'), $fileName);
                $serie->setPoster($fileName);
            }

            $em->persist($serie);
            $em->flush();

            $this->addFlash('success', 'La série a été enregistrée');

            return new Response(json_encode([
                'id' => $serie->getId(),
                'name' => $serie->getName(),
            ]), Response::HTTP_OK);
        }

        return $this->render('serie/edit_ajax.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_CONTRIB')]
    public function update(Serie $serie, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SerieType::class, $serie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('poster_file')->getData() instanceof UploadedFile) {
                $dir = $this->getParameter('poster_dir');
                $posterFile = $form->get('poster_file')->getData();
                $fileName = $slugger->slug($serie->getName()) . '-' . uniqid() . '.' . $posterFile->guessExtension();
                $posterFile->move($dir, $fileName);

                if ($serie->getPoster() && \file_exists($dir . '/' . $serie->getPoster())) {
                    unlink($dir . '/' . $serie->getPoster());
                }

                $serie->setPoster($fileName);

            }

            $em->persist($serie);
            $em->flush();

            $this->addFlash('success', 'La série a été modifiée');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('serie/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_CONTRIB')]
    public function delete(serie $serie, EntityManagerInterface $em): Response
    {

        if (!empty($serie->getSeasons())) {
            foreach($serie->getSeasons() as $season) {
                $season->setSerie(null);
                $em->persist($season);
            }
        }

        $em->remove($serie);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
