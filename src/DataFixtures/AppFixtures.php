<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture{
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
                ->setEmail('admin@gmail.com')
                ->setFname('Jhon')
                ->setPhoneExt('+212')
                ->setPhone('123456789')
                ->setLname('Doe')
                ->setRoles(['ROLE_USER'])
        ;
        $user->setPassword($this->userPasswordHasher->hashPassword($user, '123'));
        $manager->persist($user);
        
        $manager->persist(
            (new Course())
                ->setTitle('Arabic Language course')
                ->setPrice(10)
                ->setImagePath('/images/courses/arabic.png')
                ->setCreator($user)
        );
        $manager->persist(
            (new Course())
                ->setTitle('French Language Course')
                ->setPrice(100)
                ->setImagePath('/images/courses/french.png')
                ->setCreator($user)
        );
        $manager->persist(
            (new Course())
                ->setTitle('Chinese Language course')
                ->setPrice(10)
                ->setImagePath('/images/courses/chinese.png')
                ->setCreator($user)
        );
        $manager->persist(
            (new Course())
                ->setTitle('English Language Course')
                ->setPrice(100)
                ->setImagePath('/images/courses/english.png')
                ->setCreator($user)
        );
        $manager->persist(
            (new Course())
                ->setTitle('Spanish Language course')
                ->setPrice(10)
                ->setImagePath('/images/courses/spanish.png')
                ->setCreator($user)
        );
        $manager->persist(
            (new Course())
                ->setTitle('German Language Course')
                ->setPrice(100)
                ->setImagePath('/images/courses/german.png')
                ->setCreator($user)
        );

        $manager->flush();
    }
}
