<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\WeatherRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WeatherController extends AbstractController
{
    #[Route('/weather/{cityName}/{country}', defaults: ['countryCode' => 'PL'])]
    public function cityShow(WeatherRepository $weatherRepository, #[MapEntity(expr: 'repository.findByName(cityName,countryCode)')]
    City $city): Response
    {
        $measurements = $weatherRepository->findByLocation($city);
        
        return $this->render('weather/city.html.twig', [
            'city' => $city,
            'measurements' => $measurements
        ]);
    }
}
