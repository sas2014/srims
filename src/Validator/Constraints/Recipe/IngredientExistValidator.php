<?php

namespace App\Validator\Constraints\Recipe;

use App\Messages\AppMessageConstants;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class IngredientExistValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var IngredientRepository
     */
    protected IngredientRepository $ingredientRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param IngredientRepository $ingredientRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        IngredientRepository $ingredientRepository,
    )
    {
        $this->entityManager = $entityManager;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $presentIds = array_column($this->ingredientRepository->getIngredientsByIds($value), 'id');

        $diff = array_diff($value, $presentIds);
        if($diff !== []){
            $this->context->buildViolation(AppMessageConstants::INGREDIENTS_IDS_NOT_FOUND_ERROR)
                ->setParameter('idsNotFound', implode(',', $diff))
                ->addViolation();
        }
    }

}