<?php

declare(strict_types=1);

use App\Andino\Products\Controller\ProductController;

require __DIR__ . '/vendor/autoload.php';

$controller = new ProductController();
$page = strtolower((string) ($_GET['page'] ?? 'products'));
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$response = $controller->handle($page, $method);

$view = __DIR__ . '/src/view/' . $response['view'] . '.php';
$data = $response['data'] ?? [];

$documentRoot = str_replace('\\', '/', realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? '')) ?: '');
$assetBase = str_contains($documentRoot, '/public') ? '' : '/public';

extract($data, EXTR_SKIP);

ob_start();
require $view;
$content = ob_get_clean();

require __DIR__ . '/src/view/layout.php';
