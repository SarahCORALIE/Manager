<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findAllOrderByName(): array{
        return $this->createQueryBuilder('person')
        ->leftJoin("person.jobs",'job')
        ->orderBy('person.name', 'ASC')
        ->getQuery()
        ->getResult()
    ;
    }

    public function findByCompanyName(string $companyName): array {
        return $this->createQueryBuilder('person')
        ->leftJoin("person.jobs",'job')
        ->where('job.companyName = :companyName')
        ->setParameter(':companyName', $companyName)
        ->orderBy('person.name', 'ASC')
        ->getQuery()
        ->getResult();

    }

    public function findByJobBetweenDate(string $startDate, string $endDate): array {      
        $q = $this->createQueryBuilder('person');
        return $q
        ->leftJoin("person.jobs",'job')
        ->where(
            $q->expr()->orX(
            $q->expr()->between('job.startedAt', ':startDate', ':endDate'),
            $q->expr()->between('job.startedAt', ':startDate', ':endDate')
            )
        )
        ->setParameter(':startDate', $startDate)
        ->setParameter(':endDate', $endDate)
        ->orderBy('person.name', 'ASC')
        ->getQuery()
        ->getResult();
     }

}
