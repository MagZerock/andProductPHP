<?php

declare(strict_types=1);

$products = $products ?? [];
$totalPrice = (float) ($totalPrice ?? 0);
$successMessage = (string) ($successMessage ?? '');

?>
<h1>Andino Products Management</h1>

<?php if ($successMessage !== ''): ?>
  <div class="alert alert-success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<h2>Products List</h2>
<table>
    <thead>
        <tr>
            <th>ID (Hex)</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Flavor</th>
            <th>Category</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td style="font-family: monospace; font-size: 0.9em; color: #7f8c8d;">
                    <?= htmlspecialchars((string) $product->getId(), ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td><?= htmlspecialchars((string) $product->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $product->getBrand(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $product->getFlavor(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $product->getCategory(), ENT_QUOTES, 'UTF-8') ?></td>
                <td>$ <?= number_format((float) ($product->getPrice() ?? 0), 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td colspan="5" style="text-align: right; color: #2c3e50;">Total Prices Sum:</td>
            <td style="color: #27ae60;">$ <?= number_format($totalPrice, 2) ?></td>
        </tr>
    </tbody>
</table>