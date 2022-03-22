<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class Category extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['drama', 'thriller', 'comedy', 'action', 'horror'] as $label) {
            $category = (new \App\Entity\Category())->setName($label);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
