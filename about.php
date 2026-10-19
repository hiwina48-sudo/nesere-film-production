<?php
/**
 * NESERE Film Production - About Us Page
 * Brand Colors: Black (#0B0B0B), Eagle Gold (#D4AF37 / #F2D16B), White (#FFFFFF)
 */
require_once __DIR__ . '/config/config.php';

// Multi-language Logic (አማርኛ / English)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$lang_dict = [
    'am' => [
        'page_title'       => 'ስለ እኛ | Nesere Film Production',
        'hero_badge'       => 'ስለ ንስር ፊልም ፕሮዳክሽን',
        'hero_title'       => 'የፈጠራ እና የሲኒማቲክ ጥበብ',
        'hero_title_gold'  => 'ጉዟችን',
        'hero_desc'        => 'በንስር ፊልም ፕሮዳክሽን እያንዳንዱ ታሪክ በስሜት፣ በፈጠራ እና በዓላማ መተረክ እንዳለበት እናምናለን።',
        
        // ዋና ጽሑፍ (Story)
        'story_badge'      => 'የእኛ ታሪክ እና እምነት',
        'story_title'      => 'ታሪኮችን ወደ ህያው ሲኒማቲክ ጥበብ የመቀየር ጉዞ',
        'story_p1'         => 'በንስር ፊልም ፕሮዳክሽን እያንዳንዱ ታሪክ በስሜት፣ በፈጠራ እና በዓላማ መተረክ እንዳለበት በጽኑ እናምናለን። ከተመልካቾች ጋር በጥልቀት የሚገናኙ ፊልሞችን፣ የቴሌቪዥን ማስታወቂያዎችን፣ የዩቲዩብ ፖድካስቶችን እንዲሁም የማህበራዊ ሚዲያ ይዘቶችን በመፍጠር እና በማስተዳደር ላይ ልዩ ሙያ አለን።',
        'story_p2'         => 'ቡድናችን የእርስዎን ራዕይ ወደ እውን ለማምጣት ጥበብን፣ ቴክኖሎጂን እና የታሪክ አተረካክን በማዋሃድ ሀሳቦችን ወደ ኃይለኛ የእይታ ምስሎች ይቀይራል። ሰርግ፣ የብራንድ ዘመቻ፣ ወይም ዘጋቢ ፊልም (ዶክመንተሪ) ይሁን፤ እያንዳንዱ ምስል ከስክሪኑ ባሻገር ህያው ሆኖ እንዲተነፍስ እናደርጋለን።',
        
        // የተስተካከሉ አሃዞች
        'stat_1'           => '100+',
        'stat_1_txt'       => 'ያለቁ ሲኒማቲክ ፕሮጀክቶች',
        'stat_2'           => '5+ Years',
        'stat_2_txt'       => 'የሙያ ልምድ ዓመታት',
        'stat_3'           => '99.9%',
        'stat_3_txt'       => 'የደንበኞች እርካታ',
        
        'mv_badge'         => 'መርሆዎቻችን',
        'mv_title'         => 'ራዕይ፣ ተልዕኮ እና እሴቶቻችን',
        'vision_title'     => 'ራዕያችን (Our Vision)',
        'vision_desc'      => 'በኢትዮጵያ እና በምስራቅ አፍሪካ ቀዳሚው፣ በፈጠራ ጥበቡ ተወዳዳሪ የማይገኝለት እና ዓለም አቀፍ ደረጃቸውን የጠበቁ ፊልሞችን የሚያዘጋጅ ግንባር ቀደም ፕሮዳክሽን መሆን።',
        'mission_title'    => 'ተልዕኳችን (Our Mission)',
        'mission_desc'     => 'ጥበብን፣ ቴክኖሎጂን እና የታሪክ አተረካክን በማዋሃድ ደንበኞቻችንን ከታዳሚዎቻቸው ጋር በጥልቀት የሚያስተሳስሩ ዘመን የማይሽራቸው የቪዥዋል ስራዎችን ማበርከት።',
        'values_title'     => 'ዋና እሴቶቻችን (Core Values)',
        'values_desc'      => 'የላቀ ጥራት (Excellence)፣ ስሜት ቀስቃሽ ፈጠራ (Creativity)፣ አስተማማኝነት (Reliability) እና ለኪነ-ጥበቡ ያለን ጽኑ ፍቅር።',
        
        'team_badge'       => 'ባለሙያዎቻችን',
        'team_title'       => 'ከጥበቡ ጀርባ ያሉ የፈጠራ ሰዎች',
        'team_sub'         => 'በፊልም ዳይሬክሽን፣ ሲነማቶግራፊ፣ ድምፅ፣ ኤዲቲንግ እና ዲጂታል ሚዲያ የካበተ ልምድ ያላቸው ባለሙያዎች ስብስብ።',
        'team_1_role'      => 'Creative Director & Founder',
        'team_2_role'      => 'Lead Cinematographer',
        'team_3_role'      => 'Senior Post-Production & Colorist',
        'team_4_role'      => 'Head of Digital & Media',
        
        'cta_heading'      => 'ቀጣዩን ስራዎን አብረን እንስራ?',
        'cta_desc'         => 'የፊልም፣ የማስታወቂያ፣ የፖድካስት፣ የሰርግ ወይም የክስተት ቀረጻ ፕሮጀክትዎን ዝርዝር ይላኩልን፤ አብረን አስደናቂ ስራ እንፍጠር።',
        'cta_btn'          => 'አገልግሎት ይጠይቁ'
    ],
    'en' => [
        'page_title'       => 'About Us | Nesere Film Production',
        'hero_badge'       => 'ABOUT NESERE FILM PRODUCTION',
        'hero_title'       => 'Crafting Visual Stories with',
        'hero_title_gold'  => 'Emotion & Purpose',
        'hero_desc'        => 'At Nesere Film Production, we believe every story deserves to be told with emotion, creativity, and purpose.',
        
        // ዋና ጽሑፍ (Story)
        'story_badge'      => 'OUR PHILOSOPHY & STORY',
        'story_title'      => 'Bringing Vision to Life Beyond the Screen',
        'story_p1'         => 'At Nesere Film Production, we believe every story deserves to be told with emotion, creativity, and purpose. We specialize in crafting films, TV commercials, YouTube podcasts, and social media content and management that connect deeply with audiences.',
        'story_p2'         => 'Our team transforms ideas into powerful visuals — blending art, technology, and storytelling to bring your vision to life. Whether it’s a wedding, a brand campaign, or a documentary, we make every frame breathe beyond the screen.',
        
        // የተስተካከሉ አሃዞች
        'stat_1'           => '100+',
        'stat_1_txt'       => 'Cinematic Projects Delivered',
        'stat_2'           => '3+ Years',
        'stat_2_txt'       => 'Years of Production Excellence',
        'stat_3'           => '99.9%',
        'stat_3_txt'       => 'Client Commitment',
        
        'mv_badge'         => 'FOUNDATIONS',
        'mv_title'         => 'Vision, Mission & Core Values',
        'vision_title'     => 'Our Vision',
        'vision_desc'      => 'To stand as the leading creative media powerhouse in the region, known for authentic storytelling and unmatched visual cinematic excellence.',
        'mission_title'    => 'Our Mission',
        'mission_desc'     => 'To harmonize advanced technology and creative artistry, transforming conceptual ideas into unforgettable visual experiences.',
        'values_title'     => 'Our Core Values',
        'values_desc'      => 'Uncompromising Quality, Purposeful Creativity, Integrity & Reliability, and Deep Artistic Passion.',
        
        'team_badge'       => 'THE CREW',
        'team_title'       => 'Masterminds Behind the Lens',
        'team_sub'         => 'A dedicated collective of directors, cinematographers, sound designers, colorists, and brand strategists.',
        'team_1_role'      => 'Creative Director & Founder',
        'team_2_role'      => 'Lead Cinematographer',
        'team_3_role'      => 'Senior Post-Production & Colorist',
        'team_4_role'      => 'Head of Digital & Media',
        
        'cta_heading'      => 'Ready to Collaborate on Your Next Vision?',
        'cta_desc'         => 'Tell us about your upcoming film, TV advert, documentary, or event. Let’s make every frame breathe together.',
        'cta_btn'          => 'Request a Production Service'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/includes/header.php';
?>

<style>
:root {
    --nesere-black: #0B0B0B;
    --nesere-dark-card: #121316;
    --nesere-border: #1E2026;
    --nesere-gold: #D4AF37;
    --nesere-gold-light: #F2D16B;
    --nesere-muted: #8E929D;
    --nesere-white: #FFFFFF;
}

body {
    background-color: var(--nesere-black);
    color: var(--nesere-white);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    margin: 0;
    padding: 0;
}

/* 1. Hero Area */
.about-hero {
    position: relative;
    padding: 85px 20px 65px;
    text-align: center;
    background: radial-gradient(circle at 50% 15%, rgba(212, 175, 55, 0.12) 0%, rgba(11, 11, 11, 1) 75%);
}

.about-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 30px;
    background: rgba(212, 175, 55, 0.08);
    border: 1px solid var(--nesere-gold);
    color: var(--nesere-gold-light);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.about-hero h1 {
    font-size: 42px;
    font-weight: 800;
    line-height: 1.25;
    max-width: 850px;
    margin: 0 auto 18px;
}

.about-hero .gold-text {
    background: linear-gradient(135deg, var(--nesere-gold-light), var(--nesere-gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.about-hero p {
    font-size: 16px;
    color: var(--nesere-muted);
    max-width: 720px;
    margin: 0 auto;
    line-height: 1.65;
}

/* 2. Company Story & Stats Section */
.story-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 70px 20px;
}

.story-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 45px;
    align-items: center;
}

@media (max-width: 900px) {
    .story-grid {
        grid-template-columns: 1fr;
    }
}

.story-text-col .sub-tag {
    color: var(--nesere-gold);
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    display: block;
}

.story-text-col h2 {
    font-size: 32px;
    color: #FFFFFF;
    margin: 0 0 20px 0;
    line-height: 1.3;
}

.story-text-col p {
    font-size: 15px;
    color: var(--nesere-muted);
    line-height: 1.75;
    margin-bottom: 18px;
}

/* Stats Box */
.stats-card-box {
    background: var(--nesere-dark-card);
    border: 1px solid var(--nesere-border);
    border-radius: 12px;
    padding: 35px 30px;
    display: grid;
    grid-template-columns: 1fr;
    gap: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.stat-single-row {
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding-bottom: 18px;
}

.stat-single-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.stat-single-row h3 {
    font-size: 40px;
    color: var(--nesere-gold);
    margin: 0 0 6px 0;
    font-weight: 800;
    letter-spacing: 0.5px;
}

.stat-single-row span {
    font-size: 13px;
    color: #FFFFFF;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
}

/* 3. Vision, Mission & Values */
.pillars-section {
    background: #0E0F12;
    border-top: 1px solid var(--nesere-border);
    border-bottom: 1px solid var(--nesere-border);
    padding: 80px 20px;
}

.pillars-container {
    max-width: 1200px;
    margin: 0 auto;
}

.center-title-tag {
    text-align: center;
    color: var(--nesere-gold);
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    display: block;
}

.center-heading-main {
    text-align: center;
    font-size: 32px;
    color: #FFFFFF;
    margin: 0 0 45px 0;
}

.pillars-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

@media (max-width: 900px) {
    .pillars-grid {
        grid-template-columns: 1fr;
    }
}

.pillar-card {
    background: var(--nesere-dark-card);
    border: 1px solid var(--nesere-border);
    border-radius: 12px;
    padding: 35px 28px;
    transition: transform 0.25s, border-color 0.25s;
}

.pillar-card:hover {
    transform: translateY(-4px);
    border-color: var(--nesere-gold);
}

.pillar-card h3 {
    color: var(--nesere-gold);
    font-size: 20px;
    margin: 0 0 14px 0;
}

.pillar-card p {
    color: var(--nesere-muted);
    font-size: 14px;
    line-height: 1.65;
    margin: 0;
}

/* 4. Team Showcase */
.team-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 80px 20px;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

@media (max-width: 992px) {
    .team-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 550px) {
    .team-grid {
        grid-template-columns: 1fr;
    }
}

.team-card {
    background: var(--nesere-dark-card);
    border: 1px solid var(--nesere-border);
    border-radius: 12px;
    padding: 28px 20px;
    text-align: center;
    transition: border-color 0.25s;
}

.team-card:hover {
    border-color: var(--nesere-gold);
}

.team-avatar-box {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    background: #18191E;
    border: 2px solid var(--nesere-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    color: var(--nesere-gold);
}

.team-card h4 {
    font-size: 17px;
    color: #FFFFFF;
    margin: 0 0 6px 0;
}

.team-card span {
    font-size: 13px;
    color: var(--nesere-muted);
}

/* 5. Bottom CTA Banner */
.about-cta-wrapper {
    max-width: 1200px;
    margin: 20px auto 60px;
    padding: 0 20px;
}

.about-cta-inner {
    background: linear-gradient(135deg, #15171C 0%, #0F1013 100%);
    border: 1px solid #2A2D36;
    border-radius: 12px;
    padding: 45px 30px;
    text-align: center;
}

.about-cta-inner h2 {
    font-size: 28px;
    color: #FFFFFF;
    margin: 0 0 12px 0;
}

.about-cta-inner p {
    font-size: 15px;
    color: var(--nesere-muted);
    max-width: 600px;
    margin: 0 auto 28px;
}

.btn-gold-cta {
    background: linear-gradient(135deg, var(--nesere-gold-light), var(--nesere-gold));
    color: #000;
    font-weight: 700;
    padding: 13px 32px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 15px;
    display: inline-block;
    transition: transform 0.2s;
}

.btn-gold-cta:hover {
    transform: translateY(-2px);
    color: #000;
}
</style>

<!-- 1. HERO AREA -->
<section class="about-hero">
    <span class="about-badge"><?= e($str['hero_badge']) ?></span>
    <h1><?= e($str['hero_title']) ?> <span class="gold-text"><?= e($str['hero_title_gold']) ?></span></h1>
    <p><?= e($str['hero_desc']) ?></p>
</section>

<!-- 2. COMPANY STORY & STATS (በ 100+፣ 3+ Years እና 99.9% የተስተካከለው) -->
<section class="story-section">
    <div class="story-grid">
        <div class="story-text-col">
            <span class="sub-tag"><?= e($str['story_badge']) ?></span>
            <h2><?= e($str['story_title']) ?></h2>
            <p><?= e($str['story_p1']) ?></p>
            <p><?= e($str['story_p2']) ?></p>
        </div>
        <div>
            <div class="stats-card-box">
                <div class="stat-single-row">
                    <h3><?= e($str['stat_1']) ?></h3>
                    <span><?= e($str['stat_1_txt']) ?></span>
                </div>
                <div class="stat-single-row">
                    <h3><?= e($str['stat_2']) ?></h3>
                    <span><?= e($str['stat_2_txt']) ?></span>
                </div>
                <div class="stat-single-row">
                    <h3><?= e($str['stat_3']) ?></h3>
                    <span><?= e($str['stat_3_txt']) ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. VISION, MISSION & VALUES -->
<section class="pillars-section">
    <div class="pillars-container">
        <span class="center-title-tag"><?= e($str['mv_badge']) ?></span>
        <h2 class="center-heading-main"><?= e($str['mv_title']) ?></h2>

        <div class="pillars-grid">
            <div class="pillar-card">
                <h3><?= e($str['vision_title']) ?></h3>
                <p><?= e($str['vision_desc']) ?></p>
            </div>
            <div class="pillar-card">
                <h3><?= e($str['mission_title']) ?></h3>
                <p><?= e($str['mission_desc']) ?></p>
            </div>
            <div class="pillar-card">
                <h3><?= e($str['values_title']) ?></h3>
                <p><?= e($str['values_desc']) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- 4. CREATIVE TEAM SHOWCASE -->
<section class="team-section">
    <span class="center-title-tag"><?= e($str['team_badge']) ?></span>
    <h2 class="center-heading-main" style="margin-bottom: 12px;"><?= e($str['team_title']) ?></h2>
    <p style="text-align: center; color: var(--nesere-muted); font-size: 15px; max-width: 650px; margin: 0 auto 45px; line-height: 1.6;">
        <?= e($str['team_sub']) ?>
    </p>

    <div class="team-grid">
        <div class="team-card">
            <div class="team-avatar-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h4>NESERE Team</h4>
            <span><?= e($str['team_1_role']) ?></span>
        </div>
        <div class="team-card">
            <div class="team-avatar-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </div>
            <h4>Cinematography Unit</h4>
            <span><?= e($str['team_2_role']) ?></span>
        </div>
        <div class="team-card">
            <div class="team-avatar-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="10 8 16 12 10 16 10 8"></polygon>
                </svg>
            </div>
            <h4>Post & Color Lab</h4>
            <span><?= e($str['team_3_role']) ?></span>
        </div>
        <div class="team-card">
            <div class="team-avatar-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <h4>Growth & Strategy</h4>
            <span><?= e($str['team_4_role']) ?></span>
        </div>
    </div>
</section>

<!-- 5. CALL TO ACTION -->
<div class="about-cta-wrapper">
    <div class="about-cta-inner">
        <h2><?= e($str['cta_heading']) ?></h2>
        <p><?= e($str['cta_desc']) ?></p>
        <a href="service_request.php" class="btn-gold-cta"><?= e($str['cta_btn']) ?></a>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>