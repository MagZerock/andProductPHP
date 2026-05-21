<?php

declare(strict_types=1);

use App\Andino\Products\Controller\ProductController;

require __DIR__ . '/vendor/autoload.php';

$controller = new ProductController();
$page = strtolower((string) ($_GET['page'] ?? 'products'));
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

$content = $controller->handle($page, $method);

$indexHtml = file_get_contents(__DIR__ . '/src/view/index.html');
$output = str_replace('<!--APP_CONTENT-->', $content, $indexHtml);

echo $output;
