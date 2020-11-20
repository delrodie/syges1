<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $role[] = 'ROLE_SUPER_ADMIN';
        $user = new User();
        $user->setUsername('delrodie');
        $user->setRoles($role);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'delroy@2020'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
