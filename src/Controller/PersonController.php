<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonController extends AbstractController
{
    #[Route('/person/list', name: 'api_person_list', methods:['GET'])]
    public function getPersonList(PersonRepository $personRepository): JsonResponse
    {
        $personList  = $personRepository->findAllOrderByName();

        return $this->json(
            $personList
        ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
    }

    #[Route('/person/list/{companyName}', name: 'api_person_list_by_company_name', methods:['GET'])]
    public function getListByCompanyName(
        string $companyName, 
        PersonRepository $personRepository
    ): JsonResponse
    {

        $personList  = $personRepository->findByCompanyName($companyName);

        return $this->json(
            $personList
        ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
        
    }

    // #[Route('/person/list/betweenDate', name: 'api_person_list_by_company_name', methods:['POST'])]
    // public function getListBetweenDate(
    //     string $start, 
    //     DateTime $end,
    //     PersonRepository $personRepository
    // ): JsonResponse
    // {

    //     $personList  = $personRepository->findByJobBetweenDate($start, $end);

    //     return $this->json(
    //         $personList
    //     ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
        
    // }


    // #[Route('/person/add', name: 'api_person_add', methods:['POST'])]
    // public function add(): JsonResponse
    // {

    //     // return $this->json([
    //     //     'data' => 'Welcome to your new controller!',
    //     //     'path' => 'src/Controller/PersonController.php',
    //     // ]);
    // }

}


