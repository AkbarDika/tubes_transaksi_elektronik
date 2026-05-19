#!/usr/bin/env php
<?php
/**
 * Test Midtrans Snap Token Generation
 * Jalankan: php test_midtrans.php
 */

require __DIR__ . '/vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$env = $_ENV;

echo "\n=== TEST MIDTRANS CONFIGURATION ===\n\n";

// Config
Config::$serverKey = $env['MIDTRANS_SERVER_KEY'] ?? '';
Config::$clientKey = $env['MIDTRANS_CLIENT_KEY'] ?? '';
Config::$isProduction = ($env['MIDTRANS_IS_PRODUCTION'] ?? 'false') === 'true';
Config::$isSanitized = true;
Config::$is3ds = true;

// Display config
echo "Server Key: " . (empty(Config::$serverKey) ? "❌ NOT SET" : "✅ " . substr(Config::$serverKey, 0, 20) . "...") . "\n";
echo "Client Key: " . (empty(Config::$clientKey) ? "❌ NOT SET" : "✅ " . substr(Config::$clientKey, 0, 20) . "...") . "\n";
echo "Production: " . (Config::$isProduction ? "Yes" : "No (Sandbox)") . "\n\n";

if (empty(Config::$serverKey) || empty(Config::$clientKey)) {
    echo "❌ FAILED: Missing Midtrans credentials in .env\n";
    exit(1);
}

// Test token generation
$params = [
    'transaction_details' => [
        'order_id' => 'TEST-' . time(),
        'gross_amount' => 100000,
    ],
    'customer_details' => [
        'first_name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '08123456789',
    ],
    'item_details' => [
        [
            'id' => '1',
            'price' => 100000,
            'quantity' => 1,
            'name' => 'Test Item',
        ],
    ],
];

echo "Testing Snap Token Generation...\n";
echo "Order ID: " . $params['transaction_details']['order_id'] . "\n";
echo "Amount: Rp " . number_format($params['transaction_details']['gross_amount'], 0, ',', '.') . "\n\n";

try {
    $snapToken = Snap::getSnapToken($params);
    
    echo "✅ SUCCESS! Snap Token Generated:\n";
    echo "Token: " . substr($snapToken, 0, 50) . "...\n\n";
    echo "Use this token to initialize Midtrans payment:\n";
    echo "snap.pay('" . $snapToken . "');\n";
    
    exit(0);
} catch (\Exception $e) {
    echo "❌ FAILED: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "Class: " . get_class($e) . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
