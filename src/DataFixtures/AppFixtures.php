<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $article = new Article();
        $article->setName('Rode sjaal');
        $article->setImage('images/articles/rode-sjaal.png');
        $manager->persist($article);

        $article = new Article();
        $article->setName('Zwarte sjaal');
        $article->setImage('images/articles/zwarte-sjaal.png');
        $manager->persist($article);

        $article = new Article();
        $article->setName('Pet');
        $article->setImage('images/articles/pet.png');
        $manager->persist($article);

        $article = new Article();
        $article->setName('Vlag');
        $article->setImage('images/articles/vlag.png');
        $manager->persist($article);

        $manager->flush();
    }
}
