<?php

declare(strict_types=1);

namespace OCP {
    interface IRequest {
    }

    interface IDBConnection {
        public function executeQuery(string $sql, array $params = [], $types = []): \OCP\DB\IResult;
    }

    class ServerVersion {
        public function getVersionString(): string {
            return '32.0.0';
        }
    }
}

namespace OCP\App {
    interface IAppManager {
        public function getAppVersion(string $appId, bool $useCache = true): string;
    }
}

namespace OCP\DB {
    interface IResult {
    }
}

namespace OCP\AppFramework {
    use OCP\IRequest;

    class Http {
        public const STATUS_OK = 200;
        public const STATUS_INTERNAL_SERVER_ERROR = 500;
    }

    class App {
        public function __construct(
            protected string $appName,
        ) {
        }
    }

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

namespace {
    require_once __DIR__ . '/../../vendor/autoload.php';
}
