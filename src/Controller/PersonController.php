<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Person;
use App\Repository\JobRepository;
use App\Repository\PersonRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

#[Route('/api/person')]
class PersonController extends AbstractController
{

    /**
     * List the persons order by name.
     *
     *
     * @OA\Response(
     *     response=200,
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Person::class, groups={"getPerson"}))
     *     )
     * )
     */
    #[Route('/list', name: 'api_person_list', methods:['GET'])]
    public function getPersonList(PersonRepository $personRepository): JsonResponse
    {
        $personList  = $personRepository->findAllOrderByName();

        return $this->json(
            $personList
        ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
    }


    /**
     * List the persons that have work in the same company.
     *
     *
     * @OA\Response(
     *     response=200,
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Person::class, groups={"getPerson"}))
     *     )
     * )
     */
    #[Route('/list/{companyName}', name: 'api_person_list_by_company_name', methods:['GET'])]
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

    /**
     * Add a person.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the person",
     *     @OA\JsonContent(
     *        @Model=Person::class, groups={"getPerson"},
     *     )
     * )
     */
    #[Route('/add', name: 'api_person_add', methods:['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em
    ): JsonResponse 
    {
        // We check if the birthdate is valid
        $person = $serializer->deserialize($request->getContent(), Person::class, 'json');
        $dateActuelle = new DateTime();
        $difference = $person->getBirthdate()->diff($dateActuelle)->y;
        if (
            $person->getBirthdate() > $dateActuelle 
            || $difference > 150 ) {
            throw new BadRequestHttpException('Incorrect birthdate');
        }  

        $em->persist($person);
        $em->flush();

        $jsonBook = $serializer->serialize($person, 'json', ['groups' => 'getBooks']);
        
        
        return $this->json(
            $person
        ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
    }

    /**
     * Add a job to a person.
     *
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the person",
     *     @OA\JsonContent(
     *        @Model=Person::class, groups={"getPerson"},
     *     )
     * )
     */
    #[Route('/{id}/job/add', name:'api_job_add', methods: ['POST'])]
    public function addJob(Person $person,Request $request, SerializerInterface $serializer, EntityManagerInterface $em
    ): JsonResponse{

        $job = $serializer->deserialize($request->getContent(), Job::class,'json');
        if($job->getStopedAt() && ($job->getStopedAt()< $job->getStartedAt() || $job->getStopedAt() > new DateTime() ) ){
            throw new BadRequestHttpException('Incorrect date');
        }

        $job->setPerson($person);

        $em->persist($job);
        $em->flush();

        return $this->json(
            $person
        ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
    }

    /**
     * List a person job between 2 date
     *
     *
     * @OA\Response(
     *     response=200,
     *     @OA\JsonContent(
     *        @Model=Job::class, groups={"getPerson"},
     *     )
     * )
     */
    #[Route('/{id}/job/between/{start}/{finish}', name: 'api_person_job_between_date', methods:['GET'])]
    public function getListBetweenDate(
        Person $person,
        DateTime $start, 
        ?DateTime $finish,
        JobRepository $jobRepository
    ): JsonResponse
    {

        $personList  = $jobRepository->findByJobBetweenDate($person, $start, $finish);
        if( ($finish && $finish < $start )
         || $start > new DateTime() ){
            throw new BadRequestHttpException('Incorrect date');
        }
        return $this->json(
            $personList
        ,  Response::HTTP_OK,[],  ['groups' => 'getPerson']);
        
    }

}


