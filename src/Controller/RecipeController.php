<?php

namespace App\Controller;

use App\DTO\Request\ListDTO;
use App\DTO\Request\Recipe\RecipeDTO;
use App\DTO\Request\Recipe\RecipeEditDTO;
use App\Entity\Recipe;
use App\Messages\AppMessageConstants;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class RecipeController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var RecipeRepository
     */
    protected RecipeRepository $recipeRepository;

    /**
     * @var IngredientRepository
     */
    protected IngredientRepository $ingredientRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RecipeRepository $recipeRepository
     * @param IngredientRepository $ingredientRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RecipeRepository $recipeRepository,
        IngredientRepository $ingredientRepository,
    )
    {
        $this->entityManager = $entityManager;
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * @param ListDTO $dto
     * @return JsonResponse
     */
    public function getRecipeList(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )] ListDTO $dto,
    ): JsonResponse
    {
        return new JsonResponse($this->recipeRepository->getRecipesList($dto), JsonResponse::HTTP_OK);
    }

    /**
     * @param RecipeDTO $RecipeDTO
     * @return JsonResponse
     */
    public function addRecipe(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationFailedStatusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        )] RecipeDTO $recipeDTO,
    ): JsonResponse
    {
        $recipe = new Recipe();
        $recipe->setName($recipeDTO->getName());
        $recipe->setDescription($recipeDTO->getDescription());

        $ingredientEntities = $this->ingredientRepository->getIngredientsByIds(
            array_unique($recipeDTO->getIngredients()),
            $this->ingredientRepository::RETURN_TYPE_ENTITY
        );

        foreach ($ingredientEntities as $ingredient) {
            $recipe->addIngredient($ingredient);
        }

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $recipe->getId()], JsonResponse::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function deleteRecipe(int $id): JsonResponse
    {
        $recipe = $this->recipeRepository->find($id);

        if(!$recipe){
            throw $this->createNotFoundException();
        }

        $this->entityManager->remove($recipe);
        $this->entityManager->flush();

        return new JsonResponse(['result' => AppMessageConstants::OPERATION_SUCCESSFUL], JsonResponse::HTTP_OK);
    }

    /**
     * @param int $id
     * @param RecipeEditDTO $ingredientDTO
     * @return JsonResponse
     */
    public function editRecipe(int $id,
       #[MapRequestPayload(
           acceptFormat: 'json',
           validationGroups: ['edit'],
           validationFailedStatusCode: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
       )] RecipeEditDTO $recipeDTO,
    ): JsonResponse
    {
        $recipe = $this->recipeRepository->find($id);

        if(!$recipe){
            throw $this->createNotFoundException();
        }

        if(null !== $recipeDTO->getName())
            $recipe->setName($recipeDTO->getName());

        if(null !== $recipeDTO->getDescription())
            $recipe->setDescription($recipeDTO->getDescription());

        if(null !== $recipeDTO->getIngredients()) {
            $ingredientEntities = $this->ingredientRepository->getIngredientsByIds(
                array_unique($recipeDTO->getIngredients()),
                $this->ingredientRepository::RETURN_TYPE_ENTITY
            );

            foreach ($recipe->getIngredients() as $ingredient) {
                $recipe->removeIngredient($ingredient);
            }

            foreach ($ingredientEntities as $ingredient) {
                $recipe->addIngredient($ingredient);
            }
        }

        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $recipe->getId()], JsonResponse::HTTP_OK);
    }
}