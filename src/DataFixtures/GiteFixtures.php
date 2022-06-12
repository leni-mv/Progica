<?php

namespace App\DataFixtures;

use App\Entity\Gite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<20; $i++){

            $gite = new Gite();
            $gite
                ->setNom('Gite' . $i)
                ->setDescription('Super description n°' . $i)
                ->setSurface(mt_rand(30, 120))
                ->setChambre(mt_rand(1,8))
                ->setCouchage(mt_rand(1, 16));

            $manager->persist($gite);

            // $product = new Product();
        // $manager->persist($product);

        }

        $manager->flush();
    }
}
