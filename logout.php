<?php
/**
 * NESERE Film Production - User Logout Handler
 * Clears sessions, cookies, and redirects safely to login page
 */
require_once __DIR__ . '/config/config.php';

// 1. የሴሽን ዳታዎችን በሙሉ ባዶ ማድረግ
$_SESSION = [];

// 2. ሴሽን ኩኪውን ከብሮውዘር ላይ ማጥፋት
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. ሴሽኑን ሙሉ በሙሉ ማውደም
session_destroy();

// 4. አዲስ ንጹህ ሴሽን ከፍቶ የስኬት መልዕክት ማስቀመጥ
session_start();
flash('info', 'በተሳካ ሁኔታ ወጥተዋል። እንደገና ለመጠቀም እባክዎን ይግቡ።');

// 5. ወደ መግቢያ ገጽ መምራት
redirect('login.php');