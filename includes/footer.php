<?php
/**
 * NESERE Film Production - Global Bilingual Footer
 * Linked to service_request.php with Pre-selected Services
 * Luxury Black (#09090B) & Eagle Gold (#D4AF37) Theme
 */

$current_lang = $_SESSION['lang'] ?? 'am';
$base = $base ?? './';

// 9ኙ ዋና ዋና አገልግሎቶች ከነ ID እና ስማቸው (በቀጥታ ወደ service_request.php የሚያመሩ)
$footer_services = [
    ['id' => 1, 'en' => 'Film & Video Production',     'am' => 'የፊልም እና ቪዲዮ ፕሮዳክሽን'],
    ['id' => 2, 'en' => 'TV Commercials',             'am' => 'የቴሌቪዥን ማስታወቂያዎች'],
    ['id' => 3, 'en' => 'YouTube & Podcast',           'am' => 'የዩቲዩብ እና ፖድካስት ዝግጅት'],
    ['id' => 4, 'en' => 'Social Media Management',     'am' => 'የማህበራዊ ሚዲያ ማኔጅመንት'],
    ['id' => 5, 'en' => 'Wedding & Event Coverage',    'am' => 'የሰርግ እና ሁነቶች ቀረጻ'],
    ['id' => 6, 'en' => 'Post Production & Editing',   'am' => 'የቪዲዮ ቅንብር እና ከለሪንግ'],
    ['id' => 7, 'en' => 'Graphics Design',             'am' => 'ግራፊክስ ዲዛይን እና ብራንዲንግ'],
    ['id' => 8, 'en' => 'Web Development',             'am' => 'የድረ-ገጽ ልማት'],
    ['id' => 9, 'en' => 'Advertisement & Boosting',    'am' => 'የማስታወቂያ ቡስቲንግ']
];

// የቋንቋ መዝገበ-ቃላት
$f_str = [
    'am' => [
        'bio'          => 'በንስር ፊልም ፕሮዳክሽን እያንዳንዱ ታሪክ በስሜት፣ በፈጠራ እና በዓላማ መተረክ እንዳለበት እናምናለን። ታሪኮችን ወደ ህያው ሲነማቲክ ጥበብ እንቀይራለን።',
        'srv_title'    => 'አገልግሎት መጠየቂያ',
        'quick_title'  => 'ፈጣን ገጾች',
        'home'         => 'ዋና ገጽ',
        'about'        => 'ስለ እኛ',
        'services'     => 'አገልግሎቶችና ፓኬጆች',
        'portfolio'    => 'ስራዎቻችን (Portfolio)',
        'contact'      => 'አግኙን (Contact)',
        'request'      => 'ጥያቄ ማስገቢያ',
        'login'        => 'መግቢያ',
        'contact_title'=> 'የእውቂያ መስመሮች',
        'address'      => 'አዲስ አበባ፣ ኢትዮጵያ',
        'hours'        => 'ሰኞ - ቅዳሜ፡ 2:30 ጠዋት - 12:00 ከሰዓት',
        'rights'       => 'መብቱ በህግ የተጠበቀ ነው።',
        'privacy'      => 'የግል መመሪያ',
        'terms'        => 'የአጠቃቀም ውል'
    ],
    'en' => [
        'bio'          => 'At Nesere Film Production, we believe every story deserves to be told with emotion, creativity, and purpose. Transforming vision beyond the screen.',
        'srv_title'    => 'Request a Service',
        'quick_title'  => 'Quick Links',
        'home'         => 'Home',
        'about'        => 'About Us',
        'services'     => 'Services & Packages',
        'portfolio'    => 'Our Portfolio',
        'contact'      => 'Contact Us',
        'request'      => 'Request Service',
        'login'        => 'Login',
        'contact_title'=> 'Direct Lines',
        'address'      => 'Addis Ababa, Ethiopia',
        'hours'        => 'Mon - Sat: 8:30 AM - 6:00 PM',
        'rights'       => 'All Rights Reserved.',
        'privacy'      => 'Privacy Policy',
        'terms'        => 'Terms of Service'
    ]
][$current_lang];
?>

<style>
    .site-footer {
        background: #09090B;
        border-top: 1px solid var(--nesere-border, #1E2026);
        padding: 70px 20px 25px;
        margin-top: auto;
    }

    .footer-container {
        max-width: 1240px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.4fr 1.1fr 0.9fr 1.2fr;
        gap: 40px;
        margin-bottom: 50px;
    }

    @media (max-width: 992px) {
        .footer-container {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {
        .footer-container {
            grid-template-columns: 1fr;
        }
    }

    .footer-col h4 {
        color: var(--nesere-white, #FFFFFF);
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        letter-spacing: 0.5px;
    }

    .footer-col h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 32px;
        height: 2px;
        background: var(--nesere-gold, #D4AF37);
    }

    .footer-col p {
        color: var(--nesere-muted, #8E929D);
        font-size: 14px;
        line-height: 1.65;
        margin-bottom: 18px;
    }

    .footer-services-list,
    .footer-nav-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-services-list li,
    .footer-nav-links li {
        margin-bottom: 10px;
    }

    .footer-services-list a,
    .footer-nav-links a {
        color: #9E9EA7;
        text-decoration: none;
        font-size: 13.5px;
        transition: color 0.2s ease, padding-left 0.2s ease;
        display: inline-block;
    }

    .footer-services-list a:hover,
    .footer-nav-links a:hover {
        color: var(--nesere-gold, #D4AF37);
        padding-left: 4px;
    }

    .footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
        color: var(--nesere-muted, #8E929D);
        font-size: 13.5px;
    }

    .footer-contact-item i {
        color: var(--nesere-gold, #D4AF37);
        margin-top: 3px;
        font-size: 14px;
    }

    .footer-contact-item a {
        color: #FFFFFF;
        text-decoration: none;
        transition: color 0.2s;
    }

    .footer-contact-item a:hover {
        color: var(--nesere-gold, #D4AF37);
    }

    .footer-socials {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .footer-social-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #141418;
        border: 1px solid var(--nesere-border, #1E2026);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--nesere-gold, #D4AF37);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .footer-social-btn:hover {
        background: var(--nesere-gold, #D4AF37);
        border-color: var(--nesere-gold, #D4AF37);
        color: #000000;
        transform: translateY(-2px);
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        padding-top: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        max-width: 1240px;
        margin: 0 auto;
        font-size: 13px;
        color: #6C707A;
    }

    .footer-bottom a {
        color: #8E929D;
        text-decoration: none;
        margin-left: 15px;
    }

    .footer-bottom a:hover {
        color: var(--nesere-gold, #D4AF37);
    }
</style>

<footer class="site-footer">
    <div class="footer-container">
        <!-- 1. Brand & Bio -->
        <div class="footer-col">
            <h4 style="color: var(--nesere-gold, #D4AF37);">NESERE FILMS</h4>
            <p><?= htmlspecialchars($f_str['bio']) ?></p>
            <div class="footer-socials">
                <a href="https://t.me/neserefilmproduction" target="_blank" class="footer-social-btn" title="Telegram">
                    <i class="fa-brands fa-telegram"></i>
                </a>
                <a href="https://instagram.com/neserefilmproduction" target="_blank" class="footer-social-btn" title="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://x.com/neserefilmproduction" target="_blank" class="footer-social-btn" title="X (Twitter)">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <a href="https://tiktok.com/@neserefilmproduction" target="_blank" class="footer-social-btn" title="TikTok">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
                <a href="https://facebook.com/NesereFilmProduction" target="_blank" class="footer-social-btn" title="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </div>
        </div>

        <!-- 2. 9ኙ የአገልግሎት ጥያቄ ሊንኮች (ወደ service_request.php) -->
        <div class="footer-col">
            <h4><?= htmlspecialchars($f_str['srv_title']) ?></h4>
            <ul class="footer-services-list">
                <?php foreach ($footer_services as $s): ?>
                    <li>
                        <a href="<?= $base ?>service_request.php?service_id=<?= (int)$s['id'] ?>">
                            <?= htmlspecialchars($current_lang === 'am' ? $s['am'] : $s['en']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 3. Quick Links -->
        <div class="footer-col">
            <h4><?= htmlspecialchars($f_str['quick_title']) ?></h4>
            <ul class="footer-nav-links">
                <li><a href="<?= $base ?>index.php"><?= htmlspecialchars($f_str['home']) ?></a></li>
                <li><a href="<?= $base ?>about.php"><?= htmlspecialchars($f_str['about']) ?></a></li>
                <li><a href="<?= $base ?>services.php"><?= htmlspecialchars($f_str['services']) ?></a></li>
                <li><a href="<?= $base ?>portfolio.php"><?= htmlspecialchars($f_str['portfolio']) ?></a></li>
                <li><a href="<?= $base ?>contact.php"><?= htmlspecialchars($f_str['contact']) ?></a></li>
                <li><a href="<?= $base ?>service_request.php"><?= htmlspecialchars($f_str['request']) ?></a></li>
                <li><a href="<?= $base ?>login.php"><?= htmlspecialchars($f_str['login']) ?></a></li>
            </ul>
        </div>

        <!-- 4. Direct Contact Details -->
        <div class="footer-col">
            <h4><?= htmlspecialchars($f_str['contact_title']) ?></h4>
            <div class="footer-contact-item">
                <i class="fa fa-location-dot"></i>
                <span><?= htmlspecialchars($f_str['address']) ?></span>
            </div>
            <div class="footer-contact-item">
                <i class="fa fa-phone"></i>
                <span>
                    <a href="tel:+251970701196">+251 970701196</a><br>
                    <a href="tel:+251940695261">+251 940695261</a>
                </span>
            </div>
            <div class="footer-contact-item">
                <i class="fa fa-envelope"></i>
                <span>contact@neserefilms.com</span>
            </div>
            <div class="footer-contact-item">
                <i class="fa fa-clock"></i>
                <span><?= htmlspecialchars($f_str['hours']) ?></span>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <div>
            &copy; <?= date('Y') ?> NESERE Film Production. <?= htmlspecialchars($f_str['rights']) ?>
        </div>
        <div>
            <a href="<?= $base ?>privacy.php"><?= htmlspecialchars($f_str['privacy']) ?></a>
            <a href="<?= $base ?>terms.php"><?= htmlspecialchars($f_str['terms']) ?></a>
        </div>
    </div>
</footer>

</body>
</html>