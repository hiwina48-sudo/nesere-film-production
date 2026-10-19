<?php
/**
 * NESERE Film Production - Services & Packages
 * Luxury Black & Gold Theme | Bilingual (AM/EN) | Ribbon Badge on Right
 */
require_once __DIR__ . '/config/config.php';

$current_lang = $_SESSION['lang'] ?? 'am';
if (isset($_GET['lang'])) {
    $current_lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $current_lang;
}

$page_title = ($current_lang === 'am') ? 'አገልግሎቶች እና ፓኬጆች | ንስር ፊልም ፕሮዳክሽን' : 'Services & Packages | Nesere Film Production';
require_once __DIR__ . '/includes/header.php';
?>

<style>
:root {
    --srv-bg: #0B0B0B;
    --srv-card: #121316;
    --srv-border: #1E2026;
    --srv-gold: #D4AF37;
    --srv-gold-light: #F2D16B;
    --srv-muted: #8E929D;
    --srv-white: #FFFFFF;
}

body {
    background-color: var(--srv-bg);
    color: var(--srv-white);
}

.srv-container {
    max-width: 1200px;
    margin: 40px auto 90px;
    padding: 0 20px;
}

.srv-header {
    text-align: center;
    margin-bottom: 50px;
}

.srv-header h1 {
    font-size: 32px;
    color: var(--srv-gold);
    margin-bottom: 10px;
}

.srv-header p {
    color: var(--srv-muted);
    font-size: 15px;
}

.category-title {
    font-size: 22px;
    color: var(--srv-gold-light);
    border-bottom: 1px solid var(--srv-border);
    padding-bottom: 10px;
    margin: 50px 0 25px 0;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.pkg-card {
    background: var(--srv-card);
    border: 1px solid var(--srv-border);
    border-radius: 12px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s, border-color 0.3s;
    position: relative;
    overflow: hidden;
}

.pkg-card.recommended {
    border-color: var(--srv-gold);
    box-shadow: 0 0 25px rgba(212, 175, 55, 0.18);
}

.pkg-card:hover {
    border-color: var(--srv-gold);
    transform: translateY(-5px);
}

/* Luxury Gold Ribbon on the Right Side */
.ribbon-recommended {
    position: absolute;
    top: 22px;
    right: -35px;
    background: linear-gradient(135deg, var(--srv-gold-light), var(--srv-gold));
    color: #0B0B0B;
    font-size: 10.5px;
    font-weight: 800;
    padding: 6px 42px;
    text-transform: uppercase;
    transform: rotate(45deg);
    box-shadow: 0 3px 6px rgba(0,0,0,0.4);
    letter-spacing: 1px;
    z-index: 2;
}

.pkg-title {
    font-size: 20px;
    color: #fff;
    margin-bottom: 10px;
    font-weight: 700;
}

.pkg-price {
    font-size: 18px;
    color: var(--srv-gold);
    font-weight: 800;
    margin-bottom: 20px;
}

.pkg-features {
    list-style: none;
    padding: 0;
    margin: 0 0 25px 0;
}

.pkg-features li {
    font-size: 13.5px;
    color: #D1D1D6;
    margin-bottom: 10px;
    position: relative;
    padding-left: 20px;
}

.pkg-features li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--srv-gold);
}

.btn-order {
    display: block;
    text-align: center;
    padding: 12px;
    background: linear-gradient(135deg, var(--srv-gold-light), var(--srv-gold));
    color: #0B0B0B;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 700;
    font-size: 14px;
    transition: opacity 0.2s, transform 0.2s;
}

.btn-order:hover {
    opacity: 0.9;
    transform: scale(1.02);
}
</style>

<div class="srv-container">
    <div class="srv-header">
        <h1><?= ($current_lang === 'am') ? 'የፕሮዳክሽን እና አገልግሎት ፓኬጆች' : 'Production & Service Packages' ?></h1>
        <p><?= ($current_lang === 'am') ? 'ለእርስዎ ድርጅት፣ ጥበብ እና ማስታወሻ የሚሆኑ የላቀ ጥራት ያላቸው ፕሮዳክሽን አማራጮች' : 'High quality production options tailored for your brand, art, and memories.' ?></p>
    </div>

    <!-- 1. Serial Drama Packages -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '1. የተከታታይ ድራማ ፓኬጆች (Serial Drama)' : '1. Serial Drama Packages' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Standard Drama Package</div>
                <div class="pkg-price">24,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ <?= ($current_lang === 'am') ? 'ክፍል' : 'Episode' ?></span></div>
                <ul class="pkg-features">
                    <li>Pre-Production: የድራማውን ስክሪፕት በ Final Draft ማዘጋጀት[cite: 9]</li>
                    <li>Production: በሶኒ ካሜራ (Sony Alpha 7 IV) እና በ Zoom H6 ከ DJI Mic 2 ጋር ቀረጻ[cite: 9]</li>
                    <li>Post-Production: ቪዲዮ ኤዲቲንግ (Premiere Pro) እና ከለር እርማት[cite: 9]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Best</div>
            <div>
                <div class="pkg-title">Cinematic Drama Pro</div>
                <div class="pkg-price">25,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ <?= ($current_lang === 'am') ? 'ክፍል' : 'Episode' ?></span></div>
                <ul class="pkg-features">
                    <li>Pre-Production: በ ShotsPlan ሴት ዲያግራም እና Shot lists ዝግጅት[cite: 9]</li>
                    <li>Production: በ Blackmagic Pocket 6K Pro እና Zoom H8 ቀረጻ[cite: 9]</li>
                    <li>Post-Production: በ DaVinci Resolve ሲኒማቲክ ከለር ግሬዲንግ[cite: 9]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Master VIP Serial Package</div>
                <div class="pkg-price">35,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ <?= ($current_lang === 'am') ? 'ክፍል' : 'Episode' ?></span></div>
                <ul class="pkg-features">
                    <li>Pre-Production: የቦታ ምርጫ እና የተዋናዮች ድልድል (Casting)[cite: 9]</li>
                    <li>Production: በ Sony FX3 ካሜራ (S-Log3/Cine EI) የሲኒማቲክ ቀረጻ[cite: 9]</li>
                    <li>Post-Production: VFX በ After Effects እና Audio Mastering[cite: 9]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 2. YouTube & Podcast Packages -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '2. ዩቲዩብ እና ፖድካስት ፓኬጆች' : '2. YouTube & Podcast Packages' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Single Episode (Basic)</div>
                <div class="pkg-price">18,000 ETB</div>
                <ul class="pkg-features">
                    <li>እስከ 1 ሰዓት የሚደርስ ቀረጻ (Up to 1 hour shoot)[cite: 10]</li>
                    <li>2 የካሜራ አቀማመጥ (Sony Alpha series)[cite: 10]</li>
                    <li>ንጹህ የድምጽ ቀረጻ እና ሚክሲንግ[cite: 10]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Popular</div>
            <div>
                <div class="pkg-title">Monthly 4 Eps (Standard)</div>
                <div class="pkg-price">60,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span></div>
                <ul class="pkg-features">
                    <li>እስከ 3 ካሜራዎች አቀማመጥ (3 Camera setup)[cite: 10]</li>
                    <li>8 አጫጭር ቪዲዮዎች ለ Shorts/TikTok/Reels[cite: 10]</li>
                    <li>ባለብዙ ትራክ ድምጽ እና ታምብኔል ዲዛይን[cite: 10]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Full Show VIP (Premium)</div>
                <div class="pkg-price">130,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span></div>
                <ul class="pkg-features">
                    <li>የስቱዲዮ ማስዋብ እና የገጽታ ግንባታ (Custom styling)[cite: 10]</li>
                    <li>በ Sony FX3 እና Zoom H8 የተደገፈ ጥራት[cite: 10]</li>
                    <li>ያልተገደበ አጫጭር ቪዲዮዎች (Unlimited Shorts)[cite: 10]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 3. Social Media Management Packages (Newly Added) -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '3. የሶሻል ሚዲያ ማኔጅመንት ፓኬጆች' : '3. Social Media Management Packages' ?>[cite: 16]</h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Starter Package</div>
                <div class="pkg-price">21,400 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span>[cite: 16]</div>
                <ul class="pkg-features">
                    <li>Logo Design[cite: 16]</li>
                    <li>12 Videos Per Month[cite: 16]</li>
                    <li>TikTok Management[cite: 16]</li>
                    <li>Monthly Report & Build Community to Telegram[cite: 16]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Popular</div>
            <div>
                <div class="pkg-title">Standard Package</div>
                <div class="pkg-price">30,600 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span>[cite: 16]</div>
                <ul class="pkg-features">
                    <li>Logo Design[cite: 16]</li>
                    <li>16 Videos & 6 Graphics Per Month[cite: 16]</li>
                    <li>Post Boosting[cite: 16]</li>
                    <li>Facebook & TikTok Management[cite: 16]</li>
                    <li>Monthly Report & Build Community to Telegram[cite: 16]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Premium Package</div>
                <div class="pkg-price">41,300 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span>[cite: 16]</div>
                <ul class="pkg-features">
                    <li>Logo Design[cite: 16]</li>
                    <li>20 Videos & 8 Graphics Per Month[cite: 16]</li>
                    <li>Post Boosting[cite: 16]</li>
                    <li>Facebook, Instagram & TikTok Management[cite: 16]</li>
                    <li>Monthly Report & Build Community to Telegram[cite: 16]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 4. Wedding & Event Packages -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '4. የሰርግ እና ልዩ ዝግጅት ቀረጻ' : '4. Wedding & Event Coverage' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Classic Moments (Basic)</div>
                <div class="pkg-price">45,000 ETB[cite: 11]</div>
                <ul class="pkg-features">
                    <li>ሙሉ የዝግጅት ቀረጻ በ 2 ባለሙያዎች[cite: 11]</li>
                    <li>1 የፎቶ ካሜራ እና 3-5 ደቂቃ ሃይላይት ቪዲዮ[cite: 11]</li>
                    <li>ሙሉ የፕሮግራሙ ቪዲዮ እና 1 ቲክቶክ ቪዲዮ[cite: 11]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Hot</div>
            <div>
                <div class="pkg-title">Royal Cinema (Standard)</div>
                <div class="pkg-price">75,000 ETB[cite: 11]</div>
                <ul class="pkg-features">
                    <li>4K ባለብዙ ካሜራ ቀረጻ እና ድሮን (Drone)[cite: 11]</li>
                    <li>በ 48 ሰዓት ውስጥ የሚለቀቅ አጭር ማስታወቂያ (Teaser)[cite: 11]</li>
                    <li>DaVinci Resolve ፕሮፌሽናል ከለር ግሬዲንግ[cite: 11]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Imperial Luxury (Premium)</div>
                <div class="pkg-price">180,000 ETB[cite: 11]</div>
                <ul class="pkg-features">
                    <li>ከሰርግ በፊት የሚሰራ የፍቅር ታሪክ አጭር ፊልም (Pre-wedding)[cite: 11]</li>
                    <li>ዳይሬክተር እና 4 የፕሮዳክሽን ባለሙያዎች[cite: 11]</li>
                    <li>Same Day Edit (SDE) እና የተሟላ የፎቶ አልበም[cite: 11]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 5. Advertisement & Social Media Campaigns -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '5. ማስታወቂያ እና የሶሻል ሚዲያ ዘመቻ' : '5. Advertisement & Social Media Campaigns' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Campaign Kickstart (Basic)</div>
                <div class="pkg-price">15,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span>[cite: 12]</div>
                <ul class="pkg-features">
                    <li>1 የተመረጠ ፕላትፎርም (Facebook ወይም Instagram)[cite: 12]</li>
                    <li>ደንበኞችን የመለየት ጥናት እና A/B Testing[cite: 12]</li>
                    <li>የ 1 ወር ሙሉ ክትትል እና ሪፖርት[cite: 12]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Top</div>
            <div>
                <div class="pkg-title">Growth Multiplier (Standard)</div>
                <div class="pkg-price">35,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span>[cite: 12]</div>
                <ul class="pkg-features">
                    <li>በ 2 የሶሻል ሚዲያ አውታሮች ላይ የሚደረግ ዘመቻ[cite: 12]</li>
                    <li>የተፎካካሪዎች ገበያ ጥናት እና Retargeting[cite: 12]</li>
                    <li>ዝርዝር ወርሃዊ ሪፖርት እና የበጀት አስተዳደር[cite: 12]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Enterprise Scale (Premium)</div>
                <div class="pkg-price">80,000 ETB <span style="font-size:12px; color:var(--srv-muted);">/ month</span>[cite: 12]</div>
                <ul class="pkg-features">
                    <li>በሁሉም የሶሻል ሚዲያ አውታሮች ሰፊ ዘመቻ[cite: 12]</li>
                    <li>ከታዋቂ ኢንፍሉዌንሰሮች ጋር መስራት[cite: 12]</li>
                    <li>የተመደበ ዲጂታል ማርኬቲንግ ማናጀር እና ስትራቴጂ ስብሰባ[cite: 12]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 6. TV Commercials -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '6. የቴሌቪዥን ማስታወቂያ ፕሮዳክሽን (TVC)' : '6. TV Commercials' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Basic TVC</div>
                <div class="pkg-price">50,000+ ETB[cite: 13]</div>
                <ul class="pkg-features">
                    <li>እስከ 1 ደቂቃ የሚደርስ የማስታወቂያ ቪዲዮ (60 sec)[cite: 13]</li>
                    <li>የ 1 ቀን ቀረጻ በ 1 ቦታ (በ 4K ካሜራ)[cite: 13]</li>
                    <li>ፕሮፌሽናል ድምጽ (Voiceover) እና ጀርባ ሙዚቃ[cite: 13]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Choice</div>
            <div>
                <div class="pkg-title">Corporate Standard</div>
                <div class="pkg-price">120,000+ ETB[cite: 13]</div>
                <ul class="pkg-features">
                    <li>እስከ 2 ቀናት ቀረጻ በ 2 የተለያዩ ቦታዎች[cite: 13]</li>
                    <li>በሲኒማ ካሜራ (Sony FX3/Blackmagic) እና ድሮን ቀረጻ[cite: 13]</li>
                    <li>ሞሽን ግራፊክስ እና 2D አኒሜሽን[cite: 13]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Cinematic Premium</div>
                <div class="pkg-price">350,000+ ETB[cite: 13]</div>
                <ul class="pkg-features">
                    <li>የተሟላ የፕሮዳክሽን ክሩ (ዳይሬክተር፣ ሜካፕ አርቲስት)[cite: 13]</li>
                    <li>ኦሪጅናል የሙዚቃ ቅንብር እና ጂንግል[cite: 13]</li>
                    <li>ከፍተኛ የቪዥዋል ኢፌክት እና 3D አኒሜሽን[cite: 13]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 7. Post Production & Editing -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '7. የድህረ-ቀረጻ እና ኤዲቲንግ (Post Production)' : '7. Post Production & Editing' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Essential Edit (Basic)</div>
                <div class="pkg-price">15,000+ ETB[cite: 14]</div>
                <ul class="pkg-features">
                    <li>መደበኛ ቪዲዮ ኤዲቲንግ በ Premiere Pro[cite: 14]</li>
                    <li>መሰረታዊ የከለር እርማት እና ቀላል ታይትል[cite: 14]</li>
                    <li>መደበኛ የድምጽ ማስተካከል[cite: 14]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Popular</div>
            <div>
                <div class="pkg-title">Pro Cinematic Edit</div>
                <div class="pkg-price">35,000+ ETB[cite: 14]</div>
                <ul class="pkg-features">
                    <li>ፕሮፌሽናል ኤዲቲንግ እና ትራንዚሽን[cite: 14]</li>
                    <li>ሲኒማቲክ ከለር ግሬዲንግ በ DaVinci Resolve[cite: 14]</li>
                    <li>የድምፅ ዲዛይን እና ሞሽን ግራፊክስ[cite: 14]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Masterpiece VFX & Mastering</div>
                <div class="pkg-price">80,000+ ETB[cite: 14]</div>
                <ul class="pkg-features">
                    <li>የተወሳሰበ የቪዥዋል ኢፌክት (VFX) በ After Effects[cite: 14]</li>
                    <li>ማስተር ከለር ግሬዲንግ እና ኦዲዮ ማስተሪንግ[cite: 14]</li>
                    <li>3D ሎጎ አኒሜሽን እና የትሬለር ዝግጅት[cite: 14]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>

    <!-- 8. Web Development -->
    <h2 class="category-title"><?= ($current_lang === 'am') ? '8. የዌብሳይት እና ዌብ አፕሊኬሽን ማበልጸግ' : '8. Web Development & Apps' ?></h2>
    <div class="packages-grid">
        <div class="pkg-card">
            <div>
                <div class="pkg-title">Portfolio Landing (Basic)</div>
                <div class="pkg-price">25,000 ETB[cite: 15]</div>
                <ul class="pkg-features">
                    <li>1 ዓመት ነፃ ዶሜይን እና ሆስቲንግ[cite: 15]</li>
                    <li>ለሞባይል እና ኮምፒዩተር የሚመች ዲዛይን[cite: 15]</li>
                    <li>እስከ 5 ክፍሎች ያሉት ላንዲንግ ገጽ እና ፎርም[cite: 15]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card recommended">
            <div class="ribbon-recommended">Popular</div>
            <div>
                <div class="pkg-title">Corporate Portal (Standard)</div>
                <div class="pkg-price">55,000 ETB[cite: 15]</div>
                <ul class="pkg-features">
                    <li>ብጁ የድርጅት ዲዛይን እና የጀርባ አስተዳደር (CMS)[cite: 15]</li>
                    <li>የሁለት ቋንቋዎች ድጋፍ (አማርኛ እና እንግሊዝኛ)[cite: 15]</li>
                    <li>መሰረታዊ SEO እና የድርጅት ኢሜል አካውንቶች[cite: 15]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>

        <div class="pkg-card">
            <div>
                <div class="pkg-title">Custom Web App (Premium)</div>
                <div class="pkg-price">120,000 ETB[cite: 15]</div>
                <ul class="pkg-features">
                    <li>የደንበኞች አካውንት አስተዳደር እና ዳሽቦርድ[cite: 15]</li>
                    <li>የክፍያ ስርዓት ትስስር (Telebirr, Chapa)[cite: 15]</li>
                    <li>ከፍተኛ ደህንነት፣ ባካፕ እና የ 1 ዓመት ነፃ ድጋፍ[cite: 15]</li>
                </ul>
            </div>
            <a href="service_request.php" class="btn-order"><?= ($current_lang === 'am') ? 'አሁን እዘዝ' : 'Order Now' ?></a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>