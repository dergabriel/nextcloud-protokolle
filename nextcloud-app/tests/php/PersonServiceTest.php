<?php

declare(strict_types=1);

namespace OCA\Protokolle\Tests\Php;

use OCA\Protokolle\Db\Person;
use OCA\Protokolle\Db\PersonMapper;
use OCA\Protokolle\Exception\InvalidPersonDataException;
use OCA\Protokolle\Exception\PersonAlreadyExistsException;
use OCA\Protokolle\Service\PersonService;
use OCP\IUser;
use OCP\IUserManager;
use PHPUnit\Framework\TestCase;

class PersonServiceTest extends TestCase {
    private PersonMapper $mapper;
    private IUserManager $userManager;
    private PersonService $service;

    protected function setUp(): void {
        $this->mapper = $this->createMock(PersonMapper::class);
        $this->userManager = $this->createMock(IUserManager::class);
        $this->service = new PersonService($this->mapper, $this->userManager);
    }

    public function testCreateExternCreatesExternalPerson(): void {
        $this->mapper->expects($this->once())
            ->method('insert')
            ->willReturnCallback(static function (Person $person): Person {
                $person->setId(1);
                return $person;
            });

        $person = $this->service->createExtern('Misha', 'Muster', 'misha@example.org', 'Gast');

        $this->assertSame(1, $person->getId());
        $this->assertTrue($person->getExtern());
        $this->assertNull($person->getNextcloudUserId());
        $this->assertSame('Misha', $person->getVorname());
        $this->assertSame('Muster', $person->getNachname());
        $this->assertSame('Misha Muster', $person->getAnzeigename());
    }

    public function testCreateExternRejectsEmptyVorname(): void {
        $this->mapper->expects($this->never())->method('insert');

        $this->expectException(InvalidPersonDataException::class);

        $this->service->createExtern('', 'Muster', null, null);
    }

    public function testCreateFromNextcloudUserRejectsDuplicate(): void {
        $existing = new Person();
        $existing->setId(1);
        $existing->setNextcloudUserId('gabriel');

        $this->mapper->method('findByNextcloudUserId')->with('gabriel')->willReturn($existing);
        $this->userManager->expects($this->never())->method('get');
        $this->mapper->expects($this->never())->method('insert');

        $this->expectException(PersonAlreadyExistsException::class);

        $this->service->createFromNextcloudUser('gabriel');
    }

    public function testCreateFromNextcloudUserCreatesPerson(): void {
        $user = $this->createMock(IUser::class);

        $this->mapper->method('findByNextcloudUserId')->with('gabriel')->willReturn(null);
        $this->userManager->method('get')->with('gabriel')->willReturn($user);
        $this->mapper->expects($this->once())
            ->method('insert')
            ->willReturnCallback(static function (Person $person): Person {
                $person->setId(2);
                return $person;
            });

        $person = $this->service->createFromNextcloudUser('gabriel');

        $this->assertSame(2, $person->getId());
        $this->assertFalse($person->getExtern());
        $this->assertSame('gabriel', $person->getNextcloudUserId());
        $this->assertNull($person->getVorname());
        $this->assertNull($person->getNachname());
    }

    public function testGetAnzeigenameUsesDisplayNameForNextcloudUser(): void {
        $person = new Person();
        $person->setExtern(false);
        $person->setNextcloudUserId('gabriel');
        $user = $this->createMock(IUser::class);
        $user->method('getDisplayName')->willReturn('Gabriel Becker');

        $this->userManager->method('get')->with('gabriel')->willReturn($user);

        $this->assertSame('Gabriel Becker', $this->service->getAnzeigename($person));
    }
}
