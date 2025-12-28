<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sample_store');

define('ROOT_URL', 'http://localhost:8000/'); // Root of the project
define('BASE_URL', 'http://localhost:8000/public/'); // Public folder URL
define('SITE_URL', 'http://localhost:8000/public/'); // Needed for eSewa callbacks
define('ADMIN_URL', 'http://localhost:8000/admin/'); // Admin folder URL


define('APP_NAME', 'Sample Store');

define('ESEWA_ENV', 'test'); // Values: test, live.

define('ESEWA_MERCHANT_CODE', 'EPAYTEST');
define('ESEWA_SUCCESS_URL', SITE_URL . 'esewa_success.php');
define('ESEWA_FAILURE_URL', SITE_URL . 'esewa_failure.php');

define('SESSION_COOKIE_LIFETIME', 60 * 60 * 24 * 7);

define('PASSWORD_ALGO', PASSWORD_DEFAULT);
