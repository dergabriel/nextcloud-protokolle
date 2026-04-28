<?php

declare(strict_types=1);

namespace OCA\Protokolle\Tests\Php;

use OCA\Protokolle\Db\Gremium;
use OCA\Protokolle\Db\GremiumMapper;
use OCA\Protokolle\Exception\GremiumNameAlreadyExistsException;
use OCA\Protokolle\Service\GremiumService;
use PHPUnit\Framework\TestCase;

class GremiumServiceTest extends TestCase {
    private GremiumMapper $mapper;
    private GremiumService $service;

    protected function setUp(): void {
        $this->mapper = $this->createMock(GremiumMapper::class);
        $this->service = new GremiumService($this->mapper);
    }

    public function testCreateCreatesGremium(): void {
        $this->mapper->expects($this->once())
            ->method('findByName')
            ->with('AStA')
            ->willReturn(null);
        $this->mapper->expects($this->once())
            ->method('insert')
            ->willReturnCallback(static function (Gremium $gremium): Gremium {
                $gremium->setId(1);
                return $gremium;
            });

        $gremium = $this->service->create('AStA', 'AStA', 'Allgemeiner Studierendenausschuss');

        $this->assertSame(1, $gremium->getId());
        $this->assertSame('AStA', $gremium->getName());
        $this->assertSame('AStA', $gremium->getKuerzel());
        $this->assertSame('Allgemeiner Studierendenausschuss', $gremium->getBeschreibung());
        $this->assertNotNull($gremium->getCreatedAt());
    }

    public function testCreateRejectsDuplicateName(): void {
        $existing = new Gremium();
        $existing->setId(1);
        $existing->setName('AStA');

        $this->mapper->method('findByName')->with('AStA')->willReturn($existing);
        $this->mapper->expects($this->never())->method('insert');

        $this->expectException(GremiumNameAlreadyExistsException::class);

        $this->service->create('AStA', null, null);
    }

    public function testUpdateUpdatesExistingGremium(): void {
        $existing = new Gremium();
        $existing->setId(7);
        $existing->setName('AStA');

        $this->mapper->method('find')->with(7)->willReturn($existing);
        $this->mapper->method('findByName')->with('StuPa')->willReturn(null);
        $this->mapper->expects($this->once())
            ->method('update')
            ->willReturnCallback(static fn (Gremium $gremium): Gremium => $gremium);

        $updated = $this->service->update(7, 'StuPa', 'SP', 'Studierendenparlament');

        $this->assertSame(7, $updated->getId());
        $this->assertSame('StuPa', $updated->getName());
        $this->assertSame('SP', $updated->getKuerzel());
        $this->assertSame('Studierendenparlament', $updated->getBeschreibung());
    }

    public function testDeleteDeletesExistingGremium(): void {
        $existing = new Gremium();
        $existing->setId(7);

        $this->mapper->method('find')->with(7)->willReturn($existing);
        $this->mapper->expects($this->once())->method('delete')->with($existing);

        $this->service->delete(7);

        $this->assertSame(7, $existing->getId());
    }
}
