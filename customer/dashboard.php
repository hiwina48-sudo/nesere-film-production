<?php
/**
 * NESERE Film Production - Customer Dashboard
 * Luxury Black & Gold Theme | Bilingual (Amharic & English)
 * Fixed: Added Logout Button & Top Navigation Actions
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

$db = db();
$customer_id = (int)($_SESSION['user']['customer_id'] ?? ($_SESSION['user']['user_id'] ?? 0));
$user_name = $_SESSION['user']['full_name'] ?? 'Customer';

// ቋንቋ መቀየሪያ
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

// ስታቲስቲክስ
$total_requests = 0;
$completed_requests = 0;
$pending_requests = 0;
$recent_requests = [];

try {
    // አጠቃላይ ጥያቄዎች
    $st = $db->prepare("SELECT COUNT(*) FROM service_requests WHERE customer_id = ?");
    $st->execute([$customer_id]);
    $total_requests = (int)$st->fetchColumn();

    // የተጠናቀቁ
    $st = $db->prepare("SELECT COUNT(*) FROM service_requests WHERE customer_id = ? AND status = 'Completed'");
    $st->execute([$customer_id]);
    $completed_requests = (int)$st->fetchColumn();

    // በመጠባበቅ ላይ ያሉ
    $st = $db->prepare("SELECT COUNT(*) FROM service_requests WHERE customer_id = ? AND status = 'Pending'");
    $st->execute([$customer_id]);
    $pending_requests = (int)$st->fetchColumn();

    // የቅርብ ጊዜ ጥያቄዎች
    $st = $db->prepare("
        SELECT r.*, s.service_name, s.service_name_am 
        FROM service_requests r 
        LEFT JOIN services s ON r.service_id = s.service_id 
        WHERE r.customer_id = ? 
        ORDER BY r.created_at DESC LIMIT 5
    ");
    $st->execute([$customer_id]);
    $recent_requests = $st->fetchAll();
} catch (Exception $e) {
    // Database fallback
}

// መዝገበ ቃላት
$lang_dict = [
    'am' => [
        'page_title'     => 'የደንበኛ ዳሽቦርድ | ንስር ፊልም ፕሮዳክሽን',
        'badge'          => 'የደንበኛ ክፍል',
        'welcome'        => 'እንኳን ደህና መጡ፣ ' . e($user_name),
        'sub'            => 'የጠየቋቸውን የፊልም እና ቪዲዮ ፕሮዳክሽን አገልግሎቶች እዚህ ይከታተሉ',
        'stat_total'     => 'አጠቃላይ ጥያቄዎች',
        'stat_pending'   => 'በመጠባበቅ ላይ',
        'stat_completed' => 'የተጠናቀቁ',
        'btn_request'    => '+ አዲስ አገልግሎት',
        'btn_my_requests'=> 'የእኔ ጥያቄዎች ዝርዝር',
        'recent_title'   => 'የቅርብ ጊዜ ጥያቄዎችዎ',
        'col_service'    => 'አገልግሎት / ርዕስ',
        'col_budget'     => 'በጀት',
        'col_status'     => 'ሁኔታ',
        'col_date'       => 'ቀን',
        'no_requests'    => 'እስካሁን ምንም የጠየቁት አገልግሎት የለም።',
        'btn_logout'     => 'ውጣ (Logout)',
        'confirm_logout' => 'ከሲስተሙ መውጣት ይፈልጋሉ?',
        'lang_toggle'    => 'English'
    ],
    'en' => [
        'page_title'     => 'Customer Dashboard | Nesere Film Production',
        'badge'          => 'CUSTOMER AREA',
        'welcome'        => 'Welcome, ' . e($user_name),
        'sub'            => 'Track and manage your video production and creative service inquiries',
        'stat_total'     => 'Total Requests',
        'stat_pending'   => 'Pending Review',
        'stat_completed' => 'Completed',
        'btn_request'    => '+ Request Service',
        'btn_my_requests'=> 'View My Requests',
        'recent_title'   => 'Your Recent Inquiries',
        'col_service'    => 'Service / Project',
        'col_budget'     => 'Budget',
        'col_status'     => 'Status',
        'col_date'       => 'Date',
        'no_requests'    => 'You have not submitted any service requests yet.',
        'btn_logout'     => 'Logout',
        'confirm_logout' => 'Are you sure you want to log out?',
        'lang_toggle'    => 'አማርኛ'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

if (file_exists(__DIR__ . '/../includes/header.php')) {
    require_once __DIR__ . '/../includes/header.php';
}
?>

<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-dark: #0B0B0B;
            --card-dark: #121316;
            --border-dark: #1E2026;
            --gold: #D4AF37;
            --gold-light: #F2D16B;
            --text-muted: #8E929D;
            --text-white: #FFFFFF;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
        }

        .cust-container {
            max-width: 1200px;
            margin: 40px auto 80px;
            padding: 0 20px;
        }

        /* Top Header */
        .cust-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-dark);
            padding-bottom: 25px;
            margin-bottom: 35px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .cust-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold);
            color: var(--gold-light);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .cust-header h1 {
            font-size: 28px;
            margin: 0 0 6px 0;
            color: var(--text-white);
        }

        .cust-header p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .cust-top-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-gold-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            color: #000;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 700;
            transition: transform 0.2s;
        }

        .btn-gold-action:hover {
            transform: translateY(-2px);
            color: #000;
        }

        .btn-lang {
            padding: 9px 14px;
            background: #16171D;
            border: 1px solid var(--border-dark);
            color: var(--gold);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-lang:hover {
            border-color: var(--gold);
        }

        .btn-logout-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 15px;
            background: rgba(231, 76, 60, 0.12);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #ff6b6b;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .btn-logout-action:hover {
            background: #e74c3c;
            color: #fff;
        }

        /* Stats Cards */
        .cust-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .cust-stat-card {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 12px;
            padding: 24px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cust-stat-info span {
            display: block;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .cust-stat-info h2 {
            font-size: 32px;
            margin: 0;
            font-weight: 800;
            color: var(--text-white);
        }

        .cust-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Recent Table Box */
        .cust-table-card {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 12px;
            padding: 26px;
            overflow-x: auto;
        }

        .cust-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-dark);
            padding-bottom: 14px;
        }

        .cust-table-header h2 {
            font-size: 18px;
            margin: 0;
            color: #FFFFFF;
        }

        .cust-table-header a {
            color: var(--gold);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
        }

        .cust-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .cust-table th {
            background: #16171D;
            padding: 14px 16px;
            font-size: 12.5px;
            color: var(--gold);
            border-bottom: 1px solid var(--border-dark);
            text-transform: uppercase;
        }

        .cust-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-dark);
            color: #D1D1D6;
            font-size: 13.5px;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid var(--gold);
            color: var(--gold-light);
        }
    </style>
</head>
<body>

<div class="cust-container">
    <div class="cust-header">
        <div>
            <span class="cust-badge"><?= e($str['badge']) ?></span>
            <h1><?= e($str['welcome']) ?></h1>
            <p><?= e($str['sub']) ?></p>
        </div>

        <div class="cust-top-actions">
           
            <a href="dashboard.php?lang=<?= ($current_lang === 'am') ? 'en' : 'am' ?>" class="btn-lang">
                <i class="fa fa-globe"></i> <?= e($str['lang_toggle']) ?>
            </a>
            <a href="../logout.php" class="btn-logout-action" onclick="return confirm('<?= e($str['confirm_logout']) ?>');">
                <i class="fa fa-sign-out-alt"></i> <?= e($str['btn_logout']) ?>
            </a>
        </div>
    </div>

    <!-- ስታቲስቲክስ ካርዶች -->
    <div class="cust-stats-grid">
        <div class="cust-stat-card">
            <div class="cust-stat-info">
                <span><?= e($str['stat_total']) ?></span>
                <h2><?= $total_requests ?></h2>
            </div>
            <div class="cust-stat-icon"><i class="fa fa-folder-open"></i></div>
        </div>

        <div class="cust-stat-card">
            <div class="cust-stat-info">
                <span><?= e($str['stat_pending']) ?></span>
                <h2><?= $pending_requests ?></h2>
            </div>
            <div class="cust-stat-icon"><i class="fa fa-clock"></i></div>
        </div>

        <div class="cust-stat-card">
            <div class="cust-stat-info">
                <span><?= e($str['stat_completed']) ?></span>
                <h2><?= $completed_requests ?></h2>
            </div>
            <div class="cust-stat-icon"><i class="fa fa-check-circle"></i></div>
        </div>
    </div>

    <!-- የቅርብ ጊዜ ጥያቄዎች ሰንጠረዥ -->
    <div class="cust-table-card">
        <div class="cust-table-header">
            <h2><?= e($str['recent_title']) ?></h2>
            <a href="requests.php"><?= e($str['btn_my_requests']) ?> →</a>
        </div>

        <table class="cust-table">
            <thead>
                <tr>
                    <th><?= e($str['col_service']) ?></th>
                    <th><?= e($str['col_budget']) ?></th>
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
                                <strong style="color: #fff; font-size: 14px;"><?= e($row['project_title'] ?: 'Untitled Project') ?></strong><br>
                                <span style="font-size: 12px; color: var(--gold);"><?= e($srv) ?></span>
                            </td>
                            <td><?= !empty($row['budget']) ? number_format((float)$row['budget'], 2) . ' ETB' : '—' ?></td>
                            <td>
                                <span class="badge-status"><?= e($row['status'] ?? 'Pending') ?></span>
                            </td>
                            <td><?= !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '—' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 35px;">
                            <?= e($str['no_requests']) ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    require_once __DIR__ . '/../includes/footer.php';
}
?>
</body>
</html>