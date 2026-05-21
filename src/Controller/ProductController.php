<?php

declare(strict_types=1);

namespace App\Andino\Products\Controller;

use App\Andino\Products\Model\Product;

final class ProductController
{
    private const CATEGORIES = ['Beverages', 'Snacks', 'Dairy', 'Candies', 'Bakery'];

    public function handle(string $page, string $method): string
    {
        if ($method === 'POST' && $page === 'add') {
            return $this->store();
        }

        if ($page === 'add') {
            return $this->showAddForm();
        }

        return $this->showProducts();
    }

    private function showProducts(array $messages = []): string
    {
        $products = Product::all();
        $totalPrice = Product::sumPrices($products);
        $template = file_get_contents(__DIR__ . '/../view/products.html');

        $rows = '';
        foreach ($products as $product) {
            $rows .= '<tr>' .
                '<td style="font-family: monospace; font-size: 0.9em; color: #7f8c8d;">' . htmlspecialchars((string) $product->getId(), ENT_QUOTES, 'UTF-8') . '</td>' .
                '<td>' . htmlspecialchars((string) $product->getName(), ENT_QUOTES, 'UTF-8') . '</td>' .
                '<td>' . htmlspecialchars((string) $product->getBrand(), ENT_QUOTES, 'UTF-8') . '</td>' .
                '<td>' . htmlspecialchars((string) $product->getFlavor(), ENT_QUOTES, 'UTF-8') . '</td>' .
                '<td>' . htmlspecialchars((string) $product->getCategory(), ENT_QUOTES, 'UTF-8') . '</td>' .
                '<td>$ ' . number_format((float) ($product->getPrice() ?? 0), 2) . '</td>' .
                '</tr>';
        }

        $messagesHtml = '';
        if (!empty($messages['success'])) {
            $messagesHtml = '<div class="alert alert-success">' . htmlspecialchars((string) $messages['success'], ENT_QUOTES, 'UTF-8') . '</div>';
        }

        $output = str_replace(['{{products_rows}}', '{{total_price}}', '{{messages}}'], [$rows, number_format((float) $totalPrice, 2), $messagesHtml], $template);

        return $output;
    }

    private function showAddForm(array $oldInput = [], array $errors = [], string $message = ''): string
    {
        $template = file_get_contents(__DIR__ . '/../view/add-product.html');

        $categoriesOptions = '';
        foreach (self::CATEGORIES as $cat) {
            $selected = ($oldInput['category'] ?? '') === $cat ? ' selected' : '';
            $categoriesOptions .= '<option value="' . htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') . '</option>';
        }

        $replacements = [
            '{{name}}' => htmlspecialchars((string) ($oldInput['name'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{brand}}' => htmlspecialchars((string) ($oldInput['brand'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{flavor}}' => htmlspecialchars((string) ($oldInput['flavor'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{price}}' => htmlspecialchars((string) ($oldInput['price'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{categories_options}}' => $categoriesOptions,
            '{{category_empty}}' => empty($oldInput['category']) ? 'selected' : '',
            '{{error_name}}' => !empty($errors['name']) ? '<span class="field-error">' . htmlspecialchars((string) $errors['name'], ENT_QUOTES, 'UTF-8') . '</span>' : '',
            '{{error_brand}}' => !empty($errors['brand']) ? '<span class="field-error">' . htmlspecialchars((string) $errors['brand'], ENT_QUOTES, 'UTF-8') . '</span>' : '',
            '{{error_category}}' => !empty($errors['category']) ? '<span class="field-error">' . htmlspecialchars((string) $errors['category'], ENT_QUOTES, 'UTF-8') . '</span>' : '',
            '{{error_price}}' => !empty($errors['price']) ? '<span class="field-error">' . htmlspecialchars((string) $errors['price'], ENT_QUOTES, 'UTF-8') . '</span>' : '',
            '{{message}}' => $message !== '' ? '<div class="alert alert-error">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>' : '',
        ];

        $output = strtr($template, $replacements);

        return $output;
    }

    private function store(): string
    {
        $input = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'brand' => trim((string) ($_POST['brand'] ?? '')),
            'flavor' => trim((string) ($_POST['flavor'] ?? '')),
            'category' => trim((string) ($_POST['category'] ?? '')),
            'price' => trim((string) ($_POST['price'] ?? '')),
        ];

        $errors = $this->validate($input);

        if ($errors !== []) {
            return $this->showAddForm($input, $errors, 'Fix the highlighted fields and try again.');
        }

        $product = Product::fromArray($input);
        $product->save();

        // After saving, show products with success message
        return $this->showProducts(['success' => 'Product saved successfully.']);
    }

    private function validate(array $input): array
    {
        $errors = [];

        if ($input['name'] === '') {
            $errors['name'] = 'Product name is required.';
        }

        if ($input['brand'] === '') {
            $errors['brand'] = 'Brand is required.';
        }

        if (!in_array($input['category'], self::CATEGORIES, true)) {
            $errors['category'] = 'Please select a valid category.';
        }

        if ($input['price'] === '' || !is_numeric($input['price']) || (float) $input['price'] < 0) {
            $errors['price'] = 'Price must be a valid number greater than or equal to 0.';
        }

        return $errors;
    }

    private function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../view/' . $view;
        $layoutFile = __DIR__ . '/../view/layouts/main.php';

        extract($data, EXTR_SKIP);

        if (isset($_GET['saved']) && $_GET['saved'] === '1' && empty($messages)) {
            $messages = ['success' => 'Product saved successfully.'];
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }
}
