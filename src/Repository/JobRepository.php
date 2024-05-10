<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\Person;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }


    public function findByJobBetweenDate(Person $person, DateTime $startDate, ?DateTime $endDate): array {      
        
        if (!$endDate){
            $endDate= new DateTime();
        }

        $q = $this->createQueryBuilder('job');
        return $q
        ->leftJoin("job.person",'person')
        ->andWhere('person.id = :personId')
        ->andWhere(
            $q->expr()->orX(
            $q->expr()->between('job.startedAt', ':startDate', ':endDate'),
            $q->expr()->between('job.stopedAt', ':startDate', ':endDate')
            )
        )
        ->setParameter(':personId', $person->getId())
        ->setParameter(':startDate', $startDate)
        ->setParameter(':endDate', $endDate)
        ->getQuery()
        ->getResult();
    }

}
