<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setUsername('Nik' . $i);
            $client->setEmail('test' . $i . '@gmail.com');
            $client->setPhone('+37529777777' . $i);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
