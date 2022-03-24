<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use joshtronic\LoremIpsum;

class Product extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $lorem = new LoremIpsum();
        $categories = $manager->getRepository(\App\Entity\Category::class)->findAll();
        for ($i = 0; $i < 100; $i++) {
            $product = new \App\Entity\Product();
            $product->setName('Product ' . $i);
            $product->setPrice(rand(1, 1000) / 100);
            $product->setDetail($lorem->words(30));
            $manager->persist($product);
            for ($j = 0; $j < rand(1, 3); $j++) {
                $product->addCategory($categories[rand(0, count($categories) - 1)]);
            }
        }

        $manager->flush();
    }
}
