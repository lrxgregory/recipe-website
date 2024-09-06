<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Gedmo\Translatable\TranslatableListener;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function paginateRecipes(int $page): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.category', 'c') // 'c' is the alias for the Category entity
            ->addSelect('c'); // Add 'c' to the select to ensure fields from Category are included

        // Pagination with proper alias references in the sortFieldAllowList
        return $this->paginator->paginate(
            $queryBuilder->getQuery() ->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                TranslationWalker::class
            ),
            $page,
            20,
            [
                'distinct' => false,
                'defaultSortFieldName' => 'r.title',
                'defaultSortDirection' => 'asc',
                'sortFieldAllowList' => ['r.title', 'c.name'] // Correct alias for sorting fields
            ]
        );
    }

    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->where('r.duration <= :duration')
            ->orderBy('r.duration', 'ASC')
            ->leftJoin('r.category', 'c')
            ->setMaxResults(10)
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
