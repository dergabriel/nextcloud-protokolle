<?php

declare(strict_types=1);

namespace OCA\Protokolle\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;

class PageController extends Controller {
    public function __construct(
        string $appName,
        IRequest $request,
    ) {
        parent::__construct($appName, $request);
    }

    /**
     * @NoCSRFRequired
     */
    public function index(): TemplateResponse {
        return new TemplateResponse('protokolle', 'main');
    }

    /**
     * @NoCSRFRequired
     */
    public function hello(): JSONResponse {
        return new JSONResponse([
            'status' => 'ok',
            'message' => 'Hallo aus Protokolle',
        ]);
    }
}
