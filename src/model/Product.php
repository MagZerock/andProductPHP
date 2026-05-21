<?php

declare(strict_types=1);

namespace App\Andino\Products\Model;

final class Product
{
    private static $collection = null;

    private ?string $id = null;
    private string $name = '';
    private string $brand = '';
    private string $flavor = '';
    private string $category = '';
    private ?float $price = null;

    public static function all(): array
    {
        $cursor = self::collection()->find([], ['sort' => ['_id' => -1]]);
        $products = [];

        foreach ($cursor as $document) {
            $products[] = self::fromDocument($document);
        }

        return $products;
    }

    public static function sumPrices(array $products): float
    {
        $total = 0.0;

        foreach ($products as $product) {
            if ($product instanceof self && $product->price !== null) {
                $total += $product->price;
            }
        }

        return $total;
    }

    public static function fromArray(array $data): self
    {
        $product = new self();
        $product->setName((string) ($data['name'] ?? ''));
        $product->setBrand((string) ($data['brand'] ?? ''));
        $product->setFlavor((string) ($data['flavor'] ?? ''));
        $product->setCategory((string) ($data['category'] ?? ''));
        $product->setPrice((float) ($data['price'] ?? 0));

        return $product;
    }

    public static function fromDocument(object|array $document): self
    {
        $payload = (array) $document;
        $product = new self();

        $product->setId(isset($payload['_id']) ? (string) $payload['_id'] : null);

        $product->setName((string) ($payload['name'] ?? ''));
        $product->setBrand((string) ($payload['brand'] ?? ''));
        $product->setFlavor((string) ($payload['flavor'] ?? ''));
        $product->setCategory((string) ($payload['category'] ?? ''));
        $product->setPrice(isset($payload['price']) ? (float) $payload['price'] : null);

        return $product;
    }

    public function save(): void
    {
        $payload = $this->toMongoDocument();

        if ($this->id === null) {
            $this->id = bin2hex(random_bytes(12));
            $payload['_id'] = $this->id;

            $result = self::collection()->insertOne($payload);

            return;
        }

        self::collection()->updateOne(
            ['_id' => $this->id],
            ['$set' => $payload]
        );
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getFlavor(): string
    {
        return $this->flavor;
    }

    public function setFlavor(string $flavor): void
    {
        $this->flavor = $flavor;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    private function toMongoDocument(): array
    {
        return [
            'name' => $this->name,
            'brand' => $this->brand,
            'flavor' => $this->flavor,
            'category' => $this->category,
            'price' => $this->price,
        ];
    }

    private static function collection()
    {
        if (self::$collection !== null) {
            return self::$collection;
        }

        $config = require __DIR__ . '/../../config/mongodb.php';
        $clientClass = '\\MongoDB\\Client';
        $client = new $clientClass((string) $config['uri']);

        if (!is_object($client)) {
            throw new \RuntimeException('MongoDB client configuration is invalid.');
        }

        self::$collection = $client
            ->selectDatabase((string) $config['database'])
            ->selectCollection((string) $config['collection']);

        return self::$collection;
    }
}
