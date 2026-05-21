<?php

declare(strict_types=1);

$uri = getenv('MONGODB_URI')
    ?: getenv('SPRING_DATA_MONGODB_URI')
    ?: 'mongodb+srv://admin:admin@awd.ypmipjt.mongodb.net/Products?retryWrites=true&w=majority';

return [
    'uri' => $uri,
    'database' => getenv('MONGODB_DATABASE') ?: 'Products',
    'collection' => getenv('MONGODB_COLLECTION') ?: 'products',
];
