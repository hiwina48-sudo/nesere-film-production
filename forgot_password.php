<?php
/**
 * NESERE Film Production - Password Recovery Page
 * Dark (#0B0B0B) & Eagle Gold (#D4AF37)
 */
require_once __DIR__ . '/config/config.php';

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (function_exists('check_csrf')) {
        check_csrf();
    }

    $email = trim(post('email'));

    if (!empty($email)) {
        $stmt = $db->prepare("SELECT user_id, full_name FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['reset_token'] = $token;
            $_SESSION['reset_email'] = $email;

            redirect('reset_password.php?token=' . $token);
        } else {
            $error = ($current_lang === 'am') 
                ? 'በዚህ ኢሜይል የተመዘገበ መለያ አልተገኘም!' 
                : 'No account found with this email address!';
        }
    } else {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን ኢሜይልዎን ያስገቡ!' 
            : 'Please enter your email address!';
    }
}

$page_title = ($current_lang === 'am') ? 'የይለፍ ቃል መመለሻ | Nesere Films' : 'Forgot Password | Nesere Films';
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
    line-height: 1.6;
    margin: 0 0 25px 0;
}

.input-field-group {
    margin-bottom: 22px;
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
    margin-top: 6px;
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
            <span class="auth-badge-pill"><?= ($current_lang === 'am') ? 'የይለፍ ቃል መልሶ ማግኛ' : 'PASSWORD RECOVERY' ?></span>
            <h1 class="auth-title"><?= ($current_lang === 'am') ? 'የይለፍ ቃልዎን ረሱ?' : 'Reset Your Password' ?></h1>
            <p class="auth-subtitle"><?= ($current_lang === 'am') ? 'የተመዘገቡበትን ኢሜይል ያስገቡ እና ወደ አዲሱ የይለፍ ቃል መፍጠሪያ ገጽ እንወስድዎታለን።' : 'Enter your registered email address to set a new password.' ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert-danger-box">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="forgot_password.php">
            <?php if (function_exists('csrf_field')) echo csrf_field(); ?>

            <div class="input-field-group">
                <label><?= ($current_lang === 'am') ? 'የኢሜይል አድራሻ *' : 'Email Address *' ?></label>
                <input type="email" name="email" placeholder="admin@nesere.com" required>
            </div>

            <button type="submit" class="btn-auth-action"><?= ($current_lang === 'am') ? 'ቀጥል (Continue)' : 'Continue' ?></button>
        </form>

        <div class="auth-bottom-nav">
            <a href="login.php"><i class="fa fa-arrow-left"></i> <?= ($current_lang === 'am') ? 'ወደ መግቢያ ገጽ ተመለስ' : 'Back to Login' ?></a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>