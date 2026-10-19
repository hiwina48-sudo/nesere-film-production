<?php
/**
 * NESERE Film Production - Admin Service Requests Manager
 * Fixed: PDOStatement execute() error, CSRF Handling & Payment Slip Verification
 * Theme: Luxury Black (#0B0B0B) & Eagle Gold (#D4AF37)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

// Multi-language Toggle (Amharic / English)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();

// Action 1: የጥያቄን ሁኔታ መቀየር (Update Status)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'update_status') {
    check_csrf();
    $req_id     = (int)post('request_id');
    $new_status = post('status');

    if (in_array($new_status, REQUEST_STATUSES, true)) {
        if (function_exists('set_request_status')) {
            set_request_status($req_id, $new_status);
        } else {
            $stmt = $db->prepare("UPDATE service_requests SET status = ? WHERE request_id = ?");
            $stmt->execute([$new_status, $req_id]);
        }

        flash('success', ($current_lang === 'am') 
            ? 'የጥያቄው ሁኔታ በተሳካ ሁኔታ ተቀይሯል!' 
            : 'Request status updated successfully!');
    } else {
        flash('danger', ($current_lang === 'am') 
            ? 'ልክ ያልሆነ ሁኔታ ተመርጧል!' 
            : 'Invalid status selected!');
    }

    $redirect_url = 'requests.php' . (!empty($_GET['status']) ? '?status=' . urlencode($_GET['status']) : '');
    redirect($redirect_url);
}

// Action 2: የክፍያ ሁኔታን መቀየር (Mark as Paid)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'mark_paid') {
    check_csrf();
    $req_id = (int)post('request_id');

    try {
        $stmt = $db->prepare("UPDATE service_requests SET payment_status = 'Paid' WHERE request_id = ?");
        $stmt->execute([$req_id]);

        flash('success', ($current_lang === 'am') 
            ? 'የክፍያው ሁኔታ በተሳካ ሁኔታ ጸድቋል!' 
            : 'Payment verified and approved successfully!');
    } catch (Exception $e) {
        flash('danger', 'Error updating payment: ' . $e->getMessage());
    }

    $redirect_url = 'requests.php' . (!empty($_GET['status']) ? '?status=' . urlencode($_GET['status']) : '');
    redirect($redirect_url);
}

// Action 3: ጥያቄን መሰረዝ (Delete Request)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $token = $_GET['csrf'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        flash('danger', ($current_lang === 'am') ? 'የደህንነት ጊዜ አልፎበታል፤ እባክዎን እንደገና ይሞክሩ።' : 'Session token expired, please try again.');
        redirect('requests.php');
    }

    $del_id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM service_requests WHERE request_id = ?");
    $stmt->execute([$del_id]);

    flash('success', ($current_lang === 'am') 
        ? 'የአገልግሎት ጥያቄው ተሰርዟል!' 
        : 'Service request deleted successfully!');
    redirect('requests.php');
}

// ቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'     => 'የአገልግሎት ጥያቄዎች ማስተዳደሪያ',
        'back_btn'       => 'ወደ አድሚን ዳሽቦርድ ተመለስ',
        'main_title'     => 'የደንበኞች የአገልግሎት ጥያቄዎች',
        'sub_title'      => 'በድረ-ገጹ በኩል የገቡ የፊልም፣ ማስታወቂያ እና ዲጂታል ፕሮዳክሽን ትዕዛዞች ዝርዝር',
        'filter_all'     => 'ሁሉም ጥያቄዎች',
        'col_id'         => 'መለያ (ID)',
        'col_client'     => 'ደንበኛ',
        'col_service'    => 'የተጠየቀው አገልግሎት',
        'col_title'      => 'የፕሮጀክት ርዕስ እና በጀት',
        'col_payment'    => 'ክፍያ (Payment)',
        'col_status'     => 'ሁኔታ (Status)',
        'col_action'     => 'ድርጊት',
        'details_title'  => 'የፕሮጀክት ዝርዝር መረጃ፦',
        'deadline_lbl'   => 'የሚጠናቀቅበት ቀን፦',
        'no_requests'    => 'እስካሁን ምንም የቀረበ የአገልግሎት ጥያቄ የለም።',
        'btn_update'     => 'ቀይር',
        'btn_delete'     => 'ሰርዝ',
        'confirm_del'    => 'ይህንን ጥያቄ መሰረዝ ይፈልጋሉ?',
        'btn_approve_pay'=> 'ክፍያ አጽድቅ (Approve)',
        'view_slip'      => 'ደረሰኝ ይመልከቱ (Slip)',
        'pay_paid'       => 'የተከፈለ',
        'pay_pending'    => 'ማረጋገጫ የሚጠብቅ',
        'pay_unpaid'     => 'ያልተከፈለ'
    ],
    'en' => [
        'page_title'     => 'Manage Service Requests',
        'back_btn'       => 'Back to Dashboard',
        'main_title'     => 'Client Production Requests',
        'sub_title'      => 'Track, review, and update inquiries submitted for video and digital services',
        'filter_all'     => 'All Requests',
        'col_id'         => 'ID',
        'col_client'     => 'Client',
        'col_service'    => 'Service Requested',
        'col_title'      => 'Project Title & Budget',
        'col_payment'    => 'Payment',
        'col_status'     => 'Status',
        'col_action'     => 'Action',
        'details_title'  => 'Project Scope & Requirements:',
        'deadline_lbl'   => 'Target Deadline:',
        'no_requests'    => 'No production requests found.',
        'btn_update'     => 'Update',
        'btn_delete'     => 'Delete',
        'confirm_del'    => 'Are you sure you want to delete this request?',
        'btn_approve_pay'=> 'Approve Payment',
        'view_slip'      => 'View Slip',
        'pay_paid'       => 'Paid',
        'pay_pending'    => 'Pending Verification',
        'pay_unpaid'     => 'Unpaid'
    ]
];
$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

// ማጣሪያ (Filter Logic)
$filter_status = trim($_GET['status'] ?? '');
$params = [];

// ደህንነቱ የተጠበቀ ሰንጠረዥ ጥሪ (Safe Join)
try {
    $services_cols = $db->query("DESCRIBE services")->fetchAll(PDO::FETCH_COLUMN);
    $has_am_col = in_array('service_name_am', $services_cols, true);
    
    $select_am = $has_am_col ? "s.service_name_am" : "NULL AS service_name_am";
    $sql = "SELECT r.*, s.service_name, {$select_am} 
            FROM service_requests r 
            LEFT JOIN services s ON r.service_id = s.service_id ";
} catch (Exception $e) {
    $sql = "SELECT r.*, NULL AS service_name, NULL AS service_name_am FROM service_requests r ";
}

if (!empty($filter_status) && in_array($filter_status, REQUEST_STATUSES, true)) {
    $sql .= " WHERE r.status = ? ORDER BY r.created_at DESC";
    $params[] = $filter_status;
} else {
    $sql .= " ORDER BY r.created_at DESC";
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();

$total_count = (int)$db->query("SELECT COUNT(*) FROM service_requests")->fetchColumn();

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
    max-width: 1320px;
    margin: 40px auto 80px;
    padding: 0 20px;
}

.adm-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--adm-border);
    padding-bottom: 20px;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.adm-top-bar h1 {
    font-size: 26px;
    color: var(--adm-white);
    margin: 6px 0 0 0;
}

.adm-back-link {
    color: var(--adm-muted);
    text-decoration: none;
    font-size: 13.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.adm-back-link:hover {
    color: var(--adm-gold);
}

.filter-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.filter-tab {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    background: #15161B;
    border: 1px solid var(--adm-border);
    color: var(--adm-muted);
    transition: all 0.2s;
}

.filter-tab:hover, .filter-tab.active {
    border-color: var(--adm-gold);
    color: #FFFFFF;
}

.filter-tab.active {
    background: rgba(212, 175, 55, 0.15);
    color: var(--adm-gold-light);
}

.requests-card-box {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    overflow-x: auto;
}

.requests-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

.requests-table th {
    background: #16171D;
    padding: 16px 18px;
    font-size: 12px;
    font-weight: 700;
    color: var(--adm-gold);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--adm-border);
}

.requests-table td {
    padding: 18px;
    font-size: 13.5px;
    border-bottom: 1px solid var(--adm-border);
    color: #D1D1D6;
    vertical-align: top;
}

.requests-table tr:hover {
    background: rgba(255, 255, 255, 0.02);
}

.status-change-form {
    display: flex;
    gap: 6px;
    margin-top: 8px;
}

.status-change-form select {
    background: #0E0F12;
    border: 1px solid var(--adm-border);
    color: #FFFFFF;
    padding: 6px 8px;
    border-radius: 4px;
    font-size: 12px;
    outline: none;
}

.status-change-form select:focus {
    border-color: var(--adm-gold);
}

.status-change-form button {
    background: var(--adm-gold);
    color: #000;
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.btn-del-req {
    color: #ff6b6b;
    text-decoration: none;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 4px;
    background: rgba(231, 76, 60, 0.1);
    border: 1px solid rgba(231, 76, 60, 0.25);
    display: inline-block;
    margin-top: 8px;
}

.btn-del-req:hover {
    background: #e74c3c;
    color: #fff;
}

.btn-approve-payment {
    background: rgba(46, 204, 113, 0.15);
    border: 1px solid #2ecc71;
    color: #2ecc71;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 6px;
}

.btn-approve-payment:hover {
    background: #2ecc71;
    color: #000;
}
</style>

<div class="adm-container">
    <div class="adm-top-bar">
        <div>
            <a href="index.php" class="adm-back-link"><i class="fa fa-arrow-left"></i> <?= e($str['back_btn']) ?></a>
            <h1><?= e($str['main_title']) ?></h1>
            <p style="color: var(--adm-muted); font-size: 13.5px; margin: 4px 0 0 0;"><?= e($str['sub_title']) ?></p>
        </div>
    </div>

    <?php show_flash(); ?>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="requests.php" class="filter-tab <?= empty($filter_status) ? 'active' : '' ?>">
            <?= e($str['filter_all']) ?> (<?= $total_count ?>)
        </a>
        <?php 
        $count_stmt = $db->prepare("SELECT COUNT(*) FROM service_requests WHERE status = ?");
        foreach (REQUEST_STATUSES as $st): 
            $count_stmt->execute([$st]);
            $st_val = (int)$count_stmt->fetchColumn();
        ?>
            <a href="requests.php?status=<?= urlencode($st) ?>" class="filter-tab <?= ($filter_status === $st) ? 'active' : '' ?>">
                <?= e($st) ?> (<?= $st_val ?>)
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Requests Table -->
    <div class="requests-card-box">
        <table class="requests-table">
            <thead>
                <tr>
                    <th style="width: 40px;"><?= e($str['col_id']) ?></th>
                    <th><?= e($str['col_client']) ?></th>
                    <th><?= e($str['col_service']) ?></th>
                    <th><?= e($str['col_title']) ?></th>
                    <th><?= e($str['col_payment']) ?></th>
                    <th><?= e($str['col_status']) ?></th>
                    <th style="width: 170px;"><?= e($str['col_action']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $r): ?>
                        <?php 
                            $srv_name = ($current_lang === 'am' && !empty($r['service_name_am'])) 
                                ? $r['service_name_am'] 
                                : ($r['service_name'] ?? 'Production Service');
                            $pay_status = $r['payment_status'] ?? 'Unpaid';
                        ?>
                        <tr>
                            <td>#<?= (int)$r['request_id'] ?></td>
                            <td>
                                <strong style="color: #FFFFFF; font-size: 14px;"><?= e($r['client_name'] ?? 'Client') ?></strong><br>
                                <span style="font-size: 12.5px; color: var(--adm-gold);"><?= e($r['client_phone'] ?? '—') ?></span><br>
                                <span style="font-size: 12px; color: var(--adm-muted);"><?= e($r['client_email'] ?? '—') ?></span>
                            </td>
                            <td>
                                <strong style="color: var(--adm-gold-light);"><?= e($srv_name) ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #FFFFFF; margin-bottom: 4px;">
                                    <?= e($r['project_title'] ?: 'Untitled Project') ?>
                                </div>
                                <div style="font-size: 12.5px; color: var(--adm-muted); margin-bottom: 8px;">
                                    <span style="color: var(--adm-gold);"><?= e($r['budget_range'] ?? $r['budget'] ?? 'Custom Budget') ?></span>
                                </div>
                                
                                <div style="font-size: 13px; color: #A0A0AB; background: #0E0F12; padding: 10px; border-radius: 6px; border: 1px solid var(--adm-border);">
                                    <strong style="color: #FFFFFF; font-size: 11.5px;"><?= e($str['details_title']) ?></strong><br>
                                    <?= nl2br(e($r['project_details'] ?? '')) ?>
                                    
                                    <?php if (!empty($r['target_date'])): ?>
                                        <div style="margin-top: 6px; font-size: 12px; color: var(--adm-gold);">
                                            <?= e($str['deadline_lbl']) ?> <?= date('M d, Y', strtotime($r['target_date'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <!-- Payment Status Column -->
                            <td>
                                <?php if ($pay_status === 'Paid'): ?>
                                    <span style="color: #2ecc71; font-weight: 700; font-size: 12.5px;">
                                        <i class="fa fa-check-circle"></i> <?= e($str['pay_paid']) ?>
                                    </span>
                                <?php elseif ($pay_status === 'Pending_Verification'): ?>
                                    <span style="color: #f39c12; font-weight: 700; font-size: 12.5px;">
                                        <i class="fa fa-clock"></i> <?= e($str['pay_pending']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #e74c3c; font-size: 12.5px;">
                                        <?= e($str['pay_unpaid']) ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($r['payment_method'])): ?>
                                    <div style="font-size: 11.5px; color: var(--adm-muted); margin-top: 4px;">
                                        <?= e($r['payment_method']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($r['payment_slip'])): ?>
                                    <div style="margin-top: 6px;">
                                        <a href="../uploads/<?= e($r['payment_slip']) ?>" target="_blank" style="color: var(--adm-gold); font-size: 12px; text-decoration: underline;">
                                            <i class="fa fa-receipt"></i> <?= e($str['view_slip']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if ($pay_status === 'Pending_Verification'): ?>
                                    <form method="POST" action="requests.php" style="margin-top: 4px;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="mark_paid">
                                        <input type="hidden" name="request_id" value="<?= (int)$r['request_id'] ?>">
                                        <button type="submit" class="btn-approve-payment">
                                            <i class="fa fa-check"></i> <?= e($str['btn_approve_pay']) ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>

                            <!-- Order Status Column -->
                            <td>
                                <?= function_exists('status_badge') ? status_badge($r['status']) : '<span class="badge">' . e($r['status']) . '</span>' ?>
                                <div style="font-size: 11.5px; color: var(--adm-muted); margin-top: 6px;">
                                    <?= date('M d, Y', strtotime($r['created_at'])) ?>
                                </div>
                            </td>

                            <!-- Action Column -->
                            <td>
                                <form method="POST" action="requests.php" class="status-change-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="request_id" value="<?= (int)$r['request_id'] ?>">
                                    
                                    <select name="status">
                                        <?php foreach (REQUEST_STATUSES as $st): ?>
                                            <option value="<?= e($st) ?>" <?= ($r['status'] === $st) ? 'selected' : '' ?>>
                                                <?= e($st) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit"><?= e($str['btn_update']) ?></button>
                                </form>

                                <a href="requests.php?action=delete&id=<?= (int)$r['request_id'] ?>&csrf=<?= csrf() ?>" 
                                   class="btn-del-req" 
                                   onclick="return confirm('<?= e($str['confirm_del']) ?>');">
                                    <i class="fa fa-trash"></i> <?= e($str['btn_delete']) ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 45px; color: var(--adm-muted);">
                            <?= e($str['no_requests']) ?>
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