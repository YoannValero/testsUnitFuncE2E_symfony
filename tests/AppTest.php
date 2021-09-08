<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

// plus pour les tests fonctionnels
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// plus pour tester les controllers 
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends TestCase {

    public function testTestsAreWorking() {
        $this->assertEquals(2, 1+1);
    }
}