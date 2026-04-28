<?php

declare(strict_types=1);

namespace OCP {
    interface IUser {
        public function getDisplayName(): string;
    }

    interface IUserManager {
        public function get(string $uid): ?IUser;
    }

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
    class Exception extends \RuntimeException {
    }

    interface IResult {
    }

    class Types {
        public const STRING = 'string';
        public const TEXT = 'text';
        public const INTEGER = 'integer';
        public const BOOLEAN = 'boolean';
        public const DATETIME = 'datetime';
    }
}

namespace OCP\AppFramework\Db {
    class DoesNotExistException extends \Exception {
    }

    class MultipleObjectsReturnedException extends \Exception {
    }

    class Entity {
        protected ?int $id = null;

        protected function addType(string $fieldName, string $type): void {
        }

        public function getId(): ?int {
            return $this->id;
        }

        public function setId(int $id): void {
            $this->id = $id;
        }

        public function __call(string $name, array $arguments): mixed {
            if (str_starts_with($name, 'get')) {
                $property = lcfirst(substr($name, 3));
                return $this->$property;
            }

            if (str_starts_with($name, 'set')) {
                $property = lcfirst(substr($name, 3));
                $this->$property = $arguments[0] ?? null;
                return null;
            }

            throw new \BadMethodCallException($name);
        }
    }

    class QBMapper {
        public function __construct(mixed ...$args) {
        }

        public function insert(Entity $entity): Entity {
            return $entity;
        }

        public function update(Entity $entity): Entity {
            return $entity;
        }

        public function delete(Entity $entity): Entity {
            return $entity;
        }
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
