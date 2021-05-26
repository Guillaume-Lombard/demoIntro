<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    public function finsBestSeries($page){

        //en DQL
/**        $entityManager = $this->getEntityManager();
        $dql = "SELECT s FROM App\Entity\Serie as s
                WHERE s.vote > 8 
                AND s.popularity >100 
                ORDER BY s.popularity DESC";

        $query = $entityManager->createQuery($dql);

        $query -> getResult()
 **/
        //avec queryBuilder
        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->addOrderBy("s.popularity", 'DESC');
        $queryBuilder->leftJoin('s.seasons', 'seasons');
        $queryBuilder->addSelect('seasons');

        $query = $queryBuilder->getQuery();

        $offset = ($page - 1) * 50;

        //meme chose pour les 2 methodes
        $query -> setFirstResult($offset);
        $query -> setMaxResults(50);

        //paginator permet de prendre le nombre de serie et non le nombre de ligne (serie+seasons)
        $paginator = new Paginator($query);
        return $paginator;
    }
}
