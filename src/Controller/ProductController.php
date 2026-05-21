<?php

declare(strict_types=1);

namespace App\Andino\Products\Controller;

use App\Andino\Products\Model\Product;

final class ProductController
{
    private const CATEGORIES = ['Beverages', 'Snacks', 'Dairy', 'Candies', 'Bakery'];

    public function handle(string $page, string $method): array
    {
        if ($method === 'POST' && $page === 'add') {
            return $this->store();
        }

        if ($page === 'add') {
            return $this->showAddForm();
        }

        return $this->showProducts();
    }

    private function showProducts(array $messages = []): array
    {
        $products = Product::all();
        $totalPrice = Product::sumPrices($products);

        return [
            'view' => 'products',
            'data' => [
                'pageTitle' => 'Andino Products | Products',
                'currentPage' => 'products',
                'products' => $products,
                'totalPrice' => $totalPrice,
                'successMessage' => (string) ($messages['success'] ?? ''),
            ],
        ];
    }

    private function showAddForm(array $oldInput = [], array $errors = [], string $message = ''): array
    {
        return [
            'view' => 'add-product',
            'data' => [
                'pageTitle' => 'Andino Products | Add Product',
                'currentPage' => 'add',
                'categories' => self::CATEGORIES,
                'oldInput' => $oldInput,
                'errors' => $errors,
                'message' => $message,
            ],
        ];
    }

    private function store(): array
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
}
