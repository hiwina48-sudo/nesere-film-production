<?php
/**
 * NESERE Film Production - Customer Requests List (customer/requests.php)
 * Displays logged-in customer's orders table with Payment Success Alert
 * Luxury Black & Gold Theme | Bilingual (AM/EN)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

$db = db();
$customer_id = (int)($_SESSION['user']['customer_id'] ?? ($_SESSION['user']['user_id'] ?? 0));
$current_lang = $_SESSION['lang'] ?? 'am';

if (isset($_GET['lang'])) {
    $current_lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $current_lang;
}

$payment_success = (isset($_GET['payment']) && $_GET['payment'] === 'success');

// የሎግ ሳኢን አደረገው የደንበኛውን ትዕዛዞች ብቻ ማውጣት
$requests = [];
try {
    $stmt = $db->prepare("
        SELECT r.*, s.service_name, s.service_name_am 
        FROM service_requests r 
        LEFT JOIN services s ON r.service_id = s.service_id 
        WHERE r.customer_id = ?
        ORDER BY r.request_id DESC
    ");
    $stmt->execute([$customer_id]);
    $requests = $stmt->fetchAll();
} catch (Exception $e) {
    $requests = [];
}

$page_title = ($current_lang === 'am') ? 'የእኔ ትዕዛዞች | ንስር ፊልም ፕሮዳክሽን' : 'My Requests | Nesere Film Production';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
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
            --green: #2ecc71;
            --green-bg: rgba(46, 204, 113, 0.12);
        }
        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }
        .req-container {
            max-width: 1100px;
            margin: 40px auto 80px;
            padding: 0 20px;
        }
        .payment-success-card {
            background: linear-gradient(180deg, #161e19, #111413);
            border: 1px solid var(--green);
            border-radius: 14px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(46, 204, 113, 0.15);
        }
        .success-check-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--green-bg);
            border: 2px solid var(--green);
            color: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 15px;
        }
        .payment-success-card h2 {
            font-size: 22px;
            color: var(--green);
            margin: 0 0 8px 0;
        }
        .payment-success-card p {
            color: #cfd3dc;
            font-size: 14px;
            margin: 0;
        }
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-dark);
            padding-bottom: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .top-nav a {
            color: var(--gold);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .card {
            background: var(--card-dark);
            border: 1px solid var(--border-dark);
            border-radius: 12px;
            padding: 25px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: #16171D;
            padding: 14px;
            font-size: 13px;
            color: var(--gold);
            border-bottom: 1px solid var(--border-dark);
        }
        td {
            padding: 14px;
            border-bottom: 1px solid var(--border-dark);
            color: #D1D1D6;
            font-size: 13.5px;
        }
        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid var(--gold);
            color: var(--gold-light);
        }
        .btn-pay {
            padding: 7px 16px;
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            color: #000;
            text-decoration: none;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 800;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-pay:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }
    </style>
</head>
<body>

<div class="req-container">

    <!-- ክፍያ ስኬታማ ሲሆን የሚታይ አረንጓዴ የቲክ (✓) ማሳሰቢያ -->
    <?php if ($payment_success): ?>
        <div class="payment-success-card">
            <div class="success-check-circle">
                <i class="fa fa-check"></i>
            </div>
            <h2><?= ($current_lang === 'am') ? 'ክፍያዎ በተሳካ ሁኔታ ተጠናቋል!' : 'Payment Completed Successfully!' ?></h2>
            <p><?= ($current_lang === 'am') ? 'እናመሰግናለን! የከፈሉት ደረሰኝ በስኬት ደርሶናል፤ የፕሮዳክሽን ባለሙያዎቻችን ስራውን ለመጀመር በቅርቡ ያነጋግሩዎታል።' : 'Thank you! Your payment proof has been successfully received. Our production team will contact you shortly.' ?></p>
        </div>
    <?php endif; ?>

    <!-- ዳሽቦርድ መመለሻ እና አዲስ እዘዝ አዝራር -->
    <div class="top-nav">
        <a href="dashboard.php">← <?= ($current_lang === 'am') ? 'ወደ ዳሽቦርድ ተመለስ' : 'Back to Dashboard' ?></a>
        <h2 style="margin: 0; font-size: 20px; color: #fff;"><?= ($current_lang === 'am') ? 'የእኔ ትዕዛዞች ዝርዝር' : 'My Production Requests' ?></h2>
        <a href="../service_request.php" style="background: rgba(212,175,55,0.1); padding: 8px 14px; border-radius: 6px; border: 1px solid var(--gold); color: var(--gold);">
            + <?= ($current_lang === 'am') ? 'አዲስ እዘዝ (New Order)' : 'New Order' ?>
        </a>
    </div>

    <?php if (function_exists('show_flash')) { show_flash(); } ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Project / Service</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $r): ?>
                        <?php 
                            $req_id = (int)$r['request_id'];
                            $price = !empty($r['budget']) ? (float)$r['budget'] : (!empty($r['price']) ? (float)$r['price'] : 0);
                            $pay_status = strtolower($r['payment_status'] ?? '');
                            $is_paid = ($pay_status === 'paid' || $pay_status === 'pending_verification');
                        ?>
                        <tr>
                            <td>#<?= $req_id ?></td>
                            <td>
                                <strong style="color: #fff;"><?= htmlspecialchars($r['project_title'] ?? 'Custom Project') ?></strong><br>
                                <span style="font-size: 12px; color: var(--gold);">
                                    <?= htmlspecialchars(($current_lang === 'am' && !empty($r['service_name_am'])) ? $r['service_name_am'] : ($r['service_name'] ?? 'Production')) ?>
                                </span>
                            </td>
                            <td><?= $price > 0 ? number_format($price) . ' ETB' : '—' ?></td>
                            <td><span class="badge"><?= htmlspecialchars($r['status'] ?? 'Pending') ?></span></td>
                            <td>
                                <?php if ($pay_status === 'paid'): ?>
                                    <span style="color: #2ecc71; font-weight: 700;">Paid ✓</span>
                                <?php elseif ($pay_status === 'pending_verification'): ?>
                                    <span style="color: var(--gold); font-weight: 700;">Under Review</span>
                                <?php else: ?>
                                    <span style="color: #e74c3c; font-weight: 700;">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($r['created_at']) ? date('M d, Y', strtotime($r['created_at'])) : '—' ?></td>
                            <td>
                                <?php if (!$is_paid): ?>
                                    <a href="pay.php?id=<?= $req_id ?>" class="btn-pay">
                                        <?= ($current_lang === 'am') ? 'ክፈል' : 'Pay' ?> →
                                    </a>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 12px;">Submitted</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 35px;">
                            <?= ($current_lang === 'am') ? 'እስካሁን ያዘዙት ምንም አይነት ትዕዛዝ የለም።' : 'No service requests found for your account.' ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>