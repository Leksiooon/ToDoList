<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$name, $last_name, $email, $password, $roles])
        {
            $user = new User();
            $user->setName($name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPassword($this->password_encoder->encodePassword($user,$password));
            $user->setRoles($roles);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData():array
    {
        return [
            ['John', 'Wayne', 'jw@symf.loc', 'passw', ['ROLE_ADMIN']],
            ['John2', 'Wayne2', 'jw@symf2.loc', 'passw2', ['ROLE_ADMIN']],
            ['John3', 'Wayne3', 'jw@symf3.loc', 'passw3', ['ROLE_USER']],
        ];
    }
}
