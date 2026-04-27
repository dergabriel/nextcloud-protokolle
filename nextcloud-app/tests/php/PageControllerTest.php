<?php

declare(strict_types=1);

namespace OCP {
    interface IRequest {
    }
}

namespace OCP\AppFramework {
    use OCP\IRequest;

    class Controller {
        public function __construct(
            protected string $appName,
            protected IRequest $request,
        ) {
        }
    }
}

namespace OCP\AppFramework\Http {
    class JSONResponse {
        public function __construct(
            private array $data = [],
            private int $statusCode = 200,
        ) {
        }

        public function getData(): array {
            return $this->data;
        }

        public function getStatus(): int {
            return $this->statusCode;
        }
    }

    class TemplateResponse {
        public function __construct(
            private string $appName,
            private string $templateName,
        ) {
        }
    }
}

namespace OCA\Protokolle\Tests {
    use OCA\Protokolle\Controller\PageController;
    use OCP\IRequest;
    use PHPUnit\Framework\TestCase;

    require_once __DIR__ . '/../../vendor/autoload.php';

    final class PageControllerTest extends TestCase {
        public function testHelloReturnsExpectedPayload(): void {
            $request = $this->createMock(IRequest::class);
            $controller = new PageController('protokolle', $request);

            $response = $controller->hello();

            self::assertSame(200, $response->getStatus());
            self::assertSame([
                'status' => 'ok',
                'message' => 'Hallo aus Protokolle',
            ], $response->getData());
        }
    }
}
