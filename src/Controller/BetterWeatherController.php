<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Form\WeatherType;
use App\Repository\WeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/better/weather')]
class BetterWeatherController extends AbstractController
{
    #[Route('/', name: 'app_better_weather_index', methods: ['GET'])]
    #[IsGranted('ROLE_FORECAST_INDEX')]
    public function index(WeatherRepository $weatherRepository): Response
    {
        return $this->render('better_weather/index.html.twig', [
            'weather' => $weatherRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_better_weather_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_FORECAST_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weather = new Weather();
        $form = $this->createForm(WeatherType::class, $weather, [
            'validation_groups' => 'create',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weather);
            $entityManager->flush();

            return $this->redirectToRoute('app_better_weather_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('better_weather/new.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_better_weather_show', methods: ['GET'])]
    #[IsGranted('ROLE_FORECAST_SHOW')]
    public function show(Weather $weather): Response
    {
        return $this->render('better_weather/show.html.twig', [
            'weather' => $weather,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_better_weather_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_FORECAST_EDIT')]
    public function edit(Request $request, Weather $weather, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeatherType::class, $weather, [
            'validation_groups' => 'create',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_better_weather_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('better_weather/edit.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_better_weather_delete', methods: ['POST'])]
    #[IsGranted('ROLE_FORECAST_DELETE')]
    public function delete(Request $request, Weather $weather, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weather->getId(), $request->request->get('_token'))) {
            $entityManager->remove($weather);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_better_weather_index', [], Response::HTTP_SEE_OTHER);
    }
}
