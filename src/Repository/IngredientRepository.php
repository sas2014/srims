<?php

namespace App\Repository;

use App\DTO\Factory\Ingredient\IngredientFactory;
use App\DTO\Response\ListDTO;
use App\DTO\Request\ListDTO as ListDTORequest;
use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * @param ListDTORequest $dto
     * @return ListDTO
     */
    public function getIngredientsList(ListDTORequest $dto): ListDTO
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->orderBy('i.name', 'ASC')
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
        $data = (new IngredientFactory())->createList($data);

        return new ListDTO($data, $totalRows, $dto->getLimit(), $dto->getOffset());
    }
}
