<?php

namespace App\DataFixtures;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('test@test.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPassword('$2y$13$/xZf.IqJHzWd58hnEV8R9uIalCQSKOqqKH0SRnIDV8zsPm.lZyk6q'); //1
        $manager->persist($user);

        $token = (new Token())
            ->setUser($user)
            ->setExpirationDate(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2034-01-01 23:59:59'))
            ->setToken('dadd29a55c5220f6d8dc5c25d581c8');
        $manager->persist($token);

        $manager->flush();
    }
}
