<?php

namespace App\Repository;

use App\Entity\Token;
use App\Entity\User;
use App\Utils\Utils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Token>
 */
class TokenRepository extends ServiceEntityRepository
{
    /**
     * @var int
     */
    private int $authenticationTokenTTL = 7200;

    /**
     * @param int $authenticationTokenTTL
     * @param ManagerRegistry $registry
     */
    public function __construct(int $authenticationTokenTTL, ManagerRegistry $registry)
    {
        $this->authenticationTokenTTL = $authenticationTokenTTL;
        parent::__construct($registry, Token::class);
    }

    /**
     * @throws \Exception
     */
    public function saveToken($tokenString, User $user)
    {
        $token = new Token();
        $token->setToken($tokenString);
        $token->setUser($user);
        $token->setExpirationDate(Utils::getCurrentGMDate($this->authenticationTokenTTL));

        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();
    }

    /**
     * @return string
     */
    public function generateToken(): string
    {
        return md5((uniqid('', true) . rand(10000, 99999) . time()));
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function isTokenValid(Token $token): bool
    {
        $now = Utils::getCurrentGMDate();
        $expirationDate = $token->getExpirationDate();

        return $now <= $expirationDate;
    }

}
