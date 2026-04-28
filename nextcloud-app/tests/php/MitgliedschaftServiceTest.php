<?php

declare(strict_types=1);

namespace OCA\Protokolle\Tests\Php;

use OCA\Protokolle\Db\Mitgliedschaft;
use OCA\Protokolle\Db\MitgliedschaftMapper;
use OCA\Protokolle\Db\Rolle;
use OCA\Protokolle\Db\RolleMapper;
use OCA\Protokolle\Exception\MitgliedschaftAlreadyExistsException;
use OCA\Protokolle\Service\MitgliedschaftService;
use PHPUnit\Framework\TestCase;

class MitgliedschaftServiceTest extends TestCase {
    private MitgliedschaftMapper $mitgliedschaftMapper;
    private RolleMapper $rolleMapper;
    private MitgliedschaftService $service;

    protected function setUp(): void {
        $this->mitgliedschaftMapper = $this->createMock(MitgliedschaftMapper::class);
        $this->rolleMapper = $this->createMock(RolleMapper::class);
        $this->service = new MitgliedschaftService($this->mitgliedschaftMapper, $this->rolleMapper);
    }

    public function testCreateCreatesMitgliedschaft(): void {
        $this->mitgliedschaftMapper->method('findByPerson')->with(1)->willReturn([]);
        $this->mitgliedschaftMapper->expects($this->once())
            ->method('insert')
            ->willReturnCallback(static function (Mitgliedschaft $mitgliedschaft): Mitgliedschaft {
                $mitgliedschaft->setId(5);
                return $mitgliedschaft;
            });

        $mitgliedschaft = $this->service->create(1, 2, null);

        $this->assertSame(5, $mitgliedschaft->getId());
        $this->assertSame(1, $mitgliedschaft->getPersonId());
        $this->assertSame(2, $mitgliedschaft->getRolleId());
        $this->assertNull($mitgliedschaft->getStimmberechtigtOverride());
        $this->assertNotNull($mitgliedschaft->getCreatedAt());
    }

    public function testCreateRejectsDuplicateMitgliedschaft(): void {
        $existing = new Mitgliedschaft();
        $existing->setId(5);
        $existing->setPersonId(1);
        $existing->setRolleId(2);

        $this->mitgliedschaftMapper->method('findByPerson')->with(1)->willReturn([$existing]);
        $this->mitgliedschaftMapper->expects($this->never())->method('insert');

        $this->expectException(MitgliedschaftAlreadyExistsException::class);

        $this->service->create(1, 2, null);
    }

    public function testIstStimmberechtigtUsesOverride(): void {
        $mitgliedschaft = new Mitgliedschaft();
        $mitgliedschaft->setRolleId(2);
        $mitgliedschaft->setStimmberechtigtOverride(false);

        $this->rolleMapper->expects($this->never())->method('find');

        $this->assertFalse($this->service->istStimmberechtigt($mitgliedschaft));

        $mitgliedschaft->setStimmberechtigtOverride(true);

        $this->assertTrue($this->service->istStimmberechtigt($mitgliedschaft));
    }

    public function testIstStimmberechtigtUsesRolleDefault(): void {
        $mitgliedschaft = new Mitgliedschaft();
        $mitgliedschaft->setRolleId(2);
        $mitgliedschaft->setStimmberechtigtOverride(null);
        $rolle = new Rolle();
        $rolle->setId(2);
        $rolle->setStimmberechtigtDefault(true);

        $this->rolleMapper->method('find')->with(2)->willReturn($rolle);

        $this->assertTrue($this->service->istStimmberechtigt($mitgliedschaft));
        $this->assertSame(2, $mitgliedschaft->getRolleId());
        $this->assertNull($mitgliedschaft->getStimmberechtigtOverride());
    }
}
