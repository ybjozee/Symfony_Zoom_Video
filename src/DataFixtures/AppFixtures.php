<?php

namespace App\DataFixtures;

use App\Entity\{Room, User};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager)
    : void {

        $user = new User('test@test.com');
        $user->setPassword($this->hasher->hashPassword($user, 'test1234'));
        $manager->persist($user);

        $this->addRooms($user, $manager);
        $this->addUsers($manager);

        $manager->flush();
    }

    private function addRooms(User $owner, ObjectManager $manager)
    : void {

        for ($i = 1; $i <= 5; $i++) {
            $room = new Room("Demo Room $i", $owner);
            $manager->persist($room);
        }
    }

    private function addUsers(ObjectManager $manager)
    : void {

        for ($i = 1; $i <= 5; $i++) {
            $user = new User("test$i@test.com");
            $user->setPassword($this->hasher->hashPassword($user, 'test1234'));
            $manager->persist($user);
        }
    }
}
