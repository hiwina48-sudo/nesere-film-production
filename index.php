<?php
/**
 * NESERE Film Production - Premium Marketing & Home Page
 * Brand Palette: Deep Black (#0B0B0B), Eagle Gold (#D4AF37 / #F2D16B), White (#FFFFFF)
 */
require_once __DIR__ . '/config/config.php';

// Logik Pertukaran Bahasa (Amharic / English)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();

// Dapatkan tepat 9 perkhidmatan daripada pangkalan data
$services = $db->query("SELECT * FROM services WHERE status = 1 ORDER BY service_id ASC LIMIT 9")->fetchAll();

// Dapatkan projek portfolio terkini
$projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 6")->fetchAll();

// Kamus Teks Dwibahasa
$lang_dict = [
    'am' => [
        'hero_badge'      => 'የላቀ የፈጠራ እና ሲነማቲክ ሚዲያ አጋርዎ',
        'hero_title_1'    => 'ሀሳብዎን ወደ',
        'hero_title_gold' => 'ሲኒማቲክ ጥበብ',
        'hero_title_2'    => 'እንቀይራለን',
        'hero_desc'       => 'ንስር ፊልም ፕሮዳክሽን ጥራት ያላቸው ፊልሞችን፣ የቴሌቪዥን ማስታወቂያዎችን፣ ዩቲዩብ እና ፖድካስቶችን እንዲሁም ዘመናዊ የዲጂታል አገልግሎቶችን በልዩ ሙያዊ ብቃት ያዘጋጃል።',
        'cta_request'     => 'አገልግሎት ይጠይቁ',
        'cta_portfolio'   => 'ስራዎቻችንን ይመልከቱ',
        'stats_projects'  => 'ያለቁ ፕሮጀክቶች',
        'stats_clients'   => 'ረክተው የሰሩ ደንበኞች',
        'stats_exp'       => 'የሙያ ልምድ ዓመታት',
        'services_title'  => 'Our Core Services',
        'services_sub'    => 'ከሀሳብ እስከ ሙሉ ፍፃሜ—ለንግድዎ እና ለኪነ-ጥበብዎ የተሟሉ የቪዲዮ እና ዲጂታል መፍትሄዎች',
        'req_btn'         => 'Request Service →',
        'portfolio_title' => 'የቅርብ ጊዜ ስራዎቻችን',
        'portfolio_sub'   => 'በከፍተኛ የጥበብ ደረጃ የተዘጋጁ ፊልሞች፣ የንግድ ማስታወቂያዎች እና የክስተት ቀረጻዎች',
        'why_title'       => 'ለምን ንስርን ይመርጣሉ?',
        'why_1_title'     => '4K እና የሲኒማ ደረጃ ጥራት',
        'why_1_desc'      => 'በዘመናዊ የካሜራ ቴክኖሎጂዎች፣ በከፍተኛ ደረጃ የከለር ግሬዲንግ እና የድምፅ ምህንድስና የተሰሩ ስራዎች።',
        'why_2_title'     => 'ፈጣን እና አስተማማኝ አፈጻጸም',
        'why_2_desc'      => 'ፕሮጀክቶችን በስምምነቱ መሰረት በጥራት አጠናቆ በሰዓቱ የማስረከብ የጸና ቁርጠኝነት።',
        'why_3_title'     => 'ሁሉን አቀፍ የዲጂታል መፍትሄ',
        'why_3_desc'      => 'ከቀረጻ እስከ ድህረ-ምርት፣ ከማህበራዊ ሚዲያ ማስተዋወቅ እስከ ድረ-ገጽ ልማት ድረስ በአንድ ጣሪያ ስር።',
        'cta_banner_title'=> 'ቀጣዩን ፕሮጀክትዎን አብረን እንጀምር?',
        'cta_banner_sub'  => 'የፕሮዳክሽን ጥያቄዎን ዛሬውኑ ይላኩልን፤ የባለሙያ ቡድናችን ዝርዝር እቅድ እና የዋጋ ማብራሪያ ያቀርብልዎታል።',
        'btn_book_now'    => 'አሁን ጥያቄ ያስገቡ'
    ],
    'en' => [
        'hero_badge'      => 'PREMIUM CINEMATIC & DIGITAL MEDIA PRODUCTION',
        'hero_title_1'    => 'Transforming Ideas Into',
        'hero_title_gold' => 'Cinematic Reality',
        'hero_title_2'    => '',
        'hero_desc'       => 'NESERE Film Production delivers high-end cinematic films, impactful TV commercials, studio-grade podcasts, and modern digital strategies that elevate your brand to the next level.',
        'cta_request'     => 'Request a Service',
        'cta_portfolio'   => 'Explore Portfolio',
        'stats_projects'  => 'Completed Projects',
        'stats_clients'   => 'Satisfied Clients',
        'stats_exp'       => 'Years Experience',
        'services_title'  => 'Our Core Services',
        'services_sub'    => 'From concept to final master cut—comprehensive visual and digital solutions for visionary brands.',
        'req_btn'         => 'Request Service →',
        'portfolio_title' => 'Featured Works',
        'portfolio_sub'   => 'A showcase of our cinematic films, TV campaigns, documentaries, and creative stories.',
        'why_title'       => 'Why Choose NESERE?',
        'why_1_title'     => 'Cinematic 4K Excellence',
        'why_1_desc'      => 'Equipped with industry-standard cameras, advanced Hollywood color grading, and pristine audio design.',
        'why_2_title'     => 'On-Time & Dependable Delivery',
        'why_2_desc'      => 'Strict production pipelines to ensure your creative deliverables exceed expectations on schedule.',
        'why_3_title'     => 'End-to-End Digital Synergy',
        'why_3_desc'      => 'Full integration spanning video production, web development, and hyper-targeted advertising campaigns.',
        'cta_banner_title'=> 'Ready to Produce Your Next Masterpiece?',
        'cta_banner_sub'  => 'Send us your project scope today. Our production team will review your requirements and provide a custom quotation.',
        'btn_book_now'    => 'Submit Project Request'
    ]
];
$str = $lang_dict[$current_lang];
$page_title = ($current_lang === 'am') ? 'ዋና ገጽ' : 'Home';

require_once __DIR__ . '/includes/header.php';
?>

<style>
/* Skema Warna Mewah NESERE (Black & Gold) */
:root {
    --bg-black: #0B0B0B;
    --card-black: #141418;
    --border-dark: #2A2A32;
    --gold: #D4AF37;
    --gold-light: #F2D16B;
    --text-muted: #A0A0AB;
}

body {
    background-color: var(--bg-black);
    color: #FFFFFF;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

/* 1. Bahagian Hero */
.hero-section {
    position: relative;
    padding: 90px 20px 70px;
    background: radial-gradient(circle at 50% 20%, rgba(212, 175, 55, 0.12) 0%, rgba(11, 11, 11, 1) 75%);
    text-align: center;
}

.hero-eagle-logo {
    max-width: 150px;
    height: auto;
    margin-bottom: 20px;
    filter: drop-shadow(0 0 25px rgba(212, 175, 55, 0.35));
}

.hero-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 30px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid var(--gold);
    color: var(--gold-light);
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.hero-title {
    font-size: 44px;
    font-weight: 800;
    line-height: 1.25;
    max-width: 900px;
    margin: 0 auto 20px;
}

.hero-title .gold-accent {
    background: linear-gradient(135deg, var(--gold-light), var(--gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-description {
    font-size: 16px;
    color: var(--text-muted);
    max-width: 740px;
    margin: 0 auto 35px;
    line-height: 1.7;
}

.hero-buttons {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 45px;
}

.btn-gold {
    background: linear-gradient(135deg, var(--gold-light), var(--gold));
    color: #000000;
    font-weight: 700;
    padding: 13px 32px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 15px;
    box-shadow: 0 4px 20px rgba(212, 175, 55, 0.35);
    transition: transform 0.2s;
}

.btn-gold:hover {
    transform: translateY(-2px);
    color: #000;
}

.btn-outline {
    background: transparent;
    color: #FFFFFF;
    font-weight: 600;
    padding: 12px 28px;
    border-radius: 6px;
    border: 1px solid var(--border-dark);
    text-decoration: none;
    font-size: 15px;
    transition: all 0.2s;
}

.btn-outline:hover {
    border-color: var(--gold);
    color: var(--gold);
}

.counter-bar {
    display: flex;
    justify-content: center;
    gap: 45px;
    flex-wrap: wrap;
    padding-top: 25px;
    border-top: 1px solid rgba(42, 42, 50, 0.6);
    max-width: 800px;
    margin: 0 auto;
}

.counter-item h3 {
    font-size: 30px;
    color: var(--gold);
    margin: 0 0 5px;
}

.counter-item p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    text-transform: uppercase;
}

/* 2. Grid Perkhidmatan Teras (Sesuai dengan Penerangan Teks Asal) */
.section-padding {
    padding: 80px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-header h2 {
    font-size: 32px;
    color: #FFFFFF;
    margin-bottom: 12px;
}

.section-header p {
    font-size: 15px;
    color: var(--text-muted);
    max-width: 650px;
    margin: 0 auto;
    line-height: 1.6;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 25px;
}

.service-card {
    background: var(--card-black);
    border: 1px solid var(--border-dark);
    border-radius: 10px;
    padding: 30px 25px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s ease;
}

.service-card:hover {
    border-color: var(--gold);
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
}

.service-icon-box {
    width: 48px;
    height: 48px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    color: var(--gold);
    font-size: 20px;
}

.service-card h3 {
    font-size: 19px;
    color: #FFFFFF;
    margin: 0 0 12px;
}

.service-card p {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 22px;
    flex-grow: 1;
}

.service-link {
    color: var(--gold);
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s;
}

.service-link:hover {
    color: var(--gold-light);
}

/* 3. Bahagian Portfolio */
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 25px;
}

.portfolio-card {
    background: var(--card-black);
    border: 1px solid var(--border-dark);
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.portfolio-card:hover {
    border-color: var(--gold);
}

.portfolio-thumb {
    width: 100%;
    height: 200px;
    background: #1C1C22;
    object-fit: cover;
    display: block;
}

.portfolio-info {
    padding: 20px;
}

.portfolio-category {
    font-size: 12px;
    color: var(--gold);
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 6px;
}

.portfolio-title {
    font-size: 17px;
    color: #FFFFFF;
    margin: 0 0 8px;
}

.portfolio-desc {
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.5;
    margin: 0;
}

/* 4. Mengapa Memilih NESERE */
.why-section {
    background: #101014;
    border-top: 1px solid var(--border-dark);
    border-bottom: 1px solid var(--border-dark);
}

.why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.why-item {
    background: var(--card-black);
    border: 1px solid var(--border-dark);
    padding: 28px;
    border-radius: 10px;
}

.why-item h3 {
    color: var(--gold);
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 12px;
}

.why-item p {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

/* 5. Sepanduk Seruan Tindakan (CTA) */
.cta-banner {
    background: linear-gradient(135deg, #18181C 0%, #101014 100%);
    border: 1px solid var(--gold);
    border-radius: 12px;
    padding: 50px 30px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
    margin-top: 30px;
}

.cta-banner h2 {
    font-size: 30px;
    color: #FFFFFF;
    margin-top: 0;
    margin-bottom: 15px;
}

.cta-banner p {
    color: var(--text-muted);
    font-size: 15px;
    max-width: 650px;
    margin: 0 auto 30px;
}
</style>

<!-- 1. BAHAGIAN HERO -->
<section class="hero-section">
    <img src="<?= $base ?>assets/images/LOGO NESERE icon.png" alt="NESERE Eagle Logo" class="hero-eagle-logo" onerror="this.style.display='none'">
    
    <div>
        <span class="hero-badge"><?= e($str['hero_badge']) ?></span>
    </div>

    <h1 class="hero-title">
        <?= e($str['hero_title_1']) ?> <span class="gold-accent"><?= e($str['hero_title_gold']) ?></span> <?= e($str['hero_title_2']) ?>
    </h1>

    <p class="hero-description">
        <?= e($str['hero_desc']) ?>
    </p>

    <div class="hero-buttons">
        <a href="<?= $base ?>service_request.php" class="btn-gold"><?= e($str['cta_request']) ?></a>
    </div>
</section>

<!-- 2. BAHAGIAN PERKHIDMATAN TERAS (9 PERKHIDMATAN TERAS) -->
<section class="section-padding" id="services">
    <div class="section-header">
        <h2><?= e($str['services_title']) ?></h2>
        <p><?= e($str['services_sub']) ?></p>
    </div>

    <div class="services-grid">
        <?php foreach ($services as $srv): ?>
            <?php 
                $s_name = ($current_lang === 'am' && !empty($srv['service_name_am'])) ? $srv['service_name_am'] : $srv['service_name'];
                $s_desc = ($current_lang === 'am' && !empty($srv['short_desc_am'])) ? $srv['short_desc_am'] : $srv['short_desc'];
            ?>
            <div class="service-card">
                <div>
                    <div class="service-icon-box">
                        <i class="fa <?= e($srv['icon_class'] ?? 'fa-video') ?>"></i>
                    </div>
                    <h3><?= e($s_name) ?></h3>
                    <p><?= e($s_desc) ?></p>
                </div>
                <div>
                    <a href="<?= $base ?>service_request.php?service_id=<?= (int)$srv['service_id'] ?>" class="service-link">
                        <?= e($str['req_btn']) ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- 3. BAHAGIAN PORTFOLIO -->
<section class="section-padding" id="portfolio" style="padding-top: 10px;">
    <div class="section-header">
        <h2><?= e($str['portfolio_title']) ?></h2>
        <p><?= e($str['portfolio_sub']) ?></p>
    </div>

    <div class="portfolio-grid">
        <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $proj): ?>
                <div class="portfolio-card">
                    <?php if (!empty($proj['thumbnail'])): ?>
                        <img src="<?= $base . e($proj['thumbnail']) ?>" alt="<?= e($proj['title']) ?>" class="portfolio-thumb">
                    <?php else: ?>
                        <div class="portfolio-thumb" style="display:flex; align-items:center; justify-content:center; color:#555;">
                            <span>NESERE CINEMA</span>
                        </div>
                    <?php endif; ?>
                    <div class="portfolio-info">
                        <div class="portfolio-category"><?= e($proj['category']) ?></div>
                        <h4 class="portfolio-title"><?= e($proj['title']) ?></h4>
                        <p class="portfolio-desc"><?= e(mb_strimwidth($proj['description'] ?? '', 0, 110, '...')) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- 4. MENGAPA MEMILIH NESERE -->
<section class="section-padding why-section">
    <div class="section-header" style="margin-bottom: 40px;">
        <h2><?= e($str['why_title']) ?></h2>
    </div>

    <div class="why-grid">
        <div class="why-item">
            <h3><?= e($str['why_1_title']) ?></h3>
            <p><?= e($str['why_1_desc']) ?></p>
        </div>
        <div class="why-item">
            <h3><?= e($str['why_2_title']) ?></h3>
            <p><?= e($str['why_2_desc']) ?></p>
        </div>
        <div class="why-item">
            <h3><?= e($str['why_3_title']) ?></h3>
            <p><?= e($str['why_3_desc']) ?></p>
        </div>
    </div>
</section>

<!-- 5. SEPANDUK SERUAN TINDAKAN (CTA) -->
<section class="section-padding">
    <div class="cta-banner">
        <h2><?= e($str['cta_banner_title']) ?></h2>
        <p><?= e($str['cta_banner_sub']) ?></p>
        <a href="<?= $base ?>service_request.php" class="btn-gold"><?= e($str['btn_book_now']) ?></a>
    </div>
</section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>