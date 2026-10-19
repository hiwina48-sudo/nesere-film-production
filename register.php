<?php
/**
 * NESERE Film Production - User Registration Page
 * Pure Customer Registration (Admins are created only from admin/users.php)
 * Palette: Dark (#0B0B0B) & Eagle Gold (#D4AF37)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';

// አስቀድሞ Login ያደረገ ሰው ካለ ወደ ዳሽቦርዱ መምራት
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        redirect('admin/index.php');
    } else {
        redirect('customer/dashboard.php');
    }
}

// Multi-language Logic (አማርኛ / English)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();
$error = '';

// ፎርሙ ሲላክ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (function_exists('check_csrf')) {
        check_csrf();
    }

    $full_name = trim(post('full_name'));
    $email     = trim(post('email'));
    $phone     = trim(post('phone'));
    $password  = post('password');
    $confirm   = post('confirm_password');

    if (empty($full_name) || empty($email) || empty($password)) {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን አስፈላጊ የሆኑትን ክፍት ቦታዎች በሙሉ ይሙሉ!' 
            : 'Please fill in all required fields!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን ትክክለኛ የኢሜይል አድራሻ ያስገቡ!' 
            : 'Please enter a valid email address!';
    } elseif (strlen($password) < 6) {
        $error = ($current_lang === 'am') 
            ? 'የይለፍ ቃል ቢያንስ 6 ፊደላት/ቁጥሮች መሆን አለበት!' 
            : 'Password must be at least 6 characters long!';
    } elseif ($password !== $confirm) {
        $error = ($current_lang === 'am') 
            ? 'ያስገቧቸው የይለፍ ቃሎች አይመሳሰሉም!' 
            : 'Passwords do not match!';
    } else {
        try {
            // ኢሜይሉ አስቀድሞ መኖሩን ማረጋገጥ
            $check = $db->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
            $check->execute([$email]);
            if ($check->fetch()) {
                $error = ($current_lang === 'am') 
                    ? 'ይህ ኢሜይል አስቀድሞ ተመዝግቧል! እባክዎ ይግቡ ወይም ሌላ ኢሜይል ይጠቀሙ።' 
                    : 'This email is already registered. Please login or use another email.';
            } else {
                $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
                
                // በዚህ ገጽ በኩል የሚመዘገብ ማንኛውም ሰው 'customer' ብቻ ይሆናል
                $stmt = $db->prepare("INSERT INTO users (full_name, email, phone, password_hash, role, created_at) VALUES (?, ?, ?, ?, 'customer', NOW())");
                $stmt->execute([$full_name, $email, $phone, $hashed_pw]);

                $new_id = $db->lastInsertId();

                // Session መመዝገብ
                $_SESSION['user_id']   = $new_id;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['email']     = $email;
                $_SESSION['role']      = 'customer';

                flash('success', ($current_lang === 'am') 
                    ? 'እንኳን ደህና መጡ! መለያዎ በተሳካ ሁኔታ ተፈጥሯል።' 
                    : 'Welcome! Your account has been created successfully.');
                
                redirect('customer/dashboard.php');
            }
        } catch (PDOException $e) {
            $error = "የዳታቤዝ ስህተት: " . $e->getMessage();
        }
    }
}

// የቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'   => 'መለያ ይፍጠሩ | Nesere Film Production',
        'badge'        => 'የደንበኛ መለያ',
        'heading'      => 'አዲስ መለያ ይፍጠሩ',
        'subheading'   => 'የፕሮዳክሽን ጥያቄዎችዎን በቀላሉ ለመከታተል እና ከኔሰሬ ጋር ለመስራት ይመዝገቡ',
        'lbl_name'     => 'ሙሉ ስም *',
        'lbl_email'    => 'ኢሜይል አድራሻ *',
        'lbl_phone'    => 'ስልክ ቁጥር',
        'lbl_pass'     => 'የይለፍ ቃል (ቢያንስ 6 ፊደላት) *',
        'lbl_confirm'  => 'የይለፍ ቃል ያረጋግጡ *',
        'btn_submit'   => 'ይመዝገቡ',
        'already'      => 'አስቀድመው አካውንት አሎት?',
        'login_link'   => 'እዚህ ይግቡ'
    ],
    'en' => [
        'page_title'   => 'Register | Nesere Film Production',
        'badge'        => 'CLIENT REGISTRATION',
        'heading'      => 'Create Your Client Account',
        'subheading'   => 'Sign up to submit production orders, track requests, and collaborate with us',
        'lbl_name'     => 'Full Name *',
        'lbl_email'    => 'Email Address *',
        'lbl_phone'    => 'Phone Number',
        'lbl_pass'     => 'Password (min. 6 characters) *',
        'lbl_confirm'  => 'Confirm Password *',
        'btn_submit'   => 'Create Account',
        'already'      => 'Already have an account?',
        'login_link'   => 'Log in here'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/includes/header.php';
?>

<style>
:root {
    --nesere-black: #0B0B0B;
    --nesere-card: #121316;
    --nesere-border: #1E2026;
    --nesere-gold: #D4AF37;
    --nesere-gold-light: #F2D16B;
    --nesere-muted: #8E929D;
    --nesere-white: #FFFFFF;
}

.auth-wrapper {
    max-width: 520px;
    margin: 50px auto 80px;
    padding: 0 20px;
}

.auth-card {
    background: var(--nesere-card);
    border: 1px solid var(--nesere-border);
    border-radius: 12px;
    padding: 40px 35px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    background: rgba(212, 175, 55, 0.08);
    border: 1px solid var(--nesere-gold);
    color: var(--nesere-gold-light);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 12px;
    text-transform: uppercase;
}

.auth-header h1 {
    font-size: 26px;
    color: #FFFFFF;
    margin: 0 0 8px 0;
}

.auth-header p {
    font-size: 13.5px;
    color: var(--nesere-muted);
    line-height: 1.5;
    margin: 0;
}

.form-group-auth {
    margin-bottom: 18px;
}

.form-group-auth label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #D1D1D6;
    margin-bottom: 7px;
}

.form-group-auth input {
    width: 100%;
    padding: 12px 14px;
    background: #0E0F12;
    border: 1px solid var(--nesere-border);
    border-radius: 6px;
    color: #FFFFFF;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s;
}

.form-group-auth input:focus {
    border-color: var(--nesere-gold);
}

.btn-auth-submit {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, var(--nesere-gold-light), var(--nesere-gold));
    border: none;
    border-radius: 6px;
    color: #000;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 10px;
    transition: transform 0.2s;
}

.btn-auth-submit:hover {
    transform: translateY(-2px);
}

.auth-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--nesere-border);
    font-size: 13.5px;
    color: var(--nesere-muted);
}

.auth-footer a {
    color: var(--nesere-gold);
    text-decoration: none;
    font-weight: 600;
}

.auth-footer a:hover {
    text-decoration: underline;
}

.error-banner {
    background: rgba(231, 76, 60, 0.15);
    border: 1px solid #e74c3c;
    color: #e74c3c;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 13.5px;
    text-align: center;
}
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-badge"><?= e($str['badge']) ?></span>
            <h1><?= e($str['heading']) ?></h1>
            <p><?= e($str['subheading']) ?></p>
        </div>

        <?php show_flash(); ?>

        <?php if ($error): ?>
            <div class="error-banner">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <?php if (function_exists('csrf_field')) echo csrf_field(); ?>

            <div class="form-group-auth">
                <label><?= e($str['lbl_name']) ?></label>
                <input type="text" name="full_name" value="<?= val('full_name') ?>" required>
            </div>

            <div class="form-group-auth">
                <label><?= e($str['lbl_email']) ?></label>
                <input type="email" name="email" value="<?= val('email') ?>" required>
            </div>

            <div class="form-group-auth">
                <label><?= e($str['lbl_phone']) ?></label>
                <input type="text" name="phone" value="<?= val('phone') ?>" placeholder="+251 9...">
            </div>

            <div class="form-group-auth">
                <label><?= e($str['lbl_pass']) ?></label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group-auth">
                <label><?= e($str['lbl_confirm']) ?></label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-auth-submit"><?= e($str['btn_submit']) ?></button>
        </form>

        <div class="auth-footer">
            <?= e($str['already']) ?> <a href="login.php"><?= e($str['login_link']) ?></a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>