<?php

namespace App\Controller;

use App\Entity\Chose;
use App\Form\ChoseType;
use App\Repository\ChoseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chose')]
final class ChoseController extends AbstractController
{
    #[Route(name: 'app_chose_index', methods: ['GET'])]
    public function index(ChoseRepository $choseRepository): Response
    {
        return $this->render('chose/index.html.twig', [
            'choses' => $choseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chose_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chose = new Chose();
        $form = $this->createForm(ChoseType::class, $chose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chose);
            $entityManager->flush();

            return $this->redirectToRoute('app_chose_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chose/new.html.twig', [
            'chose' => $chose,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chose_show', methods: ['GET'])]
    public function show(Chose $chose): Response
    {
        return $this->render('chose/show.html.twig', [
            'chose' => $chose,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chose_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chose $chose, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChoseType::class, $chose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chose_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chose/edit.html.twig', [
            'chose' => $chose,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chose_delete', methods: ['POST'])]
    public function delete(Request $request, Chose $chose, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chose->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chose);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chose_index', [], Response::HTTP_SEE_OTHER);
    }
}
