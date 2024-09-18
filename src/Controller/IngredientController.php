<?php

namespace App\Controller;

use App\DTO\Request\Ingredient\IngredientDTO;
use App\DTO\Request\Ingredient\IngredientEditDTO;
use App\DTO\Request\ListDTO;
use App\Entity\Ingredient;
use App\Messages\AppMessageConstants;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class IngredientController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

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
        IngredientRepository $ingredientRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * @param ListDTO $dto
     * @return JsonResponse
     */
    public function getIngredientList(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )] ListDTO $dto,
    ): JsonResponse
    {
        return new JsonResponse($this->ingredientRepository->getIngredientsList($dto), JsonResponse::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function deleteIngredient(int $id): JsonResponse
    {
        $ingredient = $this->ingredientRepository->find($id);

        if(!$ingredient){
            throw $this->createNotFoundException();
        }

        $this->entityManager->remove($ingredient);
        $this->entityManager->flush();

        return new JsonResponse(['result' => AppMessageConstants::OPERATION_SUCCESSFUL], JsonResponse::HTTP_OK);
    }

    /**
     * @param int $id
     * @param IngredientEditDTO $ingredientDTO
     * @return JsonResponse
     */
    public function editIngredient(int $id,
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationGroups: ['edit'],
            validationFailedStatusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )] IngredientEditDTO $ingredientDTO,
    ): JsonResponse
    {
        $ingredient = $this->ingredientRepository->find($id);

        if(!$ingredient){
            throw $this->createNotFoundException();
        }

        if(null !== $ingredientDTO->getName())
            $ingredient->setName($ingredientDTO->getName());

        if(null !== $ingredientDTO->getDescription())
            $ingredient->setDescription($ingredientDTO->getDescription());

        if(null !== $ingredientDTO->getQuantity())
            $ingredient->setQuantity($ingredientDTO->getQuantity());

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $ingredient->getId()], JsonResponse::HTTP_OK);
    }

    /**
     * @param IngredientDTO $ingredientDTO
     * @return JsonResponse
     */
    public function addIngredient(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )] IngredientDTO $ingredientDTO,
    ): JsonResponse
    {
        $ingredient = new Ingredient();
        $ingredient->setName($ingredientDTO->getName());
        $ingredient->setDescription($ingredientDTO->getDescription());
        $ingredient->setQuantity($ingredientDTO->getQuantity());

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $ingredient->getId()], JsonResponse::HTTP_OK);
    }

}