<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRODUCTS ===\n";
$products = DB::table('products')->select('id', 'name', 'slug')->get();
foreach ($products as $p) {
    echo "{$p->id} | {$p->slug} | {$p->name}\n";
}

echo "\n=== PRODUCT IMAGES ===\n";
$images = DB::table('product_images')->select('id', 'product_id', 'image')->get();
foreach ($images as $i) {
    echo "{$i->id} | Product ID: {$i->product_id} | " . substr($i->image, 0, 60) . "...\n";
}
