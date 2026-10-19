<?php
/**
 * NESERE Film Production - Customer Payment Page (customer/pay.php)
 * Handles payment submission safely matching existing database columns
 * Luxury Black & Gold Theme | Bilingual (AM/EN)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

$db = db();
$customer_id = (int)($_SESSION['user']['customer_id'] ?? ($_SESSION['user']['user_id'] ?? 0));
$request_id = (int)($_GET['id'] ?? 0);

$current_lang = $_SESSION['lang'] ?? 'am';
if (isset($_GET['lang'])) {
    $current_lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $current_lang;
}

// የትዕዛዙን መረጃ ማምጣት
$stmt = $db->prepare("SELECT * FROM service_requests WHERE request_id = ? AND customer_id = ?");
$stmt->execute([$request_id, $customer_id]);
$order = $stmt->fetch();

if (!$order) {
    redirect('requests.php');
}

$error = '';

// የክፍያ ማረጋገጫ ፎርም ሲሰሚት ሲደረግ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = post('payment_method');
    
    try {
        // ክፍያው መመዝገቡን እና status ቱን pending_verification መሆኑን ማዘመን (ያሉትን ኮለሞች ብቻ በመጠቀም)
        $upd = $db->prepare("
            UPDATE service_requests 
            SET payment_status = 'pending_verification', 
                payment_method = ? 
            WHERE request_id = ?
        ");
        $upd->execute([$payment_method, $request_id]);

        // 🎯 ክፍያው ተጠናቆ ሲመዝገብ በቀጥታ ወደ requests.php አረንጓዴ ቲክ ማሳሰቢያ ይዞ እንዲሄድ ማድረግ
        redirect('requests.php?payment=success');
    } catch (Exception $e) {
        $error = 'Database Error: ' . $e->getMessage();
    }
}

$page_title = ($current_lang === 'am') ? 'ክፍያ መፈጸሚያ | ንስር ፊልም ፕሮዳክሽን' : 'Payment Portal | Nesere Film Production';
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
        }
        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }
        .pay-container {
            max-width: 800px;
            margin: 40px auto 80px;
            padding: 0 20px;
        }
        .top-nav {
            margin-bottom: 25px;
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
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h2 {
            margin-top: 0;
            color: #fff;
            font-size: 24px;
            border-bottom: 1px solid var(--border-dark);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .order-summary {
            background: #16171D;
            border: 1px solid var(--border-dark);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .order-summary p {
            margin: 8px 0;
            font-size: 14px;
            color: #d1d1d6;
        }
        .bank-accounts-box {
            margin-bottom: 30px;
        }
        .bank-item {
            background: #181920;
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
        }
        .bank-item h4 {
            margin: 0 0 5px 0;
            color: var(--gold);
            font-size: 15px;
        }
        .bank-item p {
            margin: 3px 0;
            font-size: 13.5px;
            color: #fff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 13.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 12px;
            background: #181920;
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            box-sizing: border-box;
        }
        select:focus, input:focus {
            border-color: var(--gold);
            outline: none;
        }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }
        .alert-error {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid #e74c3c;
            color: #e74c3c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13.5px;
        }
    </style>
</head>
<body>

<div class="pay-container">
    <div class="top-nav">
        <a href="requests.php">← <?= ($current_lang === 'am') ? 'ወደ ትዕዛዞች ዝርዝር ተመለስ' : 'Back to Requests' ?></a>
    </div>

    <div class="card">
        <h2><?= ($current_lang === 'am') ? 'የክፍያ ማረጋገጫ ማጠናቀቂያ' : 'Complete Payment' ?></h2>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- የትዕዛዝ ማጠቃለያ -->
        <div class="order-summary">
            <p><strong><?= ($current_lang === 'am') ? 'የፕሮጀክት ርዕስ፡' : 'Project Title:' ?></strong> <?= htmlspecialchars($order['project_title']) ?></p>
            <p><strong><?= ($current_lang === 'am') ? 'ሊከፈል የሚገባው ጠቅላላ ዋጋ፡' : 'Total Budget:' ?></strong> <span style="color: var(--gold); font-size: 16px; font-weight: 800;"><?= number_format($order['budget']) ?> ETB</span></p>
        </div>

        <!-- የባንክ አካውንቶች መረጃ -->
        <div class="bank-accounts-box">
            <p style="color: var(--gold); font-weight: 700; margin-bottom: 12px;">
                <?= ($current_lang === 'am') ? 'እባክዎን ከዚህ በታች ባሉት የስቱዲዮ አካውንቶች ክፍያውን ይፈጽሙ:' : 'Please make your payment to one of the studio accounts below:' ?>
            </p>
            <?php foreach (STUDIO_BANK_ACCOUNTS as $acc): ?>
                <div class="bank-item">
                    <h4><i class="fa <?= $acc['icon'] ?>"></i> <?= htmlspecialchars($acc['bank']) ?></h4>
                    <p><strong>Account Name:</strong> <?= htmlspecialchars($acc['account_name']) ?></p>
                    <p><strong>Account Number:</strong> <span style="color: var(--gold-light); font-family: monospace; font-size: 15px;"><?= htmlspecialchars($acc['account_number']) ?></span></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- የክፍያ ማረጋገጫ ፎርም -->
        <form action="" method="POST">
            <div class="form-group">
                <label><?= ($current_lang === 'am') ? 'የተጠቀሙበት ባንክ / የክፍያ መንገድ' : 'Payment Method Used' ?></label>
                <select name="payment_method" required>
                    <option value="Bank of Abyssinia">Bank of Abyssinia (አቢሲኒያ ባንክ)</option>
                    <option value="Commercial Bank of Ethiopia">Commercial Bank of Ethiopia (ንግድ ባንክ)</option>
                    <option value="Telebirr">Telebirr (ቴሌብር)</option>
                    <option value="CBE Birr">CBE Birr (ሲቢኢ ብር)</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">
                <?= ($current_lang === 'am') ? 'ክፍያ ፈጽሜአለሁ አረጋግጥ →' : 'Confirm Payment Completed →' ?>
            </button>
        </form>
    </div>
</div>

</body>
</html>