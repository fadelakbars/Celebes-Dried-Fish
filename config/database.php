<?php
// Hide PHP information header
@header_remove("X-Powered-By");

// Secure session cookie settings before session_start()
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') {
        $isSecure = true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        $isSecure = true;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function load_env_file($path) {
    if (!is_file($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#') {
            continue;
        }

        if (strpos($line, '=') === false) {
            if (getenv('DB_DATABASE') === false) {
                putenv('DB_DATABASE=' . $line);
                $_ENV['DB_DATABASE'] = $line;
                $_SERVER['DB_DATABASE'] = $line;
            }
            continue;
        }

        list($key, $value) = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");

        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

function env_value($key, $default = null) {
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }

    if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
        return $_ENV[$key];
    }

    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
        return $_SERVER[$key];
    }

    return $default;
}

load_env_file(__DIR__ . '/../.env');

$configuredHost = env_value('DB_HOST', 'localhost');
$configuredPort = (int) env_value('DB_PORT', 3306);
$configuredSocket = env_value('DB_SOCKET', null);
$configuredUsername = env_value('DB_USERNAME', '');
$configuredPassword = env_value('DB_PASSWORD', '');
$configuredDatabase = env_value('DB_DATABASE', env_value('DB_NAME', ''));

$profiles = [];

if ($configuredUsername !== '' || $configuredDatabase !== '') {
    $profiles[] = [
        'host' => $configuredHost,
        'username' => $configuredUsername,
        'password' => $configuredPassword,
        'database' => $configuredDatabase,
        'port' => $configuredPort,
        'socket' => $configuredSocket,
    ];
}

$profiles[] = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'u958379998_ikancullank',
    'port' => 3306,
    'socket' => null,
];

$profiles[] = [
    'host' => 'localhost',
    'username' => 'u958379998_mairocullank',
    'password' => 'u958379998_mairocullank',
    'database' => 'u958379998_mairoCullank',
    'port' => 3306,
    'socket' => null,
];

$profiles[] = [
    'host' => '127.0.0.1',
    'username' => 'u958379998_ikancullank',
    'password' => 'u958379998_ikanCullank',
    'database' => 'u958379998_ikancullank',
    'port' => 3306,
    'socket' => null,
];

$profiles[] = [
    'host' => '127.0.0.1',
    'username' => 'u958379998_mairocullank',
    'password' => 'u958379998_mairocullank',
    'database' => 'u958379998_mairoCullank',
    'port' => 3306,
    'socket' => null,
];

$profiles = array_values(array_unique($profiles, SORT_REGULAR));

$conn = null;
$connectionErrors = [];

mysqli_report(MYSQLI_REPORT_OFF);

foreach ($profiles as $profile) {
    if ($profile['username'] === '' || $profile['database'] === '') {
        continue;
    }

    try {
        $candidate = @new mysqli(
            $profile['host'],
            $profile['username'],
            $profile['password'],
            $profile['database'],
            $profile['port'],
            $profile['socket']
        );

        if (!$candidate->connect_error) {
            $conn = $candidate;
            break;
        }

        $connectionErrors[] = sprintf(
            '%s@%s/%s: %s',
            $profile['username'],
            $profile['host'],
            $profile['database'],
            $candidate->connect_error
        );
    } catch (Throwable $e) {
        $connectionErrors[] = sprintf(
            '%s@%s/%s: %s',
            $profile['username'],
            $profile['host'],
            $profile['database'],
            $e->getMessage()
        );
    }
}

if (!$conn) {
    error_log('Database connection failed. Tried profiles: ' . implode(' | ', $connectionErrors));
    http_response_code(500);
    exit('Konfigurasi database gagal. Periksa DB_HOST, DB_USERNAME, DB_PASSWORD, dan DB_DATABASE di hosting.');
}

// Global functions
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

function check_login() {
    if(!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Silakan login terlebih dahulu untuk melanjutkan.';
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $redirect");
        exit();
    }
}

function check_admin() {
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    }
}
?>
