<?php

namespace App\Controller;

use App\Entity\Chose;
use App\Repository\CategoryRepository;
use App\Repository\ChoseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class AutreController extends AbstractController
{
    #[Route('/autres', name: 'app_autres')]
    public function index(ChoseRepository $choseRepository): Response
    {
        $choses = $choseRepository->findAll();
        return $this->json($choses, 200, [], ['groups' => ['read-choses']]);
    }

    #[Route('/autre/{id}', name: 'app_autre')]
    public function show(Chose $chose): Response
    {
        return $this->json($chose, 200, [], ['groups' => ['read-chose']]);
    }

    #[Route('/autre/new/{idCategory}', name: 'app_autre_new', methods: ['POST'])]
    public function new(
        CategoryRepository $categoryRepository,
        $idCategory,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Request $request,
    ): Response
    {
        $category = $categoryRepository->find($idCategory);

        if (!$category) {
            return $this->json("existe pas", 403);
        }

        $chose = $serializer->deserialize($request->getContent(), Chose::class, 'json');

        $chose->setCategory($category);
        $entityManager->persist($chose);
        $entityManager->flush();

        return $this->json($chose, 200, [], ['groups' => ['read-chose']]);
    }

}
