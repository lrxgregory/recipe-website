<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Category;
use App\DTO\CategoryWithCountDTO;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Gedmo\Translatable\TranslatableListener;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return CategoryWithCountDTO[]
     */
    public function findAllWithCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('NEW App\DTO\CategoryWithCountDTO(c.id, c.name, COUNT(r.id))')
            ->leftJoin('c.recipes', 'r') // Assuming 'recipes' is the field name in the Category entity for the relationship
            ->groupBy('c.id')
            ->getQuery()
            ->setHint(
                Query::HINT_CUSTOM_OUTPUT_WALKER,
                TranslationWalker::class
            )
            ->setHint(
                TranslatableListener::HINT_FALLBACK,
                1
            )
            ->getResult();
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
