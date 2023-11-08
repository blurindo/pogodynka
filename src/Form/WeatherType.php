<?php

namespace App\Form;

use App\Repository\CityRepository;
use App\Entity\Weather;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WeatherType extends AbstractType
{
    private CityRepository $cities;
    public function __construct(CityRepository $cityRepository)
    {
        $this->cities = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cityNames = $this->cities->findAll();

        $builder
            ->add('temperatureInCelsius')
            ->add('humidity')
            ->add('windSpeed')
            ->add('Date')
            ->add('city', ChoiceType::class, [
                'choices'=>array($cityNames),
                'choice_label' => function($cityNames, $key, $index) {
                    return strtoupper($cityNames->getName() .", " .$cityNames->getCountry());
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weather::class,
        ]);
    }
}
