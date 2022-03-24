<?php

namespace App\DataFixtures;

use App\Entity\User as EntityUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use \joshtronic\LoremIpsum;

class User extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $lorem = new LoremIpsum();

        for ($i = 0; $i < 100; $i++) {
            $product = (new EntityUser())
                ->setEmail(sprintf('%s@fake.com', $lorem->words()));
            //$manager->persist($product);
        }

        $manager->flush();
    }
}
