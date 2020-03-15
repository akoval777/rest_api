<?php

namespace App\DataFixtures;

use App\Entity\Shop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	for ($i = 1; $i < 21; $i++) {
            $shop = new Shop();
            $shop->setName('Shop '.$i);
            $manager->persist($shop);
        }

        $manager->flush();
    }
}
