<?php

namespace App\DataFixtures;

use App\Entity\Users\User;
use App\Tests\HelperApiTestCase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class UserFixtures extends Fixture
{

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail(HelperApiTestCase::$testUserEmail);
        $user->setPassword(HelperApiTestCase::$testUserEncodedPassword);

        $manager->persist($user);

        $user = new User();
        $user->setEmail('tester@email.loc');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$SgEKNtTWt3px/oLO8JF0ZQ$KvisNx3JZ6+jF4eFE/7i+YSuQt+fyfAhdX6y9KCZ6cc'); // test

        $manager->persist($user);

        $manager->flush();
    }

}
