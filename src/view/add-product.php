<?php

declare(strict_types=1);

$categories = $categories ?? [];
$oldInput = $oldInput ?? [];
$errors = $errors ?? [];
$message = (string) ($message ?? '');

?>
<h1>Andino Products Management</h1>

<?php if ($message !== ''): ?>
  <div class="alert alert-error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<h2>Add New Product</h2>
<form data-product-form action="index.php?page=add" method="post" novalidate>
    <div class="form-row">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars((string) ($oldInput['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Ex. Cookie" required>
            <?php if (!empty($errors['name'])): ?>
                <span class="field-error"><?= htmlspecialchars((string) $errors['name'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" value="<?= htmlspecialchars((string) ($oldInput['brand'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Ex. Havanna" required>
            <?php if (!empty($errors['brand'])): ?>
                <span class="field-error"><?= htmlspecialchars((string) $errors['brand'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="flavor">Flavor:</label>
            <input type="text" id="flavor" name="flavor" value="<?= htmlspecialchars((string) ($oldInput['flavor'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Ex. Chocolate">
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="" disabled <?= empty($oldInput['category']) ? 'selected' : '' ?>>Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars((string) $category, ENT_QUOTES, 'UTF-8') ?>" <?= ($oldInput['category'] ?? '') === $category ? 'selected' : '' ?>>
                        <?= htmlspecialchars((string) $category, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['category'])): ?>
                <span class="field-error"><?= htmlspecialchars((string) $errors['category'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <label for="price">Price ($):</label>
        <input type="number" step="0.01" min="0" id="price" name="price" value="<?= htmlspecialchars((string) ($oldInput['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="0.00" required>
        <?php if (!empty($errors['price'])): ?>
            <span class="field-error"><?= htmlspecialchars((string) $errors['price'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
    </div>

    <button type="submit">Save Product</button>
</form>