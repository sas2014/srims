<?php

namespace App\Repository;

use App\DTO\Factory\Recipe\RecipeFactory;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use App\DTO\Request\ListDTO as ListDTORequest;
use App\DTO\Response\ListDTO;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param ListDTORequest $dto
     * @return ListDTO
     */
    public function getRecipesList(ListDTORequest $dto): ListDTO
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->select(
                    'i.id',
                    'i.name',
                    'i.description',
                    "GROUP_CONCAT(JSON_OBJECT('id', iIngredient.id, 'name', iIngredient.id)) AS ingredients"
            )
            ->leftJoin('i.ingredients', 'iIngredient')
            ->orderBy('i.name', 'ASC')
            ->groupBy('i.id')
            ->setMaxResults($dto->getLimit())
            ->setFirstResult($dto->getOffset());

        if($dto->getQuery()) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('i.name', ':searchString'),
                    $queryBuilder->expr()->like('i.description', ':searchString'),
                )
            )
                ->setParameter('searchString', '%' . $dto->getQuery() . '%');
        }

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);
        $totalRows = count($paginator);

        $data = $query->getArrayResult();
        $data = (new RecipeFactory())->createList($data);

        return new ListDTO($data, $totalRows, $dto->getLimit(), $dto->getOffset());
    }
}
