<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

// የfooter ቀሪ ኮድህ ከዚህ ይቀጥላል።

$current_lang = $_SESSION['lang'] ?? 'am';
if (!in_array($current_lang, ['am', 'en'], true)) {
    $current_lang = 'am';
}

// Set $base before including this file if the page is in a subfolder, e.g. '../'.
$base = isset($base) && is_string($base) ? $base : './';
if ($base === '') {
    $base = './';
}

if (!function_exists('nesere_footer_h')) {
    function nesere_footer_h($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

$footer_services = [
    ['id' => 1, 'en' => 'Film & Video Production',   'am' => 'የፊልም እና ቪዲዮ ፕሮዳክሽን'],
    ['id' => 2, 'en' => 'TV Commercials',            'am' => 'የቴሌቪዥን ማስታወቂያዎች'],
    ['id' => 3, 'en' => 'YouTube & Podcast',          'am' => 'የዩቲዩብ እና ፖድካስት ዝግጅት'],
    ['id' => 4, 'en' => 'Social Media Management',    'am' => 'የማህበራዊ ሚዲያ ማኔጅመንት'],
    ['id' => 5, 'en' => 'Wedding & Event Coverage',   'am' => 'የሰርግ እና ሁነቶች ቀረጻ'],
    ['id' => 6, 'en' => 'Post Production & Editing',  'am' => 'የቪዲዮ ቅንብር እና ከለሪንግ'],
    ['id' => 7, 'en' => 'Graphic Design',             'am' => 'ግራፊክስ ዲዛይን እና ብራንዲንግ'],
    ['id' => 8, 'en' => 'Web Development',            'am' => 'የድረ-ገጽ ልማት'],
    ['id' => 9, 'en' => 'Advertisement & Boosting',   'am' => 'የማስታወቂያ ቡስቲንግ'],
];

$footer_text = [
    'am' => [
        'bio' => 'በንስር ፊልም ፕሮዳክሽን እያንዳንዱ ታሪክ በስሜት፣ በፈጠራ እና በዓላማ መተረክ እንዳለበት እናምናለን። ታሪኮችን ወደ ህያው ሲነማቲክ ጥበብ እንቀይራለን።',
        'srv_title' => 'አገልግሎት መጠየቂያ',
        'quick_title' => 'ፈጣን ገጾች',
        'home' => 'ዋና ገጽ',
        'about' => 'ስለ እኛ',
        'services' => 'አገልግሎቶችና ፓኬጆች',
        'portfolio' => 'ስራዎቻችን (Portfolio)',
        'contact' => 'አግኙን (Contact)',
        'request' => 'ጥያቄ ማስገቢያ',
        'login' => 'መግቢያ',
        'contact_title' => 'የእውቂያ መስመሮች',
        'address' => 'አዲስ አበባ፣ ኢትዮጵያ',
        'hours' => 'ሰኞ - ቅዳሜ፡ 2:30 ጠዋት - 12:00 ከሰዓት',
        'rights' => 'መብቱ በህግ የተጠበቀ ነው።',
        'privacy' => 'የግል መመሪያ',
        'terms' => 'የአጠቃቀም ውል',
    ],
    'en' => [
        'bio' => 'At Nesere Film Production, we believe every story deserves to be told with emotion, creativity, and purpose. Transforming vision beyond the screen.',
        'srv_title' => 'Request a Service',
        'quick_title' => 'Quick Links',
        'home' => 'Home',
        'about' => 'About Us',
        'services' => 'Services & Packages',
        'portfolio' => 'Our Portfolio',
        'contact' => 'Contact Us',
        'request' => 'Request Service',
        'login' => 'Login',
        'contact_title' => 'Direct Lines',
        'address' => 'Addis Ababa, Ethiopia',
        'hours' => 'Mon - Sat: 8:30 AM - 6:00 PM',
        'rights' => 'All Rights Reserved.',
        'privacy' => 'Privacy Policy',
        'terms' => 'Terms of Service',
    ],
];

$f_str = $footer_text[$current_lang];
?>

<style>
    .site-footer {
        background: #09090B;
        border-top: 1px solid var(--nesere-border, #1E2026);
        padding: 64px 20px 24px;
        margin-top: auto;
        color: #FFFFFF;
        box-sizing: border-box;
    }
    .site-footer *, .site-footer *::before, .site-footer *::after { box-sizing: border-box; }
    .footer-container {
        width: 100%; max-width: 1240px; margin: 0 auto 46px;
        display: grid; grid-template-columns: 1.4fr 1.1fr .9fr 1.2fr; gap: 36px;
    }
    .footer-col { min-width: 0; }
    .footer-col h4 {
        color: var(--nesere-white, #FFFFFF); font-size: 16px; font-weight: 700;
        margin: 0 0 22px; position: relative; letter-spacing: .35px; line-height: 1.5;
    }
    .footer-col h4::after {
        content: ''; position: absolute; bottom: -8px; left: 0;
        width: 32px; height: 2px; background: var(--nesere-gold, #D4AF37);
    }
    .footer-col p { color: var(--nesere-muted, #8E929D); font-size: 14px; line-height: 1.8; margin: 0 0 18px; }
    .footer-services-list, .footer-nav-links { list-style: none; padding: 0; margin: 0; }
    .footer-services-list li, .footer-nav-links li { margin: 0 0 10px; }
    .footer-services-list a, .footer-nav-links a {
        color: #9E9EA7; text-decoration: none; font-size: 13.5px; line-height: 1.65;
        transition: color .2s ease, padding-left .2s ease; display: inline-block; overflow-wrap: anywhere;
    }
    .footer-services-list a:hover, .footer-nav-links a:hover { color: var(--nesere-gold, #D4AF37); padding-left: 4px; }
    .footer-contact-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 15px; color: var(--nesere-muted, #8E929D); font-size: 13.5px; line-height: 1.7; }
    .footer-contact-item i { color: var(--nesere-gold, #D4AF37); margin-top: 4px; font-size: 14px; min-width: 15px; }
    .footer-contact-item a { color: #FFFFFF; text-decoration: none; transition: color .2s; overflow-wrap: anywhere; }
    .footer-contact-item a:hover { color: var(--nesere-gold, #D4AF37); }
   .footer-socials {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 18px;
}

.footer-social-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #D4AF37;
    border-radius: 9px;
    background: #141418;
    color: #D4AF37;
    font-size: 18px;
    text-decoration: none;
    transition: .25s ease;
}

.footer-social-btn:hover {
    background: #D4AF37;
    color: #09090B;
    transform: translateY(-3px);
}
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,.08); padding-top: 22px;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;
        max-width: 1240px; margin: 0 auto; font-size: 13px; line-height: 1.7; color: #8E929D;
    }
    .footer-bottom-links { display: flex; flex-wrap: wrap; gap: 8px 18px; }
    .footer-bottom a { color: #8E929D; text-decoration: none; }
    .footer-bottom a:hover { color: var(--nesere-gold, #D4AF37); }
    @media (max-width: 992px) { .footer-container { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 34px 28px; } }
    @media (max-width: 600px) {
        .site-footer { padding: 46px 18px 22px; }
        .footer-container { grid-template-columns: minmax(0, 1fr); gap: 30px; margin-bottom: 34px; }
        .footer-bottom { align-items: flex-start; flex-direction: column; }
    }
</style>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-col">
            <h4 style="color: var(--nesere-gold, #D4AF37);">NESERE FILMS</h4>
            <p><?= nesere_footer_h($f_str['bio']) ?></p>
            <div class="footer-socials" aria-label="Social media">
                <a href="https://t.me/@neserefilmproduction" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Telegram" aria-label="Telegram"><i class="fa-brands fa-telegram" aria-hidden="true"></i></a>
                <a href="https://instagram.com/@neserefilmproduction" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Instagram" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                <a href="https://x.com/@neserefilmproduction" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="X (Twitter)" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
                <a href="https://tiktok.com/@neserefilmproduction" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="TikTok" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                <a href="https://facebook.com/@NesereFilmProduction" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
            </div>
        </div>


<div class="footer-col">
    <h4>
        <?= nesere_footer_h(
            $current_lang === 'am'
                ? 'አገልግሎቶቻችን'
                : 'Our Services'
        ) ?>
    </h4>

    <ul class="footer-services-list">
        <?php foreach ($footer_services as $service): ?>
            <li>
                <a href="<?= nesere_footer_h($base) ?>service_request.php?lang=<?= nesere_footer_h($current_lang) ?>&amp;service=<?= (int) $service['id'] ?>#packages">
                    <?= nesere_footer_h(
                        $current_lang === 'am'
                            ? $service['am']
                            : $service['en']
                    ) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


        <div class="footer-col">
            <h4><?= nesere_footer_h($f_str['quick_title']) ?></h4>
            <ul class="footer-nav-links">
                <li><a href="<?= nesere_footer_h($base) ?>index.php"><?= nesere_footer_h($f_str['home']) ?></a></li>
                <li><a href="<?= nesere_footer_h($base) ?>about.php"><?= nesere_footer_h($f_str['about']) ?></a></li>
                <li><a href="<?= nesere_footer_h($base) ?>services.php"><?= nesere_footer_h($f_str['services']) ?></a></li>
                <li><a href="<?= nesere_footer_h($base) ?>portfolio.php"><?= nesere_footer_h($f_str['portfolio']) ?></a></li>
                <li><a href="<?= nesere_footer_h($base) ?>contact.php"><?= nesere_footer_h($f_str['contact']) ?></a></li>
                <li><a href="<?= nesere_footer_h($base) ?>service_request.php"><?= nesere_footer_h($f_str['request']) ?></a></li>
                <li><a href="<?= nesere_footer_h($base) ?>login.php"><?= nesere_footer_h($f_str['login']) ?></a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4><?= nesere_footer_h($f_str['contact_title']) ?></h4>
            <div class="footer-contact-item"><i class="fa fa-location-dot" aria-hidden="true"></i><span><?= nesere_footer_h($f_str['address']) ?></span></div>
            <div class="footer-contact-item"><i class="fa fa-phone" aria-hidden="true"></i><span><a href="tel:+251970701196">+251 970701196</a><br><a href="tel:+251940695261">+251 940695261</a></span></div>
            <div class="footer-contact-item"><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:contact@neserefilms.com">contact@neserefilms.com</a></div>
            <div class="footer-contact-item"><i class="fa fa-clock" aria-hidden="true"></i><span><?= nesere_footer_h($f_str['hours']) ?></span></div>
        </div>
    </div>

    <div class="footer-bottom">
        <div>&copy; <?= date('Y') ?> NESERE Film Production. <?= nesere_footer_h($f_str['rights']) ?></div>
        <div class="footer-bottom-links">
            <a href="<?= nesere_footer_h($base) ?>privacy.php"><?= nesere_footer_h($f_str['privacy']) ?></a>
            <a href="<?= nesere_footer_h($base) ?>terms.php"><?= nesere_footer_h($f_str['terms']) ?></a>
        </div>
    </div>
</footer>

</body>
</html>
