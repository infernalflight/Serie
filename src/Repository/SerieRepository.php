<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findSerieBySophisticatedCriterias(string $status1, string $status2): array
    {
        $q = $this->createQueryBuilder('s')
            ->andWhere('s.status = :status')
            ->setParameter('status', $status1)
            ->orWhere('s.status = :status2')
            ->setParameter('status2', $status2)
        ;

        $cond1 = $q->expr()->between('s.firstAirDate', ':min', ':max');
        $cond2 = $q->expr()->gte('s.vote', 5);

        $q->andWhere($cond1)
          ->setParameter('min', '2023-01-01')
          ->setParameter('max', '2023-06-30');

        $q->andWhere($cond2);

        return $q->addOrderBy('s.firstAirDate', 'DESC')
//            ->setMaxResults(4)
 //           ->setFirstResult(0)
            ->getQuery()
            ->getResult();
    }

    public function getAllSeriesWithSeasons(int $page) {

        $offset = ($page -1) * 15;

        $q = $this->createQueryBuilder('s')
            ->addSelect('seasons')
            ->leftJoin('s.seasons', 'seasons')
            ->setFirstResult($offset)
            ->setMaxResults(15)
            ->getQuery()
            ->setHint(CountWalker::HINT_DISTINCT, false)
        ;

        $paginator = new Paginator($q);
        $paginator->setUseOutputWalkers(false);
        return $paginator;

    }


    public function getSeriesByDql(): array
    {

        $dql = "SELECT s FROM App\Entity\Serie s WHERE s.vote > :vote1 AND s.vote < :vote2 AND s.name like '%g%' ORDER BY s.firstAirDate DESC";

        return $this->_em->createQuery($dql)
            ->setParameter('vote1', 5)
            ->setParameter('vote2', 8)
            ->execute();
    }

    public function getSeriesBySql(): array
    {
        $rawSql = "SELECT name, overview FROM serie WHERE vote > :min AND vote < :max ORDER BY first_air_date DESC";
        $conn = $this->_em->getConnection();
        return $conn->prepare($rawSql)->executeQuery(['min' => 5, 'max' => 8])->fetchAllAssociative();

    }




//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
