<?php

namespace App\Repository;
use App\Entity\Categorie;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * Count the number of games in each category.
     *
     * @return array Returns an array of results with category name and game count.
     */
    public function countGamesByCategory(): array
    {
        return $this->createQueryBuilder('g')
            ->select('c.name as categorieName, COUNT(g.id) as gameCount') // Select c.name instead of categorie
            ->leftJoin('g.categorie', 'c')  // Join the Categorie entity
            ->groupBy('c.name')             // Group by the category name
            ->getQuery()
            ->getResult();
    }

    /**
     * Find games by a specific category.
     *
     * @param Categorie $categorie The category to filter by.
     * @return Game[] Returns an array of Game objects.
     */
    public function findByCategorie(Categorie $categorie): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('g.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all games with their associated category.
     *
     * @return Game[] Returns an array of Game objects with their categories.
     */
    public function findAllWithCategory(): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.categorie', 'c')  // Join the Categorie entity
            ->addSelect('c')                // Select the category to avoid lazy loading
            ->orderBy('g.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}