<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Location;
use App\Entity\Forecast;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $location = $this->addLocation("Barcelona","ES",41.3874,2.1686);
        $manager->persist($location);

        $forecast = $this->addForecast($location,'2024-01-01',23,25,1009,49,7.7,90,0,'sun');
        $manager->persist($forecast);
        $forecast = $this->addForecast($location,'2024-01-02',20,17,999,70,3.2,45,75,'cloud');
        $manager->persist($forecast);
        $forecast = $this->addForecast($location,'2024-01-03',21,22,1025,40,0.7,0,25,'cloud-sun');
        $manager->persist($forecast);

        $location = $this->addLocation("Berlin","DE",52.5200,13.4050);
        $manager->persist($location);

        $forecast = $this->addForecast($location,'2024-01-01',11,9,989,92,1,180,75,'cloud-rain');
        $manager->persist($forecast);
        $forecast = $this->addForecast($location,'2024-01-02',10,10,1000,50,3.2,90,75,'cloud');
        $manager->persist($forecast);
        $forecast = $this->addForecast($location,'2024-01-03',15,15,1025,40,0.7,0,25,'cloud-sun');
        $manager->persist($forecast);

        $location = $this->addLocation("Paris","FR",48.8575,2.3514);
        $manager->persist($location);

        
        $forecast = $this->addForecast($location,'2024-01-01',11,9,989,92,1,180,75,'cloud-rain');
        $manager->persist($forecast);
        $forecast = $this->addForecast($location,'2024-01-02',10,10,1000,50,3.2,90,75,'cloud');
        $manager->persist($forecast);
        $forecast = $this->addForecast($location,'2024-01-03',15,15,1025,40,0.7,0,25,'cloud-sun');
        $manager->persist($forecast);

        $location = $this->addLocation("Warsaw","PL",52.2297,21.0122);
        $manager->persist($location);

        $location = $this->addLocation("Delhi","IN",28.7041,77.1025);
        $manager->persist($location);

        $manager->flush();
    }

    private function addLocation(
        string $name,
        string $countryCode,
        float $latitude,
        float $longitude
        ) : Location
    {
        $location = new Location();
        $location
                ->setName($name)
                ->setCountryCode($countryCode)
                ->setLatitude($latitude)
                ->setLongitude($longitude)
                ;

        return $location;
    }

    private function addForecast(
        Location $location,
        string $dateString,
        int $celsius,
        int $flTemperatureCelsius,
        int $pressure,
        int $humidity,
        float $windSpeed,
        int $windDeg,
        int $cloudiness,
        string $icon
        ) : Forecast
    {
        $forecast = new Forecast();
        $forecast
                ->setLocation($location)
                ->setDate(new \DateTime($dateString))
                ->setCelcius($celsius)
                ->setFlTemperatureCelsius($flTemperatureCelsius)
                ->setPressure($pressure)
                ->setHumidity($humidity)
                ->setWindSpeed($windSpeed)
                ->setWindDeg($windDeg)
                ->setCloudiness($cloudiness)
                ->setIcon($icon)
                ;

        return $forecast;
    }



}
