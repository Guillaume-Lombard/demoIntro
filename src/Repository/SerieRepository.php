<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
 **/
        //avec queryBuilder
        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->addOrderBy("s.popularity", 'DESC');

        $query = $queryBuilder->getQuery();

        //page1 offset 0
        //page2 offset 50
        //page3 offset 100

        $offset = ($page - 1) * 50;

        //meme chose pour les 2 methodes
        $query -> setFirstResult($offset);
        $query -> setMaxResults(50);
        return $query -> getResult();




    }
}
