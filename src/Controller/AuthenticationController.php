<?php

namespace App\Controller;

use App\Messages\AppMessageConstants;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthenticationController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var TokenRepository
     */
    private TokenRepository $tokenRepository;

    /**
     * @param TokenRepository $tokenRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenRepository $tokenRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param User|null $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(AppMessageConstants::WRONG_CREDENTIALS, Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->tokenRepository->generateToken();

        $this->tokenRepository->saveToken($token, $user);

        return new JsonResponse(['token' => $token]);
    }
}