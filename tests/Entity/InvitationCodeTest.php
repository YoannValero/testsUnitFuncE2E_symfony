<?php

namespace App\Tests\Entity;

use App\Entity\InvitationCode;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvitationCodeTest extends WebTestCase
{

    use FixturesTrait;

    public function getEntity(): InvitationCode {
        return (new InvitationCode())
        ->setCode('12346')
        ->setDescription('Description de test')
        ->setExpireAt(new \DateTime())
        ;
    }
    public function assertHasErrors(InvitationCode $code, int $number = 0) {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($code);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' ==> ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity() {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidCodeEntity() {
        $this->assertHasErrors($this->getEntity()->setCode('1a345'), 1);
        $this->assertHasErrors($this->getEntity()->setCode('123'), 1);
        $this->assertHasErrors($this->getEntity()->setCode(''), 1);
    }

    public function testInvalidBlankcodeEntity() {
        $this->assertHasErrors($this->getEntity()->setCode(''), 1);
    }

    public function testInvalidBlankDescriptionEntity() {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }

    public function testInvalidUsedCodeExist() {
        // permet de simuler un objet qui a déjà eu le code 54321
        $this->loadFixtureFiles([dirname(__DIR__) . '/fixtures/invitation_codes.yaml']);
        $this->assertHasErrors($this->getEntity()->setCode('54321'), 1);
    }
}