<?php

namespace App\Security;

use App\Messages\AppMessageConstants;
use App\Repository\TokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class CustomAuthenticator extends AbstractAuthenticator
{
    public const AUTHORIZATION_HEADER = 'Authorization';

    /**
     * @var TokenRepository
     */
    private TokenRepository $tokenRepository;

    /**
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::AUTHORIZATION_HEADER);
    }

    /**
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get(self::AUTHORIZATION_HEADER);
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('', [AppMessageConstants::NO_API_TOKEN_PROVIDED]);
        }

        $apiToken = $this->tokenRepository->find($apiToken);

        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('', [AppMessageConstants::API_TOKEN_INVALID]);
        }

        if (!$this->tokenRepository->isTokenValid($apiToken)) {
            throw new CustomUserMessageAuthenticationException('', [AppMessageConstants::API_TOKEN_EXPIRED]);
        }

        $loggedUser = $apiToken->getUser();

        if (null === $loggedUser) {
            throw new CustomUserMessageAuthenticationException('', [AppMessageConstants::API_TOKEN_INVALID]);
        }

        return new SelfValidatingPassport(new UserBadge('', function ($user) use ($loggedUser) { return $loggedUser; }));
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => $exception->getMessageData()
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}