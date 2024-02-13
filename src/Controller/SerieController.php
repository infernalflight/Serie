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
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
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



    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
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
    public function delete(serie $serie, EntityManagerInterface $em): Response
    {

        $em->remove($serie);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }





















}
