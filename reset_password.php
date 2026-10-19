<?php
/**
 * NESERE Film Production - Set New Password Page
 * Fully Styled Dark (#0B0B0B) & Eagle Gold (#D4AF37)
 */
require_once __DIR__ . '/config/config.php';

// Multi-language Logic
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();
$error = '';
$email = $_SESSION['reset_email'] ?? '';

// ኢሜይል ከሌለ በቀጥታ ወደ forgot_password.php ይመልሰዋል
if (empty($email)) {
    redirect('forgot_password.php');
}

// ፎርሙ ሲላክ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (function_exists('check_csrf')) {
        check_csrf();
    }

    $new_password     = post('password');
    $confirm_password = post('confirm_password');

    if (empty($new_password) || strlen($new_password) < 6) {
        $error = ($current_lang === 'am') 
            ? 'አዲሱ የይለፍ ቃል ቢያንስ 6 ፊደላት/ቁጥሮች መሆን አለበት!' 
            : 'Password must be at least 6 characters long!';
    } elseif ($new_password !== $confirm_password) {
        $error = ($current_lang === 'am') 
            ? 'ያስገቧቸው የይለፍ ቃሎች አይመሳሰሉም!' 
            : 'Passwords do not match!';
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        unset($_SESSION['reset_token'], $_SESSION['reset_email']);

        flash('success', ($current_lang === 'am') 
            ? 'የይለፍ ቃልዎ በተሳካ ሁኔታ ተቀይሯል! አሁን መግባት ይችላሉ።' 
            : 'Your password has been reset successfully! You can now log in.');

        redirect('login.php');
    }
}

$page_title = ($current_lang === 'am') ? 'አዲስ የይለፍ ቃል ማስተካከያ | Nesere Films' : 'Set New Password | Nesere Films';
require_once __DIR__ . '/includes/header.php';
?>

<style>
.nesere-auth-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    background: radial-gradient(circle at 50% 15%, rgba(212, 175, 55, 0.08) 0%, rgba(11, 11, 11, 1) 70%);
}

.nesere-auth-box {
    width: 100%;
    max-width: 480px;
    background: #121316;
    border: 1px solid #1E2026;
    border-radius: 14px;
    padding: 40px 35px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.7);
    box-sizing: border-box;
}

.auth-badge-pill {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 30px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid #D4AF37;
    color: #F2D16B;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 14px;
}

.auth-title {
    font-size: 26px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0 0 10px 0;
}

.auth-subtitle {
    font-size: 13.5px;
    color: #8E929D;
    line-height: 1.5;
    margin: 0 0 14px 0;
}

.user-target-tag {
    display: inline-block;
    color: #D4AF37;
    font-size: 13px;
    font-weight: 600;
    background: rgba(212, 175, 55, 0.08);
    padding: 4px 12px;
    border-radius: 6px;
    border: 1px solid rgba(212, 175, 55, 0.25);
    margin-bottom: 25px;
}

.input-field-group {
    margin-bottom: 20px;
    text-align: left;
}

.input-field-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #D1D1D6;
    margin-bottom: 8px;
}

.input-field-group input {
    width: 100%;
    padding: 13px 15px;
    background: #0B0B0D;
    border: 1px solid #232630;
    border-radius: 8px;
    color: #FFFFFF;
    font-size: 14.5px;
    outline: none;
    box-sizing: border-box;
    transition: all 0.25s ease;
}

.input-field-group input:focus {
    border-color: #D4AF37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
}

.btn-auth-action {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #F2D16B 0%, #D4AF37 100%);
    border: none;
    border-radius: 8px;
    color: #000000;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.25);
}

.btn-auth-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
}

.auth-bottom-nav {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #1E2026;
    font-size: 13.5px;
}

.auth-bottom-nav a {
    color: #D4AF37;
    text-decoration: none;
    font-weight: 600;
}

.auth-bottom-nav a:hover {
    text-decoration: underline;
}

.alert-danger-box {
    background: rgba(231, 76, 60, 0.15);
    border: 1px solid #e74c3c;
    color: #ff6b6b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13.5px;
    text-align: center;
}
</style>

<div class="nesere-auth-page">
    <div class="nesere-auth-box">
        <div style="text-align: center;">
            <span class="auth-badge-pill"><?= ($current_lang === 'am') ? 'የይለፍ ቃል ማደሻ' : 'PASSWORD RESET' ?></span>
            <h1 class="auth-title"><?= ($current_lang === 'am') ? 'አዲስ የይለፍ ቃል ያስገቡ' : 'Set New Password' ?></h1>
            <p class="auth-subtitle"><?= ($current_lang === 'am') ? 'ለመለያዎ አዲስ እና አስተማማኝ የይለፍ ቃል ይምረጡ' : 'Choose a secure new password for your account' ?></p>
            <div class="user-target-tag"><?= htmlspecialchars($email) ?></div>
        </div>

        <?php if ($error): ?>
            <div class="alert-danger-box">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="reset_password.php">
            <?php if (function_exists('csrf_field')) echo csrf_field(); ?>

            <div class="input-field-group">
                <label><?= ($current_lang === 'am') ? 'አዲስ የይለፍ ቃል (ቢያንስ 6 ፊደላት) *' : 'New Password (min. 6 chars) *' ?></label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="input-field-group">
                <label><?= ($current_lang === 'am') ? 'አዲሱን የይለፍ ቃል ያረጋግጡ *' : 'Confirm New Password *' ?></label>
                <input type="password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-auth-action"><?= ($current_lang === 'am') ? 'የይለፍ ቃሉን አድስ' : 'Update Password' ?></button>
        </form>

        <div class="auth-bottom-nav">
            <a href="login.php"><i class="fa fa-arrow-left"></i> <?= ($current_lang === 'am') ? 'ወደ መግቢያ ገጽ ተመለስ' : 'Back to Login' ?></a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>