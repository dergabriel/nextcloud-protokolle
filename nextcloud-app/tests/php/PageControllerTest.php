<?php

declare(strict_types=1);

namespace OCA\Protokolle\Tests;

use OCA\Protokolle\AppInfo\Application;
use OCA\Protokolle\Controller\PageController;
use OCP\IRequest;
use PHPUnit\Framework\TestCase;

final class PageControllerTest extends TestCase {
    public function testHelloReturnsExpectedPayload(): void {
        $request = $this->createMock(IRequest::class);
        $controller = new PageController(Application::APP_ID, $request);

        $response = $controller->hello();

        self::assertSame(200, $response->getStatus());
        self::assertSame([
            'status' => 'ok',
            'message' => 'Hallo aus Protokolle',
        ], $response->getData());
    }
}
