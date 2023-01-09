<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager ): void
    {

        $faker= Factory::create('fr_FR');
        for ($i = 0; $i < 20 ; $i++){
            $user = new User();
            $user->setEmail($faker->email())
                ->setCreatedAt(new \DateTime())
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setActif($faker->boolean(50));
            if ($faker->boolean(75)){
                $user->setPseudo($faker->userName);
            }
            $numrole = $faker->numberBetween(1,3);
            if ($numrole == 1){
                $user->setRoles(["ROLE_USER"]);
            }
            if ($numrole == 2){
                $user->setRoles(["ROLE_RESTAURANT"]);
            }
            if ($numrole == 3){
                $user->setRoles(["ROLE_ADMIN"]);
            }
            $passwordHash = $this->passwordHasher->hashPassword(
                $user,
                "password"
            );


            $user->setPassword($passwordHash);
            $manager->persist($user);


        }
        $admin = new User();
        $admin->setEmail("admin@admin.fr")
            ->setCreatedAt(new \DateTime())
            ->setNom("Admin")
            ->setPrenom("Admin")
            ->setActif(true)
            ->setPseudo("Admin")
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                "admin"
            ))
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);


        $manager->flush();
    }
}
