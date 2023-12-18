<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\Weather;
use App\Repository\CityRepository;
use App\Repository\WeatherRepository;

class WeatherUtil
{
    public function __construct(
        private readonly CityRepository $locationRepository,
        private readonly WeatherRepository $measurementRepository
    )
    {
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForLocation(City $location): array
    {
        $measurements = $this->measurementRepository->findByLocation($location);
        return $measurements;
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        $measurements = $this->getWeatherForLocation($location);

        return $measurements;
    }
}