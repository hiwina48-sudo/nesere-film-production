<?php
/**
 * NESERE Film Production - Manual Payment Submission
 * Customers submit payment method, transaction reference and receipt.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

$db = db();
$customer_id = (int)($_SESSION['user']['customer_id'] ?? ($_SESSION['user']['user_id'] ?? ($_SESSION['user_id'] ?? 0)));
$request_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$current_lang = $_SESSION['lang'] ?? 'am';
if (isset($_GET['lang'])) {
    $current_lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $current_lang;
}
$isAm = $current_lang === 'am';
$t = static function (string $am, string $en) use ($isAm): string { return $isAm ? $am : $en; };

$stmt = $db->prepare('SELECT * FROM service_requests WHERE request_id = ? AND customer_id = ? LIMIT 1');
$stmt->execute([$request_id, $customer_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    redirect('requests.php');
}

$error = '';
$methods = ['Bank of Abyssinia', 'Commercial Bank of Ethiopia', 'Telebirr', 'CBE Birr'];
$allowedSlips = [
    'jpg' => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png' => ['image/png'],
    'webp' => ['image/webp'],
    'pdf' => ['application/pdf'],
];
$paymentStatus = strtolower((string)($order['payment_status'] ?? 'unpaid'));
$alreadySubmitted = in_array($paymentStatus, ['pending_verification', 'paid'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if ($alreadySubmitted) {
        $error = $t('ይህ ክፍያ ከዚህ በፊት ቀርቧል ወይም ጸድቋል።', 'This payment has already been submitted or approved.');
    } else {
        $payment_method = post('payment_method');
        $payment_reference = substr(post('payment_reference'), 0, 100);
        if (!in_array($payment_method, $methods, true)) {
            $error = $t('ትክክለኛ የክፍያ መንገድ ይምረጡ።', 'Please choose a valid payment method.');
        } elseif (empty($_FILES['payment_slip']) || ($_FILES['payment_slip']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $error = $t('የክፍያ ደረሰኝዎን ይጫኑ።', 'Please upload your payment receipt or screenshot.');
        } else {
            [$slipName, $uploadError] = upload_file('payment_slip', $allowedSlips, 'pay_', 5 * 1024 * 1024);
            if ($uploadError !== null) {
                $error = $uploadError;
            } elseif (!$slipName) {
                $error = $t('ደረሰኙን ማስቀመጥ አልተቻለም። እንደገና ይሞክሩ።', 'Could not save the receipt. Please try again.');
            } else {
                try {
                    $upd = $db->prepare("UPDATE service_requests
                        SET payment_status = 'Pending_Verification', payment_method = ?, payment_slip = ?, payment_reference = ?
                        WHERE request_id = ? AND customer_id = ? AND payment_status = 'Unpaid'");
                    $upd->execute([$payment_method, $slipName, ($payment_reference !== '' ? $payment_reference : null), $request_id, $customer_id]);
                    if ($upd->rowCount() !== 1) {
                        delete_upload($slipName);
                        $error = $t('የክፍያ ሁኔታው ተቀይሯል። ገጹን አድስና እንደገና ይመልከቱ።', 'The payment status changed. Refresh the page and try again.');
                    } else {
                        flash('success', $t('የክፍያ ማረጋገጫዎ ተልኳል። Admin እስኪያረጋግጠው ድረስ በግምገማ ላይ ይቆያል።', 'Payment proof submitted. It will remain under review until an administrator verifies it.'));
                        redirect('requests.php?payment=success');
                    }
                } catch (Throwable $e) {
                    if ($slipName) delete_upload($slipName);
                    error_log('Manual payment submission failed: ' . $e->getMessage());
                    $error = $t('ክፍያውን መመዝገብ አልተቻለም። ዳታቤዙን ያረጋግጡና እንደገና ይሞክሩ።', 'Could not record the payment. Check the database and try again.');
                }
            }
        }
    }
}

$page_title = $t('ክፍያ መፈጸሚያ | ንስር ፊልም ፕሮዳክሽን', 'Manual Payment | Nesere Film Production');
?>
<!doctype html>
<html lang="<?= $isAm ? 'am' : 'en' ?>">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($page_title) ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--bg:#0b0b0b;--card:#121316;--border:#292b31;--gold:#d4af37;--gold2:#f2d16b;--muted:#a4a7b0;--white:#fff;--red:#ff7777;--green:#70dfa0}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--white);font:15px/1.55 system-ui,-apple-system,"Segoe UI",sans-serif}.wrap{max-width:850px;margin:36px auto;padding:0 16px 50px}.back{display:inline-block;color:var(--gold);text-decoration:none;margin:0 0 20px}.card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:clamp(20px,4vw,36px);box-shadow:0 15px 45px #0006}h1{font-size:clamp(22px,4vw,29px);margin:0 0 22px}.summary{background:#191a20;border:1px solid var(--border);padding:18px;border-radius:12px;margin-bottom:25px}.summary p{margin:5px 0;color:#ddd}.price{color:var(--gold2);font-size:20px;font-weight:800}.accounts{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin:14px 0 26px}.bank{border:1px solid var(--border);border-radius:10px;background:#17181e;padding:14px}.bank h3{font-size:15px;color:var(--gold);margin:0 0 8px}.bank p{font-size:13px;margin:4px 0;overflow-wrap:anywhere}.field{margin:18px 0}.field label{display:block;color:#ddd;font-size:14px;margin-bottom:8px}input,select{display:block;width:100%;border:1px solid #363842;background:#191a20;color:white;padding:12px;border-radius:8px;font:inherit}input:focus,select:focus{outline:2px solid #d4af3766;border-color:var(--gold)}.hint{font-size:12px;color:var(--muted);margin-top:6px}.btn{width:100%;border:0;border-radius:9px;background:linear-gradient(135deg,var(--gold2),var(--gold));color:#111;padding:14px;font-weight:800;font-size:15px;cursor:pointer}.alert{padding:13px 15px;border-radius:9px;margin-bottom:18px}.error{background:#471b1b;border:1px solid #8f3838;color:#ffd1d1}.pending{background:#292514;border:1px solid #8e752b;color:#f4dfa0}.disabled{opacity:.6;cursor:not-allowed}@media(max-width:480px){.wrap{margin-top:20px}.card{border-radius:12px}}
</style></head><body><main class="wrap">
<a class="back" href="requests.php">← <?= e($t('ወደ ትዕዛዞች ተመለስ','Back to My Requests')) ?></a>
<section class="card"><h1><?= e($t('የእጅ ክፍያ ማረጋገጫ','Manual Payment Submission')) ?></h1>
<?php if ($error !== ''): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<?php if ($alreadySubmitted): ?><div class="alert pending"><?= e($paymentStatus === 'paid' ? $t('ክፍያው ተጸድቋል።','This payment has been approved.') : $t('የክፍያ ማስረጃው ተልኳል፤ Admin እያረጋገጠው ነው።','Your payment proof was submitted and is awaiting admin verification.')) ?></div><?php endif; ?>
<div class="summary"><p><strong><?= e($t('የፕሮጀክት ርዕስ፡','Project:')) ?></strong> <?= e($order['project_title'] ?? '') ?></p><p><strong><?= e($t('የትዕዛዝ ቁጥር፡','Request ID:')) ?></strong> #<?= (int)$request_id ?></p><p><strong><?= e($t('የሚከፈለው መጠን፡','Amount due:')) ?></strong> <span class="price"><?= number_format((float)($order['budget'] ?? 0), 2) ?> ETB</span></p></div>
<h2 style="font-size:17px;margin:0"><?= e($t('ክፍያውን ወደ አንዱ አካውንት ይላኩ','Transfer payment to one of these accounts')) ?></h2>
<div class="accounts"><?php foreach (STUDIO_BANK_ACCOUNTS as $acc): ?><div class="bank"><h3><?= e($acc['bank']) ?></h3><p><strong><?= e($t('የአካውንት ስም፡','Account name:')) ?></strong> <?= e($acc['account_name']) ?></p><p><strong><?= e($t('የአካውንት ቁጥር፡','Account number:')) ?></strong> <?= e($acc['account_number']) ?></p></div><?php endforeach; ?></div>
<?php if (!$alreadySubmitted): ?><form method="post" enctype="multipart/form-data">
<?= csrf_field() ?>
<div class="field"><label for="payment_method"><?= e($t('የተጠቀሙበት የክፍያ መንገድ','Payment method')) ?></label><select id="payment_method" name="payment_method" required><option value="">— <?= e($t('ይምረጡ','Choose')) ?> —</option><?php foreach ($methods as $method): ?><option value="<?= e($method) ?>" <?= post('payment_method') === $method ? 'selected' : '' ?>><?= e($method) ?></option><?php endforeach; ?></select></div>
<div class="field"><label for="payment_reference"><?= e($t('Transaction / Reference Number (ካለ)','Transaction / Reference Number (if available)')) ?></label><input id="payment_reference" name="payment_reference" type="text" maxlength="100" value="<?= e(post('payment_reference')) ?>" placeholder="e.g. transaction ID"><div class="hint"><?= e($t('በደረሰኝዎ ላይ የሚታየውን የግብይት ቁጥር ያስገቡ።','Enter the transaction ID shown on your receipt.')) ?></div></div>
<div class="field"><label for="payment_slip"><?= e($t('የክፍያ ደረሰኝ / Screenshot *','Payment receipt / screenshot *')) ?></label><input id="payment_slip" name="payment_slip" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" required><div class="hint"><?= e($t('JPG, PNG, WEBP ወይም PDF፤ ከፍተኛ 5 MB።','JPG, PNG, WEBP or PDF; maximum 5 MB.')) ?></div></div>
<button class="btn" type="submit"><?= e($t('ደረሰኝ ላክ ለማረጋገጫ →','Submit Receipt for Verification →')) ?></button>
</form><?php endif; ?>
<p class="hint" style="margin-top:18px"><?= e($t('ማስታወሻ፡ ክፍያው በAdmin እስኪረጋገጥ ድረስ ሁኔታው Paid አይሆንም።','Note: payment remains pending until an administrator verifies the receipt.')) ?></p>
</section></main></body></html>
