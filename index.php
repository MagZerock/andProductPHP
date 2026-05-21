<?php

declare(strict_types=1);

use App\Andino\Products\Controller\ProductController;

require __DIR__ . '/vendor/autoload.php';

$controller = new ProductController();
$page = strtolower((string) ($_GET['page'] ?? 'products'));
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// Controller returns the HTML content for the main section
$content = $controller->handle($page, $method);

// Load the static index.html and inject the content
$indexHtml = file_get_contents(__DIR__ . '/src/view/index.html');
$output = str_replace('<!--APP_CONTENT-->', $content, $indexHtml);

echo $output;
