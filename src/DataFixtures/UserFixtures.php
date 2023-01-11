<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param ValidatorInterface $validator
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
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

            if ($this->validator->validate($user)){
                $manager->persist($user);
            }



        }



        $manager->flush();
    }
}
