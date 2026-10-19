<?php
/**
 * NESERE Film Production - Admin Dashboard (index.php)
 * Complete: Requests, Portfolio, Messages & User Management
 * Luxury Black (#0B0B0B) & Gold (#D4AF37) Theme | Bilingual
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

// ቋንቋ መቀየሪያ (Amharic / English)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();
$admin_name = $_SESSION['user']['full_name'] ?? 'Admin';

// ስታቲስቲክስ መቁጠርያ
$total_requests   = 0;
$pending_requests = 0;
$total_projects   = 0;
$total_messages   = 0;
$unread_messages  = 0;
$recent_requests  = [];

try {
    // 1. የአገልግሎት ጥያቄዎች ብዛት
    $total_requests = (int)$db->query("SELECT COUNT(*) FROM service_requests")->fetchColumn();
    
    // 2. በመጠባበቅ ላይ ያሉ ጥያቄዎች
    $st = $db->prepare("SELECT COUNT(*) FROM service_requests WHERE status = 'Pending'");
    $st->execute();
    $pending_requests = (int)$st->fetchColumn();

    // 3. የተለጠፉ ፖርትፎሊዮ ስራዎች ብዛት
    $total_projects = (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();

    // 4. የመልዕክቶች ብዛት
    $msg_table = 'messages';
    try {
        $db->query("SELECT 1 FROM messages LIMIT 1");
    } catch (Exception $e) {
        $msg_table = 'contact_messages';
    }

    $total_messages  = (int)$db->query("SELECT COUNT(*) FROM {$msg_table}")->fetchColumn();
    $unread_messages = (int)$db->query("SELECT COUNT(*) FROM {$msg_table} WHERE is_read = 0")->fetchColumn();

    // የቅርብ ጊዜ 5 ጥያቄዎች
    $recent_requests = $db->query("
        SELECT r.*, s.service_name, s.service_name_am 
        FROM service_requests r 
        LEFT JOIN services s ON r.service_id = s.service_id 
        ORDER BY r.created_at DESC 
        LIMIT 5
    ")->fetchAll();
} catch (Exception $e) {
    // ሰንጠረዥ ባይኖርም ዳሽቦርዱ እንዳይቋረጥ
}

// የቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'       => 'አድሚን ዳሽቦርድ | Nesere Film Production',
        'badge'            => 'አስተዳዳሪ ክፍል (ADMIN PANEL)',
        'welcome'          => 'እንኳን ደህና መጡ፣ ' . e($admin_name),
        'sub'              => 'የንስር ፊልም ፕሮዳክሽን ድረ-ገጽ የስራ እንቅስቃሴዎች መቆጣጠሪያ',
        'stat_requests'    => 'አጠቃላይ ጥያቄዎች',
        'stat_pending'     => 'በመጠባበቅ ላይ ያሉ',
        'stat_portfolio'   => 'የተለጠፉ ስራዎች',
        'stat_messages'    => 'መልዕክቶች (ያልተነበቡ: ' . $unread_messages . ')',
        'quick_nav_title'  => 'ፈጣን የቁጥጥር ክፍሎች',
        'nav_requests'     => 'የአገልግሎት ጥያቄዎች',
        'nav_requests_sub' => 'የገቡ ትዕዛዞችን ተመልከት እና ምላሽ ስጥ',
        'nav_portfolio'    => 'የስራዎች ማሳያ (Portfolio)',
        'nav_port_sub'     => 'አዳዲስ ፊልሞችን እና ቪዲዮዎችን ለጥፍ/አስተዳድር',
        'nav_messages'     => 'የደንበኞች መልዕክቶች',
        'nav_messages_sub' => 'ከኮንታክት ቅጽ የመጡ መልዕክቶችን አንብብ',
        'nav_users'        => 'የአድሚን አካውንቶች',
        'nav_users_sub'    => 'አዳዲስ አድሚኖችን መዝግብ/አስተዳድር',
        'recent_title'     => 'የቅርብ ጊዜ የገቡ ጥያቄዎች',
        'view_all'         => 'ሁሉንም ተመልከት →',
        'col_client'       => 'ደንበኛ',
        'col_service'      => 'አገልግሎት',
        'col_status'       => 'ሁኔታ',
        'col_date'         => 'ቀን',
        'no_records'       => 'ምንም የቅርብ ጊዜ ጥያቄ አልተገኘም።',
        'btn_logout'       => 'ውጣ (Logout)',
        'lang_toggle'      => 'English'
    ],
    'en' => [
        'page_title'       => 'Admin Dashboard | Nesere Film Production',
        'badge'            => 'ADMIN PANEL',
        'welcome'          => 'Welcome back, ' . e($admin_name),
        'sub'              => 'Nesere Film Production Web Operations and Management Portal',
        'stat_requests'    => 'Total Requests',
        'stat_pending'     => 'Pending Review',
        'stat_portfolio'   => 'Portfolio Items',
        'stat_messages'    => 'Messages (Unread: ' . $unread_messages . ')',
        'quick_nav_title'  => 'Quick Management Links',
        'nav_requests'     => 'Service Requests',
        'nav_requests_sub' => 'Review client orders & update statuses',
        'nav_portfolio'    => 'Portfolio Manager',
        'nav_port_sub'     => 'Publish & manage movies, ads, & videos',
        'nav_messages'     => 'Client Messages',
        'nav_messages_sub' => 'Read contact inquiries & feedback',
        'nav_users'        => 'Administrator Accounts',
        'nav_users_sub'    => 'Register & manage system users/roles',
        'recent_title'     => 'Recent Service Inquiries',
        'view_all'         => 'View All →',
        'col_client'       => 'Client',
        'col_service'      => 'Service',
        'col_status'       => 'Status',
        'col_date'         => 'Date',
        'no_records'       => 'No recent inquiries found.',
        'btn_logout'       => 'Logout',
        'lang_toggle'      => 'አማርኛ'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/../includes/header.php';
?>

<style>
:root {
    --adm-bg: #0B0B0B;
    --adm-card: #121316;
    --adm-border: #1E2026;
    --adm-gold: #D4AF37;
    --adm-gold-light: #F2D16B;
    --adm-muted: #8E929D;
    --adm-white: #FFFFFF;
}

.adm-container {
    max-width: 1260px;
    margin: 40px auto 90px;
    padding: 0 20px;
}

/* Header */
.adm-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--adm-border);
    padding-bottom: 25px;
    margin-bottom: 35px;
    flex-wrap: wrap;
    gap: 20px;
}

.adm-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid var(--adm-gold);
    color: var(--adm-gold-light);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.adm-header-bar h1 {
    font-size: 28px;
    margin: 0 0 6px 0;
    color: var(--adm-white);
}

.adm-header-bar p {
    margin: 0;
    color: var(--adm-muted);
    font-size: 14px;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-lang-switch {
    padding: 9px 15px;
    background: #16171D;
    border: 1px solid var(--adm-border);
    color: var(--adm-gold);
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.btn-logout {
    padding: 9px 16px;
    background: rgba(231, 76, 60, 0.15);
    border: 1px solid rgba(231, 76, 60, 0.3);
    color: #ff6b6b;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.btn-logout:hover {
    background: #e74c3c;
    color: #fff;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
    margin-bottom: 35px;
}

.stat-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 24px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-info span {
    display: block;
    font-size: 13px;
    color: var(--adm-muted);
    margin-bottom: 6px;
}

.stat-info h2 {
    font-size: 32px;
    margin: 0;
    font-weight: 800;
    color: var(--adm-white);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: rgba(212, 175, 55, 0.1);
    color: var(--adm-gold-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

/* Quick Nav Cards */
.quick-nav-section {
    margin-bottom: 40px;
}

.quick-nav-section h2 {
    font-size: 18px;
    color: var(--adm-white);
    margin: 0 0 16px 0;
}

.nav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.nav-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 22px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.25s ease;
    position: relative;
}

.nav-card:hover {
    border-color: var(--adm-gold);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
}

.nav-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #181920;
    border: 1px solid var(--adm-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: var(--adm-gold);
    flex-shrink: 0;
}

.nav-card-text strong {
    display: block;
    font-size: 15.5px;
    color: var(--adm-white);
    margin-bottom: 4px;
}

.nav-card-text span {
    display: block;
    font-size: 12px;
    color: var(--adm-muted);
}

.nav-badge-pill {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--adm-gold);
    color: #000;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 10px;
}

/* Recent Table Box */
.recent-box {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 25px;
    overflow-x: auto;
}

.recent-box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--adm-border);
    padding-bottom: 14px;
}

.recent-box-header h2 {
    font-size: 18px;
    margin: 0;
    color: var(--adm-white);
}

.recent-box-header a {
    color: var(--adm-gold);
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 600;
}

.recent-table {
    width: 100%;
    border-collapse: collapse;
}

.recent-table th {
    background: #16171D;
    padding: 14px 16px;
    font-size: 12.5px;
    color: var(--adm-gold);
    text-align: left;
    text-transform: uppercase;
    border-bottom: 1px solid var(--adm-border);
}

.recent-table td {
    padding: 16px;
    border-bottom: 1px solid var(--adm-border);
    color: #D1D1D6;
    font-size: 13.5px;
}

.badge-gold-sub {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
    background: rgba(212, 175, 55, 0.15);
    border: 1px solid var(--adm-gold);
    color: var(--adm-gold-light);
}
</style>

<div class="adm-container">
    <!-- Header Bar -->
    <div class="adm-header-bar">
        <div>
            <span class="adm-badge"><?= e($str['badge']) ?></span>
            <h1><?= e($str['welcome']) ?></h1>
            <p><?= e($str['sub']) ?></p>
        </div>

        <div class="header-actions">
            <a href="index.php?lang=<?= ($current_lang === 'am') ? 'en' : 'am' ?>" class="btn-lang-switch">
                <i class="fa fa-globe"></i> <?= e($str['lang_toggle']) ?>
            </a>
            <a href="../logout.php" class="btn-logout">
                <i class="fa fa-sign-out-alt"></i> <?= e($str['btn_logout']) ?>
            </a>
        </div>
    </div>

    <?php show_flash(); ?>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <span><?= e($str['stat_requests']) ?></span>
                <h2><?= $total_requests ?></h2>
            </div>
            <div class="stat-icon"><i class="fa fa-envelope-open-text"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span><?= e($str['stat_pending']) ?></span>
                <h2><?= $pending_requests ?></h2>
            </div>
            <div class="stat-icon"><i class="fa fa-hourglass-half"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span><?= e($str['stat_portfolio']) ?></span>
                <h2><?= $total_projects ?></h2>
            </div>
            <div class="stat-icon"><i class="fa fa-film"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span><?= e($str['stat_messages']) ?></span>
                <h2><?= $total_messages ?></h2>
            </div>
            <div class="stat-icon"><i class="fa fa-comments"></i></div>
        </div>
    </div>

    <!-- Quick Navigation Links (4ቱ ዋና ዋና ክፍሎች) -->
    <div class="quick-nav-section">
        <h2><?= e($str['quick_nav_title']) ?></h2>
        
        <div class="nav-grid">
            <!-- 1. Service Requests -->
            <a href="requests.php" class="nav-card">
                <div class="nav-card-icon"><i class="fa fa-inbox"></i></div>
                <div class="nav-card-text">
                    <strong><?= e($str['nav_requests']) ?></strong>
                    <span><?= e($str['nav_requests_sub']) ?></span>
                </div>
            </a>

            <!-- 2. Portfolio Manager -->
            <a href="portfolio.php" class="nav-card">
                <div class="nav-card-icon"><i class="fa fa-photo-video"></i></div>
                <div class="nav-card-text">
                    <strong><?= e($str['nav_portfolio']) ?></strong>
                    <span><?= e($str['nav_port_sub']) ?></span>
                </div>
            </a>

            <!-- 3. Messages Card Link -->
            <a href="messages.php" class="nav-card">
                <?php if ($unread_messages > 0): ?>
                    <span class="nav-badge-pill"><?= $unread_messages ?> አዲስ</span>
                <?php endif; ?>
                <div class="nav-card-icon"><i class="fa fa-comments"></i></div>
                <div class="nav-card-text">
                    <strong><?= e($str['nav_messages']) ?></strong>
                    <span><?= e($str['nav_messages_sub']) ?></span>
                </div>
            </a>

            <!-- 4. User/Admin Accounts -->
            <a href="users.php" class="nav-card">
                <div class="nav-card-icon"><i class="fa fa-user-shield"></i></div>
                <div class="nav-card-text">
                    <strong><?= e($str['nav_users']) ?></strong>
                    <span><?= e($str['nav_users_sub']) ?></span>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Inquiries Table -->
    <div class="recent-box">
        <div class="recent-box-header">
            <h2><?= e($str['recent_title']) ?></h2>
            <a href="requests.php"><?= e($str['view_all']) ?></a>
        </div>

        <table class="recent-table">
            <thead>
                <tr>
                    <th><?= e($str['col_client']) ?></th>
                    <th><?= e($str['col_service']) ?></th>
                    <th><?= e($str['col_status']) ?></th>
                    <th><?= e($str['col_date']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recent_requests)): ?>
                    <?php foreach ($recent_requests as $row): ?>
                        <?php 
                            $srv = ($current_lang === 'am' && !empty($row['service_name_am'])) 
                                ? $row['service_name_am'] 
                                : ($row['service_name'] ?? 'Production Service');
                        ?>
                        <tr>
                            <td>
                                <strong style="color: #fff; font-size: 14px;"><?= e($row['client_name'] ?? 'Client') ?></strong><br>
                                <span style="font-size: 12px; color: var(--adm-gold);"><?= e($row['client_phone'] ?? '—') ?></span>
                            </td>
                            <td>
                                <strong style="color: var(--adm-gold-light);"><?= e($row['project_title'] ?: 'Project') ?></strong><br>
                                <span style="font-size: 12px; color: var(--adm-muted);"><?= e($srv) ?></span>
                            </td>
                            <td>
                                <?= function_exists('status_badge') ? status_badge($row['status']) : '<span class="badge-gold-sub">' . e($row['status']) . '</span>' ?>
                            </td>
                            <td><?= !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '—' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--adm-muted); padding: 35px;">
                            <?= e($str['no_records']) ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
require_once __DIR__ . '/../includes/footer.php'; 
?>