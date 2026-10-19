<?php
/**
 * NESERE Film Production - Global Header Navigation
 * Palette: Dark (#0B0B0B) & Gold (#D4AF37)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_lang = $_SESSION['lang'] ?? 'am';
$base = $base ?? './';
$current_page = basename($_SERVER['PHP_SELF']);

// ገጹ ራሱ ያላስቀመጠው ርዕስ ካለ
$page_title = $page_title ?? 'Nesere Film Production';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang === 'am' ? 'am' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> | Nesere Film Production</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--nesere-black);
            color: var(--nesere-white);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Layout */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(11, 11, 11, 0.94);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--nesere-border);
            padding: 14px 24px;
        }

        .nav-container {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
        }

        .brand-logo-text {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--nesere-white);
            text-transform: uppercase;
        }

        .brand-logo-text span {
            color: var(--nesere-gold);
        }

        .main-nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
        }

        .main-nav-links a {
            color: #C5C5CE;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .main-nav-links a:hover,
        .main-nav-links a.active {
            color: var(--nesere-gold);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Language Toggle Button */
        .lang-switch-btn {
            background: #17181D;
            border: 1px solid var(--nesere-border);
            color: var(--nesere-gold);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .lang-switch-btn:hover {
            border-color: var(--nesere-gold);
            color: var(--nesere-white);
        }

        .btn-header-cta {
            background: linear-gradient(135deg, var(--nesere-gold-light), var(--nesere-gold));
            color: #000;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .btn-header-cta:hover {
            transform: translateY(-2px);
            color: #000;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--nesere-gold);
            font-size: 22px;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .main-nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #0E0F12;
                border-bottom: 1px solid var(--nesere-border);
                flex-direction: column;
                padding: 20px;
                gap: 16px;
            }

            .main-nav-links.show {
                display: flex;
            }

            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="nav-container">
        <!-- Brand Logo -->
        <a href="<?= $base ?>index.php" class="brand-logo-wrap">
            <img src="<?= $base ?>assets/images/LOGO NESERE icon.png" alt="NESERE Logo" class="brand-logo-img" onerror="this.style.display='none'">
            <div class="brand-logo-text">NESERE <span>FILMS</span></div>
        </a>

        <!-- Main Navigation Links -->
        <ul class="main-nav-links" id="navLinks">
            <li><a href="<?= $base ?>index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>"><?= $current_lang === 'am' ? 'ዋና ገጽ' : 'Home' ?></a></li>
            <li><a href="<?= $base ?>about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>"><?= $current_lang === 'am' ? 'ስለ እኛ' : 'About' ?></a></li>
            <li><a href="<?= $base ?>portfolio.php" class="<?= $current_page === 'portfolio.php' ? 'active' : '' ?>"><?= $current_lang === 'am' ? 'ስራዎቻችን' : 'Portfolio' ?></a></li>
            <li><a href="<?= $base ?>contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>"><?= $current_lang === 'am' ? 'አግኙን' : 'Contact' ?></a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <li><a href="<?= $base ?>admin/index.php" style="color: var(--nesere-gold);"><i class="fa fa-gauge"></i> Dashboard</a></li>
                <?php else: ?>
                    <li><a href="<?= $base ?>customer/dashboard.php"><i class="fa fa-user"></i> My Portal</a></li>
                <?php endif; ?>
                <li><a href="<?= $base ?>logout.php" style="color: #ff6b6b;"><i class="fa fa-arrow-right-from-bracket"></i> <?= $current_lang === 'am' ? 'ውጣ' : 'Logout' ?></a></li>
            <?php else: ?>
                <li><a href="<?= $base ?>login.php"><?= $current_lang === 'am' ? 'ግባ' : 'Login' ?></a></li>
            <?php endif; ?>
        </ul>

        <!-- Actions: Language Toggle & CTA Button -->
        <div class="header-actions">
            <!-- Language Switcher -->
            <a href="?lang=<?= $current_lang === 'am' ? 'en' : 'am' ?>" class="lang-switch-btn">
                <?= $current_lang === 'am' ? 'English' : 'አማርኛ' ?>
            </a>

            <!-- Service Request Button -->
            <a href="<?= $base ?>service_request.php" class="btn-header-cta">
                <?= $current_lang === 'am' ? 'ጥያቄ ያስገቡ' : 'Request Service' ?>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button class="mobile-toggle" onclick="document.getElementById('navLinks').classList.toggle('show')">
                <i class="fa fa-bars"></i>
            </button>
        </div>
    </div>
</header>