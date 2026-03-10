<?php
require_once __DIR__ . '/../vendor/autoload.php'; // For vlucas/phpdotenv

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);

define('ROOT_URL', $_ENV['ROOT_URL']);
define('BASE_URL', $_ENV['BASE_URL']);
define('SITE_URL', $_ENV['SITE_URL']);
define('ADMIN_URL', $_ENV['ADMIN_URL']);

define('APP_NAME', $_ENV['APP_NAME']);
define('SESSION_COOKIE_LIFETIME', intval($_ENV['SESSION_COOKIE_LIFETIME']));
define('PASSWORD_ALGO', PASSWORD_DEFAULT);

define('ESEWA_ENV', $_ENV['ESEWA_ENV']);
define('ESEWA_MERCHANT_CODE', $_ENV['ESEWA_MERCHANT_CODE']);
define('ESEWA_SUCCESS_URL', SITE_URL . 'esewa_success.php');
define('ESEWA_FAILURE_URL', SITE_URL . 'esewa_failure.php');

define('KHALTI_ENV', $_ENV['KHALTI_ENV']);
define('KHALTI_SECRET_KEY', $_ENV['KHALTI_SECRET_KEY']);
define('KHALTI_SUCCESS_URL', SITE_URL . 'khalti_success.php');
define('KHALTI_FAILURE_URL', SITE_URL . 'khalti_failure.php');