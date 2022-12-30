<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Profile;
use App\Entity\User;
use EsperoSoft\Faker\Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // use the Faker to create a Faker Generator instance
        $faker = new Faker();

        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = (new User())
                ->setFullName($faker->full_name())
                ->setEmail($faker->email())
                ->setPassword(sha1("azertyuiop1234567890wxcvbn"))
                ->setCreatedAt($faker->dateTimeImmutable());

            $address = (new Address())
                ->setStreet($faker->streetAddress())
                ->setCodePostal($faker->codepostal())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setCreatedAt($faker->dateTimeImmutable());

            $profile = (new Profile)
                ->setPicture($faker->image())
                ->setCoverPicture($faker->image())
                ->setDescription($faker->description(60))
                ->setCreatedAt($faker->dateTimeImmutable());

            $user->addAddress($address);
            $user->setProfile($profile);
            $users[] = $user;

            $manager->persist($address);
            $manager->persist($profile);
            $manager->persist($user);
        }

        // $categories = [];
        $names = [
            "Actualités",
            "Sports",
            "Politique",
            "Cinéma",
            "Musique",
            "Street Art",
            "Economie",
            "Crypto-monnaies",
            "Informatique",
            "Formation",
            "Divers"
        ];
        for ($i = 0; $i < count($names); $i++) {
            $category = (new Category())
                ->setName($names[$i])
                ->setDescription("Description de : ".$names[$i])
                ->setImageUrl($faker->image())
                ->setCreatedAt($faker->dateTimeImmutable());

            $categories[] = $category;
            $manager->persist($category);
        }

        $articles = [];
        for ($i = 0; $i < 144; $i++) {
            $article = (new Article())
                ->setTitle($faker->description(5))
                ->setContent($faker->text(5,10))
                ->setImageUrl($faker->image())
                ->setCreatedAt($faker->dateTimeImmutable())
                // on définit un auteur pour chaque article
                ->setAuthor($users[rand(0, count($users)-1)])
                // on définit une categorie pour chaque article
                ->addCategory($categories[rand(0, count($categories)-1)]);
                // Nota : rand(0, count($categories)-1) renvoie un nb aléatoire entre 0 et la taille du tableau categories

            $manager->persist($article);
        }


        // $product = new Product();
        // $manager->persist($product);

        // pour sauvegarder les infos dans la database
        $manager->flush();
    }
}
