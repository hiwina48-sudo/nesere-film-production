<?php
/**
 * NESERE Film Production - Secure Login Page
 * Fully Compatible with config/config.php session structure
 */
require_once __DIR__ . '/config/config.php';

// አስቀድሞ Login ያደረገ ተጠቃሚ ካለ ወደ ሚገባበት ገጽ መምራት
if (logged()) {
    if (admin()) {
        redirect($base . 'admin/index.php');
    } else {
        redirect($base . 'customer/dashboard.php');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (function_exists('check_csrf')) {
        check_csrf();
    }

    $email    = trim(post('email'));
    $password = post('password');

    if (!empty($email) && !empty($password)) {
        // ተጠቃሚውን በኢሜይል መፈለግ
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // በዳታቤዝ ውስጥ የተቀመጠውን password hash መፈተሽ
        $db_hash = $user['password_hash'] ?? ($user['password'] ?? '');

        if ($user && !empty($db_hash) && password_verify($password, $db_hash)) {
            // በ config.php ውስጥ ባለው logged() እና admin() መሰረት በ array ማስቀመጥ
            $_SESSION['user'] = [
                'user_id'    => $user['user_id'],
                'full_name'  => $user['full_name'] ?? ($user['name'] ?? 'User'),
                'email'      => $user['email'],
                'role'       => $user['role'] ?? 'customer',
                'admin_role' => $user['admin_role'] ?? 'super_admin'
            ];

            // ለቀድሞ ፔጆች ድጋፍ ነጠላ ሴሽኖችንም አብሮ ማስቀመጥ
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['user_name'] = $_SESSION['user']['full_name'];
            $_SESSION['email']     = $user['email'];
            $_SESSION['role']      = $_SESSION['user']['role'];

            // ሚናውን አይቶ ወደሚገባበት መምራት
            if (admin()) {
                flash('success', ($current_lang === 'am') ? 'እንኳን ደህና መጡ አድሚን!' : 'Welcome back, Admin!');
                redirect($base . 'admin/index.php');
            } else {
                flash('success', ($current_lang === 'am') ? 'በተሳካ ሁኔታ ገብተዋል!' : 'Logged in successfully!');
                
                // ከመግባቱ በፊት የጠየቀው ገጽ ካለ ወደዚያው መምራት
                $target = $_SESSION['after_login'] ?? ($base . 'customer/dashboard.php');
                unset($_SESSION['after_login']);
                redirect($target);
            }
        } else {
            $error = ($current_lang === 'am') 
                ? 'ያስገቡት ኢሜይል ወይም የይለፍ ቃል የተሳሳተ ነው!' 
                : 'Invalid email or password!';
        }
    } else {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን ኢሜይል እና የይለፍ ቃል ያስገቡ!' 
            : 'Please enter both email and password!';
    }
}

// ቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'     => 'ግባ | Nesere Film Production',
        'badge'          => 'የመለያ መግቢያ',
        'heading'        => 'ወደ መለያዎ ይግቡ',
        'subheading'     => 'የፕሮዳክሽን ዳሽቦርድዎን ለመጠቀም መረጃዎን ያስገቡ',
        'lbl_email'      => 'ኢሜይል አድራሻ',
        'lbl_pass'       => 'የይለፍ ቃል',
        'forgot_pass'    => 'የይለፍ ቃል ረሱ?',
        'btn_login'      => 'ግባ',
        'no_account'     => 'መለያ የሎትም?',
        'reg_link'       => 'እዚህ ይመዝገቡ'
    ],
    'en' => [
        'page_title'     => 'Login | Nesere Film Production',
        'badge'          => 'ACCOUNT ACCESS',
        'heading'        => 'Sign In to Your Account',
        'subheading'     => 'Access your production dashboard and manage projects',
        'lbl_email'      => 'Email Address',
        'lbl_pass'       => 'Password',
        'forgot_pass'    => 'Forgot Password?',
        'btn_login'      => 'Sign In',
        'no_account'     => "Don't have an account?",
        'reg_link'       => 'Register here'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/includes/header.php';
?>

<style>
.login-wrapper {
    max-width: 480px;
    margin: 60px auto 100px;
    padding: 0 20px;
}

.login-card {
    background: #121316;
    border: 1px solid #1E2026;
    border-radius: 12px;
    padding: 40px 35px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    background: rgba(212, 175, 55, 0.08);
    border: 1px solid #D4AF37;
    color: #F2D16B;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 12px;
    text-transform: uppercase;
}

.login-header h1 {
    font-size: 26px;
    color: #FFFFFF;
    margin: 0 0 8px 0;
}

.login-header p {
    font-size: 13.5px;
    color: #8E929D;
    line-height: 1.5;
    margin: 0;
}

.form-group-login {
    margin-bottom: 18px;
}

.form-group-login label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #D1D1D6;
    margin-bottom: 7px;
}

.form-group-login input {
    width: 100%;
    padding: 12px 14px;
    background: #0E0F12;
    border: 1px solid #1E2026;
    border-radius: 6px;
    color: #FFFFFF;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s;
}

.form-group-login input:focus {
    border-color: #D4AF37;
}

.login-options-flex {
    display: flex;
    justify-content: flex-end;
    margin-top: -6px;
    margin-bottom: 22px;
}

.forgot-pass-link {
    color: #D4AF37;
    font-size: 13px;
    text-decoration: none;
    font-weight: 500;
}

.forgot-pass-link:hover {
    text-decoration: underline;
}

.btn-login-submit {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #F2D16B, #D4AF37);
    border: none;
    border-radius: 6px;
    color: #000;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.2s;
}

.btn-login-submit:hover {
    transform: translateY(-2px);
}

.login-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #1E2026;
    font-size: 13.5px;
    color: #8E929D;
}

.login-footer a {
    color: #D4AF37;
    text-decoration: none;
    font-weight: 600;
}

.login-footer a:hover {
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

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <span class="login-badge"><?= e($str['badge']) ?></span>
            <h1><?= e($str['heading']) ?></h1>
            <p><?= e($str['subheading']) ?></p>
        </div>

        <?php show_flash(); ?>

        <?php if ($error): ?>
            <div class="error-banner">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <?php if (function_exists('csrf_field')) echo csrf_field(); ?>

            <div class="form-group-login">
                <label><?= e($str['lbl_email']) ?></label>
                <input type="email" name="email" value="<?= val('email') ?>" placeholder="admin@nesere.com" required>
            </div>

            <div class="form-group-login">
                <label><?= e($str['lbl_pass']) ?></label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="login-options-flex">
                <a href="forgot_password.php" class="forgot-pass-link"><?= e($str['forgot_pass']) ?></a>
            </div>

            <button type="submit" class="btn-login-submit"><?= e($str['btn_login']) ?></button>
        </form>

        <div class="login-footer">
            <?= e($str['no_account']) ?> <a href="register.php"><?= e($str['reg_link']) ?></a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>