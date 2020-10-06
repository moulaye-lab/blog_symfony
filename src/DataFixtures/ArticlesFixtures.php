<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article; 
use App\Entity\Comment; 
use App\Entity\Category; 

/**
 * le use ici permet de faire appel la class Article dans Entity
 * un peu comme un include
 * 
 * $manager->persist permet de faire persister les fixture dans le temps 
 * 
 * $manager->flush() envoie la reque sql pour integrer dansla base de donnée
 * 
 */

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR'); //on chope faker dans son namespace

        //creer des categories fake

        for ($i=1; $i <=3 ; $i++) { 
           $category= new Category;
           $category->setTitle($faker->sentence());
           $category->setDescription($faker->paragraph());

           $manager->persist($category);

           //creer des articles
           for($j =1 ; $j <= mt_rand(4 ,6) ; $j++ ){

            $article = new Article();

            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') .
            '</p>';
                    
            
                $article->setTitle($faker->sentence())
                            ->setContent($content)
                            ->setImage($faker->imageUrl())
                            ->setCreatedAt($faker->dateTimeBetween('-6months'))
                            ->setCategory($category);
                    $manager->persist($article);
               
            for ($k=1; $k <= mt_rand(5 ,10) ; $k++) { 
                
                $comment =new Comment;

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') .
            '</p>';

                $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAT($faker->sentence())
                        ->setArticle($article);
                        $manager->persist($comment);

                    }
               
                }


        }

       

        $manager->flush();
    }
}