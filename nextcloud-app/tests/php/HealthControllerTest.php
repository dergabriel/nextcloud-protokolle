<?php

declare(strict_types=1);

namespace OCA\Protokolle\Tests;

use OCA\Protokolle\AppInfo\Application;
use OCA\Protokolle\Controller\HealthController;
use OCP\App\IAppManager;
use OCP\DB\IResult;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\ServerVersion;
use PHPUnit\Framework\TestCase;

final class HealthControllerTest extends TestCase {
    public function testCheckReturnsOkWithDatabaseConnection(): void {
        $request = $this->createMock(IRequest::class);
        $result = $this->createMock(IResult::class);

        $db = $this->createMock(IDBConnection::class);
        $db->expects(self::once())
            ->method('executeQuery')
            ->with('SELECT 1')
            ->willReturn($result);

        $appManager = $this->createMock(IAppManager::class);
        $appManager->expects(self::once())
            ->method('getAppVersion')
            ->with(Application::APP_ID, false)
            ->willReturn('0.0.1');

        $serverVersion = $this->createMock(ServerVersion::class);
        $serverVersion->expects(self::once())
            ->method('getVersionString')
            ->willReturn('32.0.0');

        $controller = new HealthController(
            Application::APP_ID,
            $request,
            $db,
            $appManager,
            $serverVersion,
        );

        $response = $controller->check();
        $data = $response->getData();

        self::assertSame(200, $response->getStatus());
        self::assertSame(Application::APP_ID, $data['app']);
        self::assertSame('0.0.1', $data['version']);
        self::assertSame('32.0.0', $data['nextcloudVersion']);
        self::assertSame('ok', $data['databaseConnection']);
    }
}
