<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Joke;
use App\Entity\JokeCategory;
use App\Repository\JokeCategoryRepository;
use App\Repository\JokeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class JokeController extends AbstractController
{
    #[Route('/joke', name: 'app_joke')]
    public function index(
        HttpClientInterface $client,
        EntityManagerInterface $entityManager,
        JokeCategoryRepository $jokeCategoryRepository,
        SerializerInterface $serializer,
    ): Response
    {
        $response = $client->request(
            'GET',
            'https://api.chucknorris.io/jokes/categories');
        $categories = $response->toArray();

        foreach ($categories as $category) {
            $jokeCategory = $jokeCategoryRepository->findOneBy(['name' => $category]);
            if (!$jokeCategory){
                $jokeCategory = new JokeCategory();
                $jokeCategory->setName($category);
                $entityManager->persist($jokeCategory);
                $entityManager->flush();
            }
            for($i = 0; $i < 10; $i++){
                $joke = $client->request('GET', 'https://api.chucknorris.io/jokes/random?category='.$category);
                $jokeResponse = $joke->toArray();
                $joke = new Joke();
                $joke->setValue($jokeResponse['value']);
                $joke->setCreatedAt(new \DateTimeImmutable($jokeResponse['created_at']));
                $joke->setJokeCategory($jokeCategory);
                $entityManager->persist($joke);

            }
        }
        $entityManager->flush();

        $allCategories = $jokeCategoryRepository->findAll();
        $json = $serializer->serialize($allCategories, 'json', ['groups' => 'read-joke']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    #[Route('/jokes', name: 'app_jokes')]
    public function listJokes(
        JokeRepository $jokeRepository,
        SerializerInterface $serializer
    ): Response {
        $jokes = $jokeRepository->findAll();
        $json = $serializer->serialize($jokes, 'json', ['groups' => 'read-jokes']);

//        return new Response($json, 200, [
//            'Content-Type' => 'application/json',
//        ]);

        return $this->render('joke/index.html.twig', [
            'jokes' => $jokes,
        ]);
    }
}
