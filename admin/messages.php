<?php
/**
 * NESERE Film Production - Admin Messages Manager
 * View, Read, and Delete Customer Contact Messages
 * Luxury Black & Gold Theme | Bilingual (Amharic & English)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

// ቋንቋ መቀየሪያ
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();

// የትኛው ሰንጠረዥ እንዳለ ማረጋገጥ (messages ወይስ contact_messages)
$msg_table = 'messages';
try {
    $db->query("SELECT 1 FROM messages LIMIT 1");
} catch (Exception $e) {
    try {
        $db->query("SELECT 1 FROM contact_messages LIMIT 1");
        $msg_table = 'contact_messages';
    } catch (Exception $ex) {}
}

// 1. እንደተነበበ ምልክት ማድረግ (Mark as Read)
if (isset($_GET['action']) && $_GET['action'] === 'read' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $db->prepare("UPDATE {$msg_table} SET is_read = 1 WHERE id = ?")->execute([$id]);
    } catch (Exception $e) {}
    redirect('messages.php');
}

// 2. መልዕክት መሰረዝ (Delete Message)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $token = $_GET['csrf'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        flash('danger', ($current_lang === 'am') ? 'የደህንነት ጊዜ አልፎበታል፤ እባክዎን እንደገና ይሞክሩ።' : 'Session expired, please try again.');
        redirect('messages.php');
    }

    $del_id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM {$msg_table} WHERE id = ?");
        $stmt->execute([$del_id]);

        flash('success', ($current_lang === 'am') ? 'መልዕክቱ በተሳካ ሁኔታ ተሰርዟል!' : 'Message deleted successfully!');
    } catch (Exception $e) {
        flash('danger', 'Error: ' . $e->getMessage());
    }
    redirect('messages.php');
}

// መልዕክቶችን መጥራት
$messages = [];
try {
    $messages = $db->query("SELECT * FROM {$msg_table} ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $messages = [];
}

$lang_dict = [
    'am' => [
        'page_title'   => 'የመልዕክቶች ማስተዳደሪያ | Nesere Films',
        'back_btn'     => 'ወደ ዳሽቦርድ ተመለስ',
        'main_title'   => 'የደንበኞች መልዕክቶችና አስተያየቶች',
        'sub_title'    => 'ከኮንታክት ቅጽ በኩል የገቡ ቀጥተኛ መልዕክቶች ዝርዝር',
        'col_sender'   => 'ላኪ (Sender)',
        'col_subject'  => 'ርዕስ እና መልዕክት',
        'col_date'     => 'የደረሰበት ቀን',
        'col_action'   => 'ድርጊት',
        'mark_read'    => 'አንብቤዋለሁ',
        'btn_delete'   => 'ሰርዝ',
        'confirm_del'  => 'ይህንን መልዕክት መሰረዝ ይፈልጋሉ?',
        'no_messages'  => 'እስካሁን ምንም የደረሰ መልዕክት የለም።',
        'badge_unread' => 'አዲስ',
        'lang_toggle'  => 'English'
    ],
    'en' => [
        'page_title'   => 'Messages Manager | Nesere Films',
        'back_btn'     => 'Back to Dashboard',
        'main_title'   => 'Client Inquiries & Messages',
        'sub_title'    => 'Direct communications submitted via the contact form',
        'col_sender'   => 'Sender',
        'col_subject'  => 'Subject & Content',
        'col_date'     => 'Date Received',
        'col_action'   => 'Action',
        'mark_read'    => 'Mark Read',
        'btn_delete'   => 'Delete',
        'confirm_del'  => 'Are you sure you want to delete this message?',
        'no_messages'  => 'No incoming messages found.',
        'badge_unread' => 'NEW',
        'lang_toggle'  => 'አማርኛ'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/../includes/header.php';
?>

<style>
:root {
    --adm-card: #121316;
    --adm-border: #1E2026;
    --adm-gold: #D4AF37;
    --adm-gold-light: #F2D16B;
    --adm-muted: #8E929D;
    --adm-white: #FFFFFF;
}

.adm-container {
    max-width: 1260px;
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
}

.msg-card-box {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    overflow-x: auto;
}

.msg-table {
    width: 100%;
    border-collapse: collapse;
}

.msg-table th {
    background: #16171D;
    padding: 14px 18px;
    font-size: 12.5px;
    color: var(--adm-gold);
    text-transform: uppercase;
    text-align: left;
    border-bottom: 1px solid var(--adm-border);
}

.msg-table td {
    padding: 18px;
    font-size: 13.5px;
    color: #D1D1D6;
    border-bottom: 1px solid var(--adm-border);
    vertical-align: top;
}

.msg-unread {
    background: rgba(212, 175, 55, 0.04);
}

.unread-badge {
    background: var(--adm-gold);
    color: #000;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 12px;
    margin-left: 6px;
}

.msg-content-preview {
    background: #0E0F12;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid var(--adm-border);
    margin-top: 8px;
    line-height: 1.5;
    color: #C0C0C8;
}

.btn-action-read {
    color: var(--adm-gold);
    text-decoration: none;
    font-size: 12px;
    display: inline-block;
    margin-bottom: 8px;
}

.btn-del-msg {
    color: #ff6b6b;
    text-decoration: none;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 4px;
    background: rgba(231, 76, 60, 0.1);
    border: 1px solid rgba(231, 76, 60, 0.25);
    display: inline-block;
}

.btn-del-msg:hover {
    background: #e74c3c;
    color: #fff;
}
</style>

<div class="adm-container">
    <div class="adm-top-bar">
        <div>
            <a href="index.php" class="adm-back-link"><i class="fa fa-arrow-left"></i> <?= e($str['back_btn']) ?></a>
            <h1><?= e($str['main_title']) ?></h1>
            <p style="color: var(--adm-muted); font-size: 13px; margin: 4px 0 0 0;"><?= e($str['sub_title']) ?></p>
        </div>

        <div>
            <a href="messages.php?lang=<?= ($current_lang === 'am') ? 'en' : 'am' ?>" class="btn-lang-toggle">
                <i class="fa fa-globe"></i> <?= e($str['lang_toggle']) ?>
            </a>
        </div>
    </div>

    <?php show_flash(); ?>

    <div class="msg-card-box">
        <table class="msg-table">
            <thead>
                <tr>
                    <th style="width: 250px;"><?= e($str['col_sender']) ?></th>
                    <th><?= e($str['col_subject']) ?></th>
                    <th style="width: 140px;"><?= e($str['col_date']) ?></th>
                    <th style="width: 110px;"><?= e($str['col_action']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $m): ?>
                        <?php $is_unread = empty($m['is_read']); ?>
                        <tr class="<?= $is_unread ? 'msg-unread' : '' ?>">
                            <td>
                                <strong style="color: #fff; font-size: 14.5px;"><?= e($m['name'] ?? 'Guest') ?></strong>
                                <?php if ($is_unread): ?>
                                    <span class="unread-badge"><?= e($str['badge_unread']) ?></span>
                                <?php endif; ?>
                                <br>
                                <span style="font-size: 12.5px; color: var(--adm-gold);"><?= e($m['email'] ?? '—') ?></span><br>
                                <span style="font-size: 12px; color: var(--adm-muted);"><?= e($m['phone'] ?? '') ?></span>
                            </td>
                            <td>
                                <?php if (!empty($m['subject'])): ?>
                                    <strong style="color: #FFFFFF; font-size: 14px;"><?= e($m['subject']) ?></strong>
                                <?php endif; ?>
                                <div class="msg-content-preview">
                                    <?= nl2br(e($m['message'] ?? '')) ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 12px; color: var(--adm-muted);">
                                    <?= !empty($m['created_at']) ? date('M d, Y h:i A', strtotime($m['created_at'])) : '—' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($is_unread): ?>
                                    <a href="messages.php?action=read&id=<?= (int)$m['id'] ?>" class="btn-action-read">
                                        <i class="fa fa-check"></i> <?= e($str['mark_read']) ?>
                                    </a><br>
                                <?php endif; ?>

                                <a href="messages.php?action=delete&id=<?= (int)$m['id'] ?>&csrf=<?= csrf() ?>" 
                                   class="btn-del-msg" 
                                   onclick="return confirm('<?= e($str['confirm_del']) ?>');">
                                    <i class="fa fa-trash"></i> <?= e($str['btn_delete']) ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 45px; color: var(--adm-muted);">
                            <?= e($str['no_messages']) ?>
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