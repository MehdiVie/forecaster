<?php

namespace App\Controller;

use App\Model\HighlanderApiDTO;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/weather' , requirements: [
    '_locale' => 'en|de'
])]
class WeatherController extends AbstractController
{
    #[Route('/highlander-says/api')]
    // #[Route('/weather/highlander-says/{threshold}', priority:2,requirements:['threshold' => '\d+'] ,name: 'this is my name', methods: ['GET','POST'])]
    public function highlanderSaysApi(#[MapQueryString] ?HighlanderApiDTO $dto=null) : Response
    {
        if (!$dto) {
            $dto = new HighlanderApiDTO();
            $dto->threshold = 50;
            $dto->trials=3;
        }

        for($i=0; $i < $dto->trials ; $i++) {

            $draw = random_int(0,100);
            $forecast=$draw<$dto->threshold? "rainy" : "sunny";
            $forecasts[]=$forecast;
        }

        $json = [
            'forecasts' => $forecasts,
            'threshold' => $dto->threshold,
            // 'self' => $this->generateUrl(
            //     'app_weather_highlandersaysapi', 
            //     ['threshold' => $threshold],
            //     UrlGeneratorInterface::ABSOLUTE_URL
            // ),
        ];
        return new JsonResponse($json);
        // return $this->json($json);
        // return $this->file(__DIR__.'/fedration.jpg','logo.png',
        // ResponseHeaderBag::DISPOSITION_INLINE);
    } 

    #[Route('/highlander-says/{threshold<\d+>}')]
    // #[Route('/weather/highlander-says/{threshold}', priority:2,requirements:['threshold' => '\d+'] ,name: 'this is my name', methods: ['GET','POST'])]
    public function highlanderSays(
        Request $request,
        RequestStack $requestStack,
        TranslatorInterface $translator,
        ?int $threshold=null,
        #[MapQueryParameter] ?string $_format = 'html'
    ) : Response
    {
        // print_r($threshold);
        // die();
        $session = $requestStack->getSession();
        if ($threshold) {
            $session->set('threshold',$threshold);
            $this->addFlash('info',
            $translator->trans('weather.highlander_says.success',[
                '%threshold%' => $threshold,
            ]));
        }
        else
        {
            $threshold=$session->get('threshold',55);
        }

        $trials= $request->get('trials',1);
        $forecasts=[];

        for($i=0; $i<$trials ; $i++) {

            $draw = random_int(0,100);
            $forecast=$draw<$threshold? "rainy" : "sunny";
            $forecasts[]=$forecast;
        }
        

        return $this->render("weather/highlander_says.{$_format}.twig", [
            'forecasts' => $forecasts,
            'threshold' => $threshold,
        ]);
    } 

    #[Route('/highlander-says/{guess}')]
    public function highlanderSaysGuess(string $guess): Response
    {
        $availableGuesses = ['snow' , 'rain' , 'hail'];

        if (!in_array($guess,$availableGuesses)) {
            //throw $this->createNotFoundException('This guess is not found!');
            //throw new NotFoundHttpException('This guess is not found!');
            throw new Exception('base Exception!');
        }
        $forecast = "it is going to be $guess !!";

        return $this->render('weather/highlander_says.html.twig', [
            'forecasts' => [$forecast],
        ]);
    } 


    #[Route('/{country}/{city}')]
    public function forecast(
        LocationRepository $locationRepository,
        ForecastRepository $forecastRepository,
        string $country, 
        string $city
        ) : Response
    {
        $location = $locationRepository->findOneBy([
            'countryCode' => $country,
            'name' => $city
        ]);

        if(!$location){
            throw $this->createNotFoundException('location not found!');
        }

        $forecasts = $forecastRepository->findForForecast($location);
        
        $response =  $this->render('weather/forecast.html.twig', [
            'forecasts' => $forecasts,
            'location' => $location,
        ]);

        return $response;
        // $forecasts = [
        //     [
        //         "date" => new DateTime('2024-01-01') ,
        //         "temperatureCelsius" => 17,
        //         "flTemperatureCelsius"=> 16,
        //         "pressure" => 100,
        //         "humidity" => 64,
        //         "wind_speed" => 102,
        //         "wind_deg" => 270,
        //         "cloudiness"=> 75,
        //         "icon" => 'sun'
        //     ],
        //     [
        //         "date" => new DateTime('2024-01-02') ,
        //         "temperatureCelsius" => 17,
        //         "flTemperatureCelsius"=> 16,
        //         "pressure" => 100,
        //         "humidity" => 64,
        //         "wind_speed" => 102,
        //         "wind_deg" => 270,
        //         "cloudiness"=> 77,
        //         "icon" => 'cloud'
        //     ],
        //     [
        //         "date" => new DateTime('2024-01-03') ,
        //         "temperatureCelsius" => 17,
        //         "flTemperatureCelsius"=> 16,
        //         "pressure" => 100,
        //         "humidity" => 64,
        //         "wind_speed" => 102,
        //         "wind_deg" => 270,
        //         "cloudiness"=> 75,
        //         "icon" => 'cloud-rain'
        //     ],

        // ];

    } 
    
    // #[Route('/highlander-says/{threshold<\d+>?50}')]
    // public function highlanderSays(int $threshold, Request $request): Response
    // {
    //     $trials = $request->get('trials',1);

    //     $forecasts=[];

    //     for ($i=0; $i < $trials; $i++) { 

    //         $draw = random_int(0,100);
    //         $forecast = ($draw < $threshold) ? "rainy!!" : "sunny!!";
    //         $forecasts[]=$forecast;
    //     }

    //     return $this->render('weather/index.html.twig', [
    //         'forecasts' => $forecasts,
    //     ]);
    // }


}
