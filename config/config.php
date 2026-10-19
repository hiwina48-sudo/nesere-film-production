<?php
/**
 * NESERE Film Production - Configuration and Shared Helpers.
 * Production-ready security, database connection, and system helpers.
 */

// ------------------------------------------------------------------
// Settings & Environment Configuration
// ------------------------------------------------------------------
const APP_ENV = 'development'; // ስህተቶች ሲኖሩ በግልጽ እንዲታዩ ለጊዜው development ተደርጓል

// የዳታቤዝ መረጃዎች
const DB_HOST = 'localhost';
const DB_NAME = 'nesere_db';
const DB_USER = 'root';
const DB_PASS = '';

const UPLOAD_DIR = __DIR__ . '/../uploads';
const REQUEST_STATUSES = ['Pending', 'Reviewed', 'Quotation Sent', 'Approved', 'In Progress', 'Completed', 'Rejected'];
const PORTFOLIO_CATEGORIES = ['Films', 'TV Commercials', 'Weddings & Events', 'Graphics', 'Social Media', 'Web Projects'];

const IMAGE_TYPES = [
    'jpg'  => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png'  => ['image/png'],
    'webp' => ['image/webp'],
];

const REFERENCE_TYPES = [
    'jpg'  => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png'  => ['image/png'],
    'pdf'  => ['application/pdf'],
    'mp4'  => ['video/mp4'],
];

// Error Handling Configuration
if (APP_ENV === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// ------------------------------------------------------------------
// Secure Session & Security Headers
// ------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// $base is the path prefix back to the site root ('' or '../').
$_dir = basename(dirname($_SERVER['SCRIPT_FILENAME'] ?? ''));
$base = in_array($_dir, ['admin', 'customer'], true) ? '../' : '';
unset($_dir);

// ------------------------------------------------------------------
// Database Connection (PDO)
// ------------------------------------------------------------------
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $ex) {
            error_log("Database Connection Error: " . $ex->getMessage());
            http_response_code(500);
            exit('የዳታቤዝ ግንኙነት ስህተት አጋጥሟል። እባክዎን የዳታቤዝ ስም እና ፓስወርድ ትክክል መሆናቸውን ያረጋግጡ።');
        }
    }
    return $pdo;
}

// ------------------------------------------------------------------
// Security & Formatting Helpers
// ------------------------------------------------------------------
function e($v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

/** Trimmed string from $_POST (arrays are ignored). */
function post(string $key, string $default = ''): string
{
    return (isset($_POST[$key]) && is_string($_POST[$key])) ? trim($_POST[$key]) : $default;
}

/** Escaped value of a POSTed field, for re-filling forms after an error. */
function val(string $key, string $default = ''): string
{
    return e(post($key, $default));
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function etb($n): string
{
    return number_format((float)$n, 2) . ' ETB';
}

/** Split "A|B|C" or one-item-per-line text into a clean array. */
function list_items(?string $text): array
{
    $parts = preg_split('/\r\n|\r|\n|\|/', (string)$text);
    return array_values(array_filter(array_map('trim', $parts), 'strlen'));
}

/** Only allow http(s) URLs (blocks javascript: and similar). */
function safe_url(?string $u): string
{
    $u = trim((string)$u);
    return (preg_match('~^https?://~i', $u) && filter_var($u, FILTER_VALIDATE_URL)) ? $u : '';
}

/** Returns a YouTube embed URL, or '' when the link is not a YouTube video. */
function video_embed(string $url): string
{
    if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{11})~', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return '';
}

function status_badge(string $status): string
{
    $map = [
        'Pending' => 'badge-gold', 'Reviewed' => 'text-bg-info', 'Quotation Sent' => 'text-bg-warning',
        'Approved' => 'text-bg-success', 'In Progress' => 'text-bg-primary', 'Completed' => 'text-bg-success',
        'Rejected' => 'text-bg-danger', 'Accepted' => 'text-bg-success', 'Paid' => 'text-bg-success',
        'Failed' => 'text-bg-danger', 'Refunded' => 'text-bg-secondary', 'Draft' => 'text-bg-secondary',
        'Published' => 'text-bg-success', 'Active' => 'text-bg-primary',
    ];
    return '<span class="badge ' . ($map[$status] ?? 'text-bg-secondary') . '">' . e($status) . '</span>';
}

// ------------------------------------------------------------------
// Flash Messages
// ------------------------------------------------------------------
function flash(string $type, string $msg): void
{
    $_SESSION['flash'] = [$type, $msg];
}

function show_flash(): void
{
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $type = in_array($f[0], ['success', 'danger', 'warning', 'info'], true) ? $f[0] : 'info';
        echo '<div class="alert alert-' . $type . '" style="padding:12px; margin-bottom:20px; border-radius:6px;">' . e($f[1]) . '</div>';
    }
}

// ------------------------------------------------------------------
// CSRF Protection
// ------------------------------------------------------------------
function csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">';
}

function check_csrf(): void
{
    $sent = $_POST['csrf'] ?? ($_GET['csrf'] ?? '');
    if (!is_string($sent) || empty($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(419);
        exit('የደህንነት ጊዜ አልፎበታል፤ እባክዎን ገፁን አድሰው (Refresh አድርገው) እንደገና ይሞክሩ።');
    }
}

// ------------------------------------------------------------------
// Authentication & Roles
// ------------------------------------------------------------------
function logged(): bool
{
    return !empty($_SESSION['user']['user_id']) || !empty($_SESSION['user_id']);
}

function admin(): bool
{
    return logged() && (($_SESSION['user']['role'] ?? '') === 'admin' || ($_SESSION['role'] ?? '') === 'admin');
}

function customer(): bool
{
    return logged() && (($_SESSION['user']['role'] ?? '') === 'customer' || ($_SESSION['role'] ?? '') === 'customer');
}

function require_login(): void
{
    global $base;
    if (!logged()) {
        $dir = ($base === '../') ? basename(dirname($_SERVER['SCRIPT_FILENAME'])) . '/' : '';
        $_SESSION['after_login'] = $dir . basename($_SERVER['SCRIPT_NAME'])
            . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
        flash('warning', 'እባክዎን በመጀመሪያ ይግቡ።');
        redirect($base . 'login.php');
    }
}

function require_customer(): void
{
    global $base;
    require_login();
    if (admin()) {
        redirect($base . 'admin/index.php');
    }
}

function admin_role(): string
{
    return $_SESSION['user']['admin_role'] ?? 'super_admin';
}

function admin_can(string $area): bool
{
    $role = admin_role();
    if ($role === 'super_admin' || $role === 'admin') {
        return true;
    }
    $map = ['manager' => ['operations'], 'editor' => ['content']];
    return in_array($area, $map[$role] ?? [], true);
}

function require_admin(?string $area = null): void
{
    global $base;
    if (!admin()) {
        redirect($base . 'login.php');
    }
    if ($area !== null && !admin_can($area)) {
        http_response_code(403);
        exit('ወደዚህ ክፍል ለመግባት ፈቃድ የለዎትም።');
    }
}

// ------------------------------------------------------------------
// Notifications
// ------------------------------------------------------------------
function notify(int $userId, string $message): void
{
    try {
        db()->prepare('INSERT INTO notifications(user_id,message) VALUES(?,?)')->execute([$userId, $message]);
    } catch (Exception $e) {}
}

function notify_admins(string $message): void
{
    try {
        $ids = db()->query("SELECT user_id FROM users WHERE role='admin'")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($ids as $id) {
            notify((int)$id, $message);
        }
    } catch (Exception $e) {}
}

function unread_count(): int
{
    if (!logged()) {
        return 0;
    }
    try {
        $uid = $_SESSION['user']['user_id'] ?? ($_SESSION['user_id'] ?? 0);
        $st = db()->prepare('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0');
        $st->execute([$uid]);
        return (int)$st->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

function set_request_status(int $id, string $status, ?string $message = null): bool
{
    if (!in_array($status, REQUEST_STATUSES, true)) {
        return false;
    }
    try {
        $st = db()->prepare('SELECT customer_id, project_title FROM service_requests WHERE request_id=?');
        $st->execute([$id]);
        $r = $st->fetch();
        if (!$r) {
            return false;
        }
        db()->prepare('UPDATE service_requests SET status=? WHERE request_id=?')->execute([$status, $id]);
        if (!empty($r['customer_id'])) {
            notify((int)$r['customer_id'], $message ?? ('ለጥያቄዎ "' . ($r['project_title'] ?? '') . '" ምላሽ ተሰጥቷል፡ ' . $status));
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// ------------------------------------------------------------------
// Dynamic Settings & Pagination
// ------------------------------------------------------------------
function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (db()->query('SELECT setting_key, setting_value FROM site_settings') as $row) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {}
    }
    return (isset($cache[$key]) && $cache[$key] !== '') ? $cache[$key] : $default;
}

function social_links(): array
{
    try {
        return db()->query('SELECT * FROM social_media_links ORDER BY id')->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function paginate(int $total, int $perPage = 10): array
{
    $pages = max(1, (int)ceil($total / $perPage));
    $page  = min(max(1, (int)($_GET['page'] ?? 1)), $pages);
    return ['page' => $page, 'pages' => $pages, 'per' => $perPage, 'offset' => ($page - 1) * $perPage, 'total' => $total];
}

function limit_sql(array $p): string
{
    return ' LIMIT ' . (int)$p['per'] . ' OFFSET ' . (int)$p['offset'];
}

function pager(array $p): string
{
    if ($p['pages'] <= 1) {
        return '';
    }
    $q = $_GET;
    $html = '<nav class="mt-4"><ul class="pagination justify-content-center flex-wrap">';
    for ($i = 1; $i <= $p['pages']; $i++) {
        $q['page'] = $i;
        $html .= '<li class="page-item' . ($i === $p['page'] ? ' active' : '') . '"><a class="page-link" href="?'
            . e(http_build_query($q)) . '">' . $i . '</a></li>';
    }
    return $html . '</ul></nav>';
}

// ------------------------------------------------------------------
// Safe File Upload Helpers
// ------------------------------------------------------------------
function save_upload(array $file, array $allowed, string $prefix, int $maxBytes = 5242880): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return [null, null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'የፋይሉ መጫን አልተካሄደም። እባክዎ አነስ ያለ መጠን ያለው ፋይል ይሞክሩ።'];
    }
    if ($file['size'] > $maxBytes) {
        return [null, 'የፋይሉ መጠን በጣም ትልቅ ነው (ከፍተኛው፡ ' . round($maxBytes / 1048576) . ' MB)።'];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!isset($allowed[$ext])) {
        return [null, 'የተመረጠው የፋይል አይነት አይፈቀድም። የተፈቀዱት፡ ' . implode(', ', array_keys($allowed)) . '።'];
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!in_array($mime, $allowed[$ext], true)) {
        return [null, 'የፋይሉ ይዘት ከፋይል አይነት ጋር አይጣጣምም።'];
    }
    $name = $prefix . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $name)) {
        return [null, 'ፋይሉን ማስቀመጥ አልተቻለም።'];
    }
    return [$name, null];
}

function upload_file(string $field, array $allowed, string $prefix, int $maxBytes = 5242880): array
{
    if (empty($_FILES[$field]) || is_array($_FILES[$field]['name'])) {
        return [null, null];
    }
    return save_upload($_FILES[$field], $allowed, $prefix, $maxBytes);
}

function multi_files(string $field): array
{
    $out = [];
    if (empty($_FILES[$field]['name']) || !is_array($_FILES[$field]['name'])) {
        return $out;
    }
    foreach ($_FILES[$field]['name'] as $i => $name) {
        $out[] = [
            'name'     => $name,
            'type'     => $_FILES[$field]['type'][$i],
            'tmp_name' => $_FILES[$field]['tmp_name'][$i],
            'error'    => $_FILES[$field]['error'][$i],
            'size'     => $_FILES[$field]['size'][$i],
        ];
    }
    return $out;
}

function delete_upload(?string $name): void
{
    if ($name && preg_match('~^[A-Za-z0-9_.-]+$~', $name) && is_file(UPLOAD_DIR . '/' . $name)) {
        @unlink(UPLOAD_DIR . '/' . $name);
    }
}

// ------------------------------------------------------------------
// Studio Bank & Telebirr Accounts (Yeabsira Demissie)
// ------------------------------------------------------------------
if (!defined('STUDIO_BANK_ACCOUNTS')) {
    define('STUDIO_BANK_ACCOUNTS', [
        [
            'bank' => 'Bank of Abyssinia (አቢሲኒያ ባንክ)',
            'account_name' => 'Yeabsira Demissie',
            'account_number' => '110382138',
            'icon' => 'fa-university'
        ],
        [
            'bank' => 'Commercial Bank of Ethiopia - CBE (የኢትዮጵያ ንግድ ባንክ)',
            'account_name' => 'Yeabsira Demissie',
            'account_number' => '1000319258478',
            'icon' => 'fa-university'
        ],
        [
            'bank' => 'Telebirr (ቴሌብር)',
            'account_name' => 'Yeabsira Demissie',
            'account_number' => '0940695261',
            'icon' => 'fa-mobile-alt'
        ],
        [
            'bank' => 'CBE Birr (ሲቢኢ ብር)',
            'account_name' => 'Yeabsira Demissie',
            'account_number' => '0940695261',
            'icon' => 'fa-wallet'
        ]
    ]);
}