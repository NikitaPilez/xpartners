<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('Admin');
        $user->setRoles(['ROLE_USER']);
        $user->setToken('12345678');
        $manager->persist($user);

        $manager->flush();
    }
}
