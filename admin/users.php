<?php
/**
 * NESERE Film Production - Admin Management (Safe & Bilingual)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

// ቋንቋ መቀየሪያ (Language Switcher)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();
$error = '';

// 1. አዲስ አድሚን መመዝገቢያ (Create Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'create_admin') {
    check_csrf();

    $full_name = trim(post('full_name'));
    $email     = trim(post('email'));
    $password  = post('password');
    $confirm   = post('confirm_password');

    if (empty($full_name) || empty($email) || empty($password)) {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን ሁሉንም አስፈላጊ መረጃዎች በትክክል ይሙሉ!' 
            : 'Please fill in all required fields!';
    } elseif ($password !== $confirm) {
        $error = ($current_lang === 'am') 
            ? 'ያስገቧቸው የይለፍ ቃሎች አይመሳሰሉም!' 
            : 'Passwords do not match!';
    } elseif (strlen($password) < 6) {
        $error = ($current_lang === 'am') 
            ? 'የይለፍ ቃል ቢያንስ 6 ፊደላት/ቁጥሮች መሆን አለበት!' 
            : 'Password must be at least 6 characters long!';
    } else {
        try {
            $chk = $db->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
            $chk->execute([$email]);
            if ($chk->fetch()) {
                $error = ($current_lang === 'am') 
                    ? 'ይህ ኢሜይል አስቀድሞ ተመዝግቧል!' 
                    : 'This email is already registered!';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                
                // የትኞቹ ኮለሞች እንዳሉ በመፈተሽ ደህንነቱ የተጠበቀ ማስገባት
                $cols = $db->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
                $has_admin_role = in_array('admin_role', $cols, true);
                $has_created_at = in_array('created_at', $cols, true);

                if ($has_admin_role && $has_created_at) {
                    $stmt = $db->prepare("INSERT INTO users (full_name, email, password_hash, role, admin_role, created_at) VALUES (?, ?, ?, 'admin', 'manager', NOW())");
                    $stmt->execute([$full_name, $email, $hashed]);
                } elseif ($has_admin_role) {
                    $stmt = $db->prepare("INSERT INTO users (full_name, email, password_hash, role, admin_role) VALUES (?, ?, ?, 'admin', 'manager')");
                    $stmt->execute([$full_name, $email, $hashed]);
                } else {
                    $stmt = $db->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
                    $stmt->execute([$full_name, $email, $hashed]);
                }

                flash('success', ($current_lang === 'am') 
                    ? 'አዲስ አድሚን በተሳካ ሁኔታ ተፈጥሯል!' 
                    : 'New Administrator created successfully!');
                
                redirect('users.php');
            }
        } catch (Exception $e) {
            $error = "DB Error: " . $e->getMessage();
        }
    }
}

// 2. አድሚን መሰረዝ (Delete Admin)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $token = $_GET['csrf'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        flash('danger', ($current_lang === 'am') ? 'የደህንነት ጊዜ አልፎበታል፤ እባክዎን እንደገና ይሞክሩ።' : 'Session expired, please try again.');
        redirect('users.php');
    }

    $del_id = (int)$_GET['id'];

    if ($del_id === (int)($_SESSION['user']['user_id'] ?? 0)) {
        flash('danger', ($current_lang === 'am') 
            ? 'የራስዎን መለያ እዚህ ላይ መሰረዝ አይችሉም!' 
            : 'You cannot delete your own account!');
    } else {
        try {
            $del_stmt = $db->prepare("DELETE FROM users WHERE user_id = ? AND role = 'admin'");
            $del_stmt->execute([$del_id]);

            flash('success', ($current_lang === 'am') 
                ? 'የአድሚን አካውንቱ ተሰርዟል!' 
                : 'Admin account deleted successfully!');
        } catch (Exception $e) {
            flash('danger', 'Error deleting: ' . $e->getMessage());
        }
    }
    redirect('users.php');
}

// ያሉትን አድሚኖች መጥራት
$admins = [];
try {
    $admins = $db->query("SELECT * FROM users WHERE role = 'admin' ORDER BY user_id DESC")->fetchAll();
} catch (Exception $e) {
    $admins = [];
}

// የቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'       => 'የአድሚን መለያዎች ማስተዳደሪያ | Nesere Films',
        'back_btn'         => 'ወደ ዳሽቦርድ ተመለስ',
        'main_title'       => 'የአስተዳዳሪዎች መለያ ማስተዳደሪያ',
        'form_heading'     => '+ አዲስ አድሚን መመዝገቢያ',
        'lbl_name'         => 'ሙሉ ስም *',
        'lbl_email'        => 'የኢሜይል አድራሻ *',
        'lbl_pass'         => 'የይለፍ ቃል (ቢያንስ 6) *',
        'lbl_confirm'      => 'የይለፍ ቃል ያረጋግጡ *',
        'btn_submit'       => 'አድሚኑን ፍጠር',
        'col_user'         => 'ስም እና ኢሜይል',
        'col_role'         => 'ሚና',
        'col_action'       => 'ድርጊት',
        'current_tag'      => '(አሁን ያለህበት)',
        'btn_delete'       => 'ሰርዝ',
        'confirm_del'      => 'ይህንን አድሚን መሰረዝ ይፈልጋሉ?',
        'no_records'       => 'ምንም የተመዘገበ አድሚን አልተገኘም።',
        'toggle_lang_text' => 'English'
    ],
    'en' => [
        'page_title'       => 'Administrator Management | Nesere Films',
        'back_btn'         => 'Back to Dashboard',
        'main_title'       => 'Administrator Accounts Management',
        'form_heading'     => '+ Add New Administrator',
        'lbl_name'         => 'Full Name *',
        'lbl_email'        => 'Email Address *',
        'lbl_pass'         => 'Password (min. 6 chars) *',
        'lbl_confirm'      => 'Confirm Password *',
        'btn_submit'       => 'Create Administrator',
        'col_user'         => 'Name & Email',
        'col_role'         => 'Role',
        'col_action'       => 'Action',
        'current_tag'      => '(Current Session)',
        'btn_delete'       => 'Delete',
        'confirm_del'      => 'Are you sure you want to delete this administrator?',
        'no_records'       => 'No administrator accounts found.',
        'toggle_lang_text' => 'አማርኛ'
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
    max-width: 1200px;
    margin: 40px auto 90px;
    padding: 0 20px;
}

.adm-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--adm-border);
    padding-bottom: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.adm-top-bar h1 {
    font-size: 24px;
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

.btn-lang-toggle {
    padding: 8px 14px;
    border-radius: 6px;
    background: #17181D;
    border: 1px solid var(--adm-border);
    color: var(--adm-gold);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.users-grid-layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 30px;
}

@media (max-width: 900px) {
    .users-grid-layout {
        grid-template-columns: 1fr;
    }
}

.form-box-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 30px 25px;
    height: fit-content;
}

.form-box-card h2 {
    font-size: 18px;
    color: var(--adm-white);
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--adm-border);
}

.adm-field {
    margin-bottom: 18px;
}

.adm-field label {
    display: block;
    font-size: 13px;
    color: #D1D1D6;
    margin-bottom: 7px;
    font-weight: 600;
}

.adm-field input {
    width: 100%;
    padding: 12px;
    background: #0E0F12;
    border: 1px solid var(--adm-border);
    border-radius: 6px;
    color: #FFFFFF;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
}

.adm-field input:focus {
    border-color: var(--adm-gold);
}

.btn-submit-gold {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, var(--adm-gold-light), var(--adm-gold));
    border: none;
    border-radius: 6px;
    color: #000;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 5px;
}

.table-box-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    overflow-x: auto;
}

.admin-list-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-list-table th {
    background: #16171D;
    padding: 14px 18px;
    font-size: 12.5px;
    color: var(--adm-gold);
    text-transform: uppercase;
    text-align: left;
    border-bottom: 1px solid var(--adm-border);
}

.admin-list-table td {
    padding: 16px 18px;
    font-size: 13.5px;
    color: #D1D1D6;
    border-bottom: 1px solid var(--adm-border);
}

.admin-role-badge {
    padding: 4px 10px;
    border-radius: 20px;
    background: rgba(212, 175, 55, 0.15);
    border: 1px solid var(--adm-gold);
    color: var(--adm-gold-light);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.btn-del-user {
    color: #ff6b6b;
    text-decoration: none;
    font-size: 12.5px;
    padding: 5px 10px;
    border-radius: 4px;
    background: rgba(231, 76, 60, 0.1);
    border: 1px solid rgba(231, 76, 60, 0.25);
    display: inline-block;
}

.btn-del-user:hover {
    background: #e74c3c;
    color: #fff;
}
</style>

<div class="adm-container">
    <div class="adm-top-bar">
        <div>
            <a href="index.php" class="adm-back-link"><i class="fa fa-arrow-left"></i> <?= e($str['back_btn']) ?></a>
            <h1><?= e($str['main_title']) ?></h1>
        </div>

        <div>
            <a href="users.php?lang=<?= ($current_lang === 'am') ? 'en' : 'am' ?>" class="btn-lang-toggle">
                <i class="fa fa-globe"></i> <?= e($str['toggle_lang_text']) ?>
            </a>
        </div>
    </div>

    <?php show_flash(); ?>

    <?php if ($error): ?>
        <div style="background: rgba(231,76,60,0.15); border: 1px solid #e74c3c; color: #ff6b6b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13.5px;">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <div class="users-grid-layout">
        <div class="form-box-card">
            <h2><?= e($str['form_heading']) ?></h2>

            <form method="POST" action="users.php">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_admin">

                <div class="adm-field">
                    <label><?= e($str['lbl_name']) ?></label>
                    <input type="text" name="full_name" placeholder="Abebe Kebede" required>
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_email']) ?></label>
                    <input type="email" name="email" placeholder="admin2@nesere.com" required>
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_pass']) ?></label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_confirm']) ?></label>
                    <input type="password" name="confirm_password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-submit-gold"><?= e($str['btn_submit']) ?></button>
            </form>
        </div>

        <div class="table-box-card">
            <table class="admin-list-table">
                <thead>
                    <tr>
                        <th><?= e($str['col_user']) ?></th>
                        <th><?= e($str['col_role']) ?></th>
                        <th style="width: 90px;"><?= e($str['col_action']) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $adm): ?>
                            <tr>
                                <td>
                                    <strong style="color: #fff; font-size: 14.5px;"><?= e($adm['full_name'] ?? $adm['name'] ?? 'Admin') ?></strong><br>
                                    <span style="font-size: 12.5px; color: var(--adm-gold);"><?= e($adm['email']) ?></span>
                                </td>
                                <td>
                                    <span class="admin-role-badge"><?= e($adm['admin_role'] ?? $adm['role'] ?? 'ADMIN') ?></span>
                                </td>
                                <td>
                                    <?php if ((int)$adm['user_id'] !== (int)($_SESSION['user']['user_id'] ?? 0)): ?>
                                        <a href="users.php?action=delete&id=<?= (int)$adm['user_id'] ?>&csrf=<?= csrf() ?>" 
                                           class="btn-del-user" 
                                           onclick="return confirm('<?= e($str['confirm_del']) ?>');">
                                            <i class="fa fa-trash"></i> <?= e($str['btn_delete']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span style="font-size: 11px; color: var(--adm-muted);"><?= e($str['current_tag']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 30px; color: var(--adm-muted);">
                                <?= e($str['no_records']) ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/../includes/footer.php'; 
?>