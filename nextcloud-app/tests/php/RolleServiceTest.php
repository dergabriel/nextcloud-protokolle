<?php

declare(strict_types=1);

namespace OCA\Protokolle\Tests\Php;

use OCA\Protokolle\Db\Rolle;
use OCA\Protokolle\Db\RolleMapper;
use OCA\Protokolle\Exception\RolleNameInGremiumAlreadyExistsException;
use OCA\Protokolle\Service\RolleService;
use PHPUnit\Framework\TestCase;

class RolleServiceTest extends TestCase {
    private RolleMapper $mapper;
    private RolleService $service;

    protected function setUp(): void {
        $this->mapper = $this->createMock(RolleMapper::class);
        $this->service = new RolleService($this->mapper);
    }

    public function testCreateCreatesRolle(): void {
        $this->mapper->method('findByGremiumAndName')->with(1, 'Referent:in')->willReturn(null);
        $this->mapper->expects($this->once())
            ->method('insert')
            ->willReturnCallback(static function (Rolle $rolle): Rolle {
                $rolle->setId(2);
                return $rolle;
            });

        $rolle = $this->service->create(1, 'Referent:in', true, 'AStA-Referat');

        $this->assertSame(2, $rolle->getId());
        $this->assertSame(1, $rolle->getGremiumId());
        $this->assertSame('Referent:in', $rolle->getName());
        $this->assertTrue($rolle->getStimmberechtigtDefault());
        $this->assertSame('AStA-Referat', $rolle->getBeschreibung());
    }

    public function testCreateRejectsDuplicateNameInSameGremium(): void {
        $existing = new Rolle();
        $existing->setId(2);
        $existing->setGremiumId(1);
        $existing->setName('Referent:in');

        $this->mapper->method('findByGremiumAndName')->with(1, 'Referent:in')->willReturn($existing);
        $this->mapper->expects($this->never())->method('insert');

        $this->expectException(RolleNameInGremiumAlreadyExistsException::class);

        $this->service->create(1, 'Referent:in', true, null);
    }

    public function testSameNameInDifferentGremiumIsAllowed(): void {
        $this->mapper->method('findByGremiumAndName')->with(2, 'Referent:in')->willReturn(null);
        $this->mapper->expects($this->once())
            ->method('insert')
            ->willReturnCallback(static function (Rolle $rolle): Rolle {
                $rolle->setId(3);
                return $rolle;
            });

        $rolle = $this->service->create(2, 'Referent:in', false, null);

        $this->assertSame(3, $rolle->getId());
        $this->assertSame(2, $rolle->getGremiumId());
        $this->assertSame('Referent:in', $rolle->getName());
        $this->assertFalse($rolle->getStimmberechtigtDefault());
    }

    public function testUpdateKeepsNameUniqueInGremium(): void {
        $rolle = new Rolle();
        $rolle->setId(4);
        $rolle->setGremiumId(1);
        $rolle->setName('Alt');

        $this->mapper->method('find')->with(4)->willReturn($rolle);
        $this->mapper->method('findByGremiumAndName')->with(1, 'Neu')->willReturn(null);
        $this->mapper->expects($this->once())->method('update')->willReturnCallback(static fn (Rolle $rolle): Rolle => $rolle);

        $updated = $this->service->update(4, 'Neu', true, 'Beschreibung');

        $this->assertSame(4, $updated->getId());
        $this->assertSame('Neu', $updated->getName());
        $this->assertTrue($updated->getStimmberechtigtDefault());
        $this->assertSame('Beschreibung', $updated->getBeschreibung());
    }
}
