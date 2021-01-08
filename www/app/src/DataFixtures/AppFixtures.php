<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i<20; $i++) {
            $user = new User();
            $user->setUsername('user '.$i);
            $manager->persist($user);
            $article = new Article();
            $article->setTitle('Title ' . $i);
            $article->setContent('Content '.$i);
            $article->setUser($user);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
