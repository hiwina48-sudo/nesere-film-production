<?php
/**
 * NESERE Film Production - Admin Authentication & Authorization Check
 * Included at the top of every page in the /admin folder.
 */

require_once __DIR__ . '/../config/config.php';

// 1. ሴሽን መጀመሩን ማረጋገጥ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. ተጠቃሚው መግባቱን (Login ማድረጉን) እና ሚናው 'admin' መሆኑን ማረጋገጥ
if (function_exists('require_admin')) {
    require_admin();
} else {
    // ፈንክሽኑ ባይኖር እንኳን ራሱን ችሎ እንዳይበላሽ
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        flash('error', 'ይህንን ገጽ ለመጠቀም አድሚን ሆነው መግባት አለብዎት!');
        header("Location: ../login.php");
        exit;
    }
}

// 3. የአድሚን ሴሽን በየጊዜው እንዲታደስ ማድረግ (Session Fixation Attack ለመከላከል)
if (!isset($_SESSION['last_admin_regen'])) {
    $_SESSION['last_admin_regen'] = time();
} elseif (time() - $_SESSION['last_admin_regen'] > 1800) { // በየ 30 ደቂቃው ሴሽኑን ያድሳል
    session_regenerate_id(true);
    $_SESSION['last_admin_regen'] = time();
}