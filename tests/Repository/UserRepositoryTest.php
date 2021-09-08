<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
// plus pour les tests fonctionnels
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// plus pour tester les controllers 
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// Changement de KernelTestCase avec WebTestCase car liip/testFixturesBundle ne marche plus avec Kernel test Case 
class UserRepositoryTest extends WebTestCase {

    use FixturesTrait;

    public function testCount() {
        self::bootKernel();

        // Pour charger desFixtures classiques
        // $this->loadFixtures([UserFixtures::class]);

        // Pour charger les Fixtures depuis le bundle Alice avec les fichiers Yaml
        $users = $this->loadFixtureFiles([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        // ****ne marche plus avec liip testfixtures bundle tuto grafikart
        // $users = self::$container->get(UserRepository::class)->count([]);
        // ****remplacement par ceci : 
        $em = self::$container->get('doctrine.orm.entity_manager');
        $users = $em->getRepository(User::class)->count([]);

        $this->assertEquals(10, $users);
    }
}