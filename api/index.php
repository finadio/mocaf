<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (
    isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
    && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
) {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';
}

if (! empty($_SERVER['HTTP_HOST']) && empty($_ENV['APP_URL'])) {
    $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $appUrl = $scheme . '://' . $_SERVER['HTTP_HOST'];
    $_ENV['APP_URL'] = $appUrl;
    $_SERVER['APP_URL'] = $appUrl;
}

$storagePath = '/tmp/storage';
$requiredDirectories = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($requiredDirectories as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

if (! file_exists(__DIR__ . '/../bootstrap/cache/packages.php')) {
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
}

if (! file_exists(__DIR__ . '/../bootstrap/cache/services.php')) {
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
}

require __DIR__ . '/../vendor/autoload.php';

$dbConnection = $_ENV['DB_CONNECTION'] ?? $_SERVER['DB_CONNECTION'] ?? 'sqlite';
$dbPath = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? '/tmp/database.sqlite';
$dbReadyMarker = '/tmp/.moodles-db-ready';

if (
    $dbConnection === 'sqlite'
    && (! file_exists($dbPath) || filesize($dbPath) < 4096 || ! file_exists($dbReadyMarker))
) {
    if (! file_exists($dbPath)) {
        touch($dbPath);
    }

    /** @var Application $consoleApp */
    $consoleApp = require __DIR__ . '/../bootstrap/app.php';
    $consoleApp->useStoragePath($storagePath);

    /** @var ConsoleKernel $consoleKernel */
    $consoleKernel = $consoleApp->make(ConsoleKernel::class);
    $consoleKernel->call('migrate:fresh', [
        '--seed' => true,
        '--force' => true,
    ]);

    file_put_contents($dbReadyMarker, '1');
}

/** @var Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());

