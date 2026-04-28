<?php

declare(strict_types=1);

namespace OCA\Protokolle\Controller;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use OCA\Protokolle\AppInfo\Application;
use OCP\App\IAppManager;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\ServerVersion;
use Throwable;

class HealthController extends Controller {
    public function __construct(
        string $appName,
        IRequest $request,
        private IDBConnection $db,
        private IAppManager $appManager,
        private ServerVersion $serverVersion,
    ) {
        parent::__construct($appName, $request);
    }

    /**
     * @NoCSRFRequired
     */
    public function check(): JSONResponse {
        $statusCode = Http::STATUS_OK;
        $databaseConnection = 'ok';

        try {
            $this->db->executeQuery('SELECT 1');
        } catch (Throwable $exception) {
            $statusCode = Http::STATUS_INTERNAL_SERVER_ERROR;
            $databaseConnection = 'fehler: ' . $exception->getMessage();
        }

        return new JSONResponse([
            'app' => Application::APP_ID,
            'version' => $this->appManager->getAppVersion(Application::APP_ID, false),
            'phpVersion' => PHP_VERSION,
            'nextcloudVersion' => $this->serverVersion->getVersionString(),
            'databaseConnection' => $databaseConnection,
            'timestamp' => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DateTimeInterface::ATOM),
        ], $statusCode);
    }
}
