<?php

namespace App\Repository;

use App\DTO\Factory\Ingredient\IngredientFactory;
use App\DTO\Response\ListDTO;
use App\DTO\Request\ListDTO as ListDTORequest;
use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    const RETURN_TYPE_ARRAY = 1;

    const RETURN_TYPE_DTO = 2;

    const RETURN_TYPE_ENTITY = 3;
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
                ->setParameter('searchString', '%' . $dto->getQuery() . '%', ParameterType::STRING);
        }

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);
        $totalRows = count($paginator);

        $data = $query->getArrayResult();
        $data = (new IngredientFactory())->createList($data);

        return new ListDTO($data, $totalRows, $dto->getLimit(), $dto->getOffset());
    }

    /**
     * @param array|int $ids
     * @param int $returnType
     * @return array
     */
    public function getIngredientsByIds(array|int $ids, int $returnType = IngredientRepository::RETURN_TYPE_ARRAY): array
    {
        if(empty($ids)) {
            return [];
        }
        if(!is_array($ids)) {
            $ids = [$ids];
        }
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->in(
                    'i.id',
                    ':inIds'
                )
            )
            ->setParameter('inIds', $ids, ArrayParameterType::INTEGER);
        $dataQuery = $queryBuilder->getQuery();

        if($returnType == IngredientRepository::RETURN_TYPE_DTO) {
            $data = $dataQuery->getArrayResult();
            $data = (new IngredientFactory())->createList($data);
        } elseif ($returnType == IngredientRepository::RETURN_TYPE_ENTITY) {
            $data = $dataQuery->getResult();
        } else {
            $data = $dataQuery->getArrayResult();
        }

        return $data;
    }
}
