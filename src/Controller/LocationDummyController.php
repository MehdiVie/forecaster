<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Doctrine\ArgumentResolver\EntityValueResolver;

#[Route('/location-dummy')]
class LocationDummyController extends AbstractController
{
    #[Route('/create')]
    public function create(LocationRepository $locationRepository): JsonResponse
    {
        $location = new Location();

        $location
                ->setName('Szczecin')
                ->setCountryCode('PL')
                ->setLatitude(53.4285)
                ->setLongitude(14.5528)
                ;
        
        $locationRepository->save($location,true);


        return $this->json([
            'id' => $location->getId(),
        ]);
    }

    #[Route('/edit')]
    public function edit(
        LocationRepository $locationRepository
    ) : JsonResponse
    {
        $location = $locationRepository->find(7);
        $location->setName('Stettin');
        
        $locationRepository->save($location,true);

        return $this->json([
            'id' => $location->getId(),
            'name' => $location->getName(),
        ]);
    }

    #[Route('/remove/{id}')]
    public function remove(
        LocationRepository $locationRepository,
        int $id
    ) : JsonResponse
    {
        $location = $locationRepository->find($id);
        
        $locationRepository->remove($location,flush:true);

        return $this->json([
            'deleted?' => "Yes"
        ]);
    }

    #[Route('/show/{name}')]
    public function show(
        LocationRepository $locationRepository,
        string $name
    ) : JsonResponse
    {
        $location = $locationRepository->findOneByName($name);

        if(!$location) {
            throw $this->createNotFoundException();
        }
        //#[MapEntity(mapping: ['name' => 'id'])]

        // $json=[];
        // foreach($locations as $location)
        $json = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'countryCode' => $location->getCountryCode(),
                'lat' => $location->getLatitude(),
                'long' => $location->getLongitude(),
        ];

        foreach($location->getForecasts() as $forecast)
        {
            $json['forecasts'][$forecast->getDate()->format('Y-m-d')]=[
                'celcius' => $forecast->getCelcius(),
            ];
        }

        return new JsonResponse($json);

    }

    #[Route('/')]
    public function index(
        LocationRepository $locationRepository
    ) : JsonResponse
    {
        $locations = $locationRepository->findAllWithForecasts();

        $json=[];
        foreach($locations as $location)
        {
            $locationjson[] = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'countryCode' => $location->getCountryCode(),
                'lat' => $location->getLatitude(),
                'long' => $location->getLongitude(),
            ];

            foreach($location->getForecasts() as $forecast)
            {
                $locationjson['forecasts'][$forecast->getDate()->format('Y-m-d')]=[
                'celcius' => $forecast->getCelcius(),
                ];
            }

            $json[]=$locationjson;


        }

        return new JsonResponse($json);
    }
}
