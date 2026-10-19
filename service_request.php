<?php
/**
 * NESERE Film Production - Service Packages Selector Page
 * Luxury Black (#0B0B0B) & Eagle Gold (#D4AF37) Theme | Bilingual (AM/EN)
 */
require_once __DIR__ . '/config/config.php';

// ቋንቋ መቀየሪያ
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

// የተመረጠው አገልግሎት መለያ (1 እስከ 8)
$preselected_service = (int)($_GET['service_id'] ?? 1);
if ($preselected_service < 1 || $preselected_service > 8) {
    $preselected_service = 1;
}

// ትክክለኛዎቹ የንስር ፊልም ፕሮዳክሽን ፓኬጆች ዝርዝር (በሁለቱም ቋንቋዎች የተዋቀረ)
$packages_data = [
    1 => [
        'title' => ($current_lang === 'am') ? 'የፊልም እና ቪዲዮ ፕሮዳክሽን (የተከታታይ ድራማ)' : 'Film & Video Production',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'መደበኛ የድራማ ፓኬጅ' : 'Standard Drama Package', 
                'price' => '24,000 ETB', 
                'budget' => 24000, 
                'features' => ($current_lang === 'am') ? [
                    'የመጨረሻ የሓሳብ ንድፍ ስክሪፕት እና የቀረጻ ሰሌዳ', 
                    'ሶኒ አልፋ 7 IV እና ዞም H6 / DJI Mic 2 ካሜራዎች', 
                    'ፕሪሚየር ፕሮ (Premiere Pro) ኤዲቲንግ እና ከለር ኮሬክሽን'
                ] : ['Final Draft script & schedule', 'Sony Alpha 7 IV & Zoom H6 / DJI Mic 2', 'Premiere Pro editing & Color Correction']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ሲኒማቲክ ድራማ ፕሮ' : 'Cinematic Drama Pro', 
                'price' => '25,000 ETB', 
                'budget' => 25000, 
                'features' => ($current_lang === 'am') ? [
                    'የሾት ፕላን የቦታ ንድፍ እና የተኩስ ዝርዝር', 
                    'ብላክማጂክ ፖኬት 6K ፕሮ እና ዞም H8', 
                    'ዳቪንቺ ሪሶልቭ ከለር ግሬዲንግ እና የድምፅ ዲዛይን'
                ] : ['ShotsPlan set diagram & shot lists', 'Blackmagic Pocket 6K Pro & Zoom H8', 'DaVinci Resolve color grading & sound design']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ማስተር ቪአይፒ የድራማ ፓኬጅ' : 'Master VIP Serial Package', 
                'price' => '35,000 ETB', 
                'budget' => 35000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? [
                    'ሙሉ ዝግጅት፣ የቦታ ፍለጋ እና ተዋናዮች ምርጫ ድጋፍ', 
                    'ሶኒ FX3 (S-Log3) እና ፕሮፌሽናል ላይቲንግ', 
                    'አፍተር ኢፌክትስ (VFX) እና የድምፅ ማስተርንግ'
                ] : ['Full prep, location & casting support', 'Sony FX3 (S-Log3/Cine EI) & pro lighting', 'After Effects VFX & Audio Mastering']
            ]
        ]
    ],
    2 => [
        'title' => ($current_lang === 'am') ? 'የቴሌቪዥን ማስታወቂያዎች' : 'TV Commercials',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'መሰረታዊ የቲቪ ማስታወቂያ' : 'Basic TVC', 
                'price' => '50,000 ETB', 
                'budget' => 50000, 
                'features' => ($current_lang === 'am') ? ['እስከ 60 ሰከንድ የሚደርስ ማስታወቂያ', 'በ 1 ቦታ የሚደረግ 1 ቀን ቀረጻ', 'ድምፅ (Voiceover) እና የጽሁፍ እነማዎች'] : ['Up to 60 sec TVC', '1 Day shoot at 1 location (4K Camera)', 'Voiceover, BGM & text animations']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ድርጅታዊ ስታንዳርድ ማስታወቂያ' : 'Corporate Standard', 
                'price' => '120,000 ETB', 
                'budget' => 120000, 
                'features' => ($current_lang === 'am') ? ['በ 2 የተለያዩ ቦታዎች የሚደረግ የ 2 ቀናት ቀረጻ', 'ሶኒ FX3 እና ድሮን ቀረጻ', 'ሞሽን ግራፊክስ እና 2ዲ እነማ'] : ['Up to 2 days shoot at 2 locations', 'Sony FX3/Blackmagic & Drone shoot', 'Motion graphics & 2D animation']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ሲኒማቲክ ፕሪሚየም ማስታወቂያ' : 'Cinematic Premium', 
                'price' => '350,000 ETB', 
                'budget' => 350000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['ሙሉ የባለሙያ ቡድን (ዳይሬክተር፣ አርት ዳይሬክተር)', 'ልዩ የሙዚቃ ግጥም እና ዜማ (Jingle)', 'ከፍተኛ የቪኤፍኤክስ (VFX) እና 3ዲ እነማ'] : ['Full crew (Director, Art Director, Makeup)', 'Original Jingle & Music Production', 'Advanced VFX & 3D Animation']
            ]
        ]
    ],
    3 => [
        'title' => ($current_lang === 'am') ? 'የዩቲዩብ እና ፖድካስት ፕሮዳክሽን' : 'YouTube & Podcast Production',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'ነጠላ ክፍል (መሰረታዊ)' : 'Single Episode (Basic)', 
                'price' => '18,000 ETB', 
                'budget' => 18000, 
                'features' => ($current_lang === 'am') ? ['እስከ 1 ሰዓት የሚደርስ ቀረጻ', 'የ 2 ካሜራ ዝግጅት', 'የስቱዲዮ ማይክ ድምፅ ቅንብር እና ኤዲቲንግ'] : ['Up to 1 hour shoot', '2 Camera setup (Sony Alpha series)', 'Studio mic mixing & basic editing']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ወርሃዊ 4 ክፍሎች (ስታንዳርድ)' : 'Monthly 4 Eps (Standard)', 
                'price' => '60,000 ETB', 
                'budget' => 60000, 
                'features' => ($current_lang === 'am') ? ['የ 3 ካሜራ ዝግጅት', '8 አጫጭር ቪዲዮዎች ለቲክቶክ/ሪልስ', 'የ thumbnailUrl ዲዛይን'] : ['3 Camera setup', '8 Short clips for TikTok/Reels/Shorts', 'Multi-track audio & Thumbnail design']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ሙሉ ትርኢት ቪአይፒ (ፕሪሚየም)' : 'Full Show VIP (Premium)', 
                'price' => '130,000 ETB', 
                'budget' => 130000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['ልዩ ስቱዲዮ ማስዋቢያ እና አቀማመጥ', 'ሶኒ FX3 ባለብዙ ካሜራ ዝግጅት', 'ያልተገደበ አጫጭር ቪዲዮዎች እና የከለር ግሬዲንግ'] : ['Custom set decoration & styling', 'Sony FX3 & Zoom H8 multi-cam setup', 'Unlimited Shorts & Pro Color Grading']
            ]
        ]
    ],
    4 => [
        'title' => ($current_lang === 'am') ? 'የሶሻል ሚዲያ ማኔጅመንት ፓኬጆች' : 'Social Media Management Packages',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'ጀማሪ ፓኬጅ' : 'Starter Package', 
                'price' => '21,400 ETB', 
                'budget' => 21400, 
                'features' => ($current_lang === 'am') ? ['የሎጎ ዲዛይን', 'በወር 12 ቪዲዮዎች', 'የቲክቶክ ገጽ አስተዳደር እና ወርሃዊ ሪፖርት'] : ['Logo Design', '12 Videos Per Month', 'TikTok Management & Monthly Report']
            ],
            [
                'name' => ($current_lang === 'am') ? 'መካከለኛ ፓኬጅ (ስታንዳርድ)' : 'Standard Package', 
                'price' => '30,600 ETB', 
                'budget' => 30600, 
                'features' => ($current_lang === 'am') ? ['የሎጎ ዲዛይን እና ፖስት ቡስቲንግ', 'በወር 16 ቪዲዮዎች እና 6 ግራፊክሶች', 'የፌስቡክ እና ቲክቶክ አስተዳደር'] : ['Logo Design & Post Boosting', '16 Videos & 6 Graphics Per Month', 'Facebook & TikTok Management']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ፕሪሚየም ፓኬጅ' : 'Premium Package', 
                'price' => '41,300 ETB', 
                'budget' => 41300, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['በወር 20 ቪዲዮዎች እና 8 ግራፊክሶች', 'የፌስቡክ፣ ኢንስታግራም እና ቲክቶክ አስተዳደር', 'የቴሌግራም ማህበረሰብ እና ሪፖርት'] : ['20 Videos & 8 Graphics Per Month', 'FB, IG & TikTok Management', 'Telegram Community & Report']
            ]
        ]
    ],
    5 => [
        'title' => ($current_lang === 'am') ? 'የሰርግ እና ሁነቶች ቀረጻ' : 'Wedding & Event Coverage',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'ክላሲክ የሰርግ ቀረጻ' : 'Classic Moments (Basic)', 
                'price' => '45,000 ETB', 
                'budget' => 45000, 
                'features' => ($current_lang === 'am') ? ['በ 2 ካሜራ ባለሙያዎች የሚደረግ ሙሉ ቀረጻ', '1 የፎቶ ካሜራ እና 3-5 ደቂቃ ድምቀት (Highlight)', 'ሙሉ የዝግጅቱ ቪዲዮ እና የቲክቶክ ክሊፕ'] : ['Full ceremony coverage with 2 Videographers', '1 Photo Camera & 3-5 min highlights', 'Full Event Video & TikTok clip']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ሮያል ሲኒማ' : 'Royal Cinema (Standard)', 
                'price' => '75,000 ETB', 
                'budget' => 75000, 
                'features' => ($current_lang === 'am') ? ['4ኬ ባለብዙ ካሜራ ቀረጻ እና ድሮን', 'በ 48 ሰዓታት ውስጥ የሚደርስ ቲዘር', 'ዳቪንቺ ሪሶልቭ ከለር ግሬዲንግ'] : ['4K Multi-camera coverage & Drone', 'Teaser in 48 hrs', 'DaVinci Resolve color grading']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ኢምፔሪያል ሉክሰሪ (ፕሪሚየም)' : 'Imperial Luxury (Premium)', 
                'price' => '180,000 ETB', 
                'budget' => 180000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['ከሠርግ በፊት የሚደረግ የፍቅር ታሪክ (Love Story) ቀረጻ', 'ዳይሬክተር እና 4 የቡድን አባላት', 'በዕለቱ የሚዘጋጅ ኤዲት (SDE) እና የፎቶ አልበም'] : ['Pre-wedding Love Story film shoot', 'Director + 4 Crew', 'Same Day Edit (SDE) & photo album']
            ]
        ]
    ],
    6 => [
        'title' => ($current_lang === 'am') ? 'የድህረ-ቀረጻ እና ኤዲቲንግ' : 'Post Production & Editing',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'መሰረታዊ ኤዲቲንግ' : 'Essential Edit (Basic)', 
                'price' => '15,000 ETB', 
                'budget' => 15000, 
                'features' => ($current_lang === 'am') ? ['በፕሪሚየር ፕሮ መደበኛ ቁርረጥ', 'መሰረታዊ ከለር ኮሬክሽን እና ርዕሶች', 'መደበኛ የድምፅ ቅንብር'] : ['Standard cutting in Premiere Pro', 'Basic color correction & titles', 'Standard audio mixing']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ፕሮ ሲኒማቲክ ኤዲት' : 'Pro Cinematic Edit', 
                'price' => '35,000 ETB', 
                'budget' => 35000, 
                'features' => ($current_lang === 'am') ? ['ከፍተኛ የቪዲዮ ኤዲቲንግ እና ሽግግሮች', 'በዳቪንቺ የተደረገ ሲኒማቲክ ከለር ግሬዲንግ', 'የድምፅ ዲዛይን እና ሞሽን ግራፊክስ'] : ['Advanced video editing & transitions', 'Cinematic color grading in DaVinci', 'Sound design & motion graphics']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ማስተርፒስ ቪኤፍኤክስ እና ማስተርንግ' : 'Masterpiece VFX & Mastering', 
                'price' => '80,000 ETB', 
                'budget' => 80000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['በአፍተር ኢፌክትስ የተሰሩ የላቁ ቪኤፍኤክስ (VFX)', 'ዋና ከለር ግሬዲንግ እና ድምፅ ማስተርንግ', '3ዲ ሎጎ እነማ እና የቴለር ዝግጅት'] : ['Advanced VFX in After Effects', 'Master color grading & Audio mastering', '3D Logo animation & Trailer prep']
            ]
        ]
    ],
    7 => [
        'title' => ($current_lang === 'am') ? 'የድረ-ገጽ ልማት' : 'Web Development & Apps',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'ፖርትፎሊዮ ላንዲንግ (መሰረታዊ)' : 'Portfolio Landing (Basic)', 
                'price' => '25,000 ETB', 
                'budget' => 25000, 
                'features' => ($current_lang === 'am') ? ['1 ዓመት ነጻ ዶሜይን እና ሆስቲንግ', 'ለስልኮች ምቹ የሆነ (Responsive) ዲዛይን', 'እስከ 5 ክፍሎች ያሉት ድረ-ገጽ'] : ['1 Year Free Domain & Hosting', 'Responsive mobile-friendly design', 'Up to 5 sections landing page']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ኮርፖሬት ፖርታል (ስታንዳርድ)' : 'Corporate Portal (Standard)', 
                'price' => '55,000 ETB', 
                'budget' => 55000, 
                'features' => ($current_lang === 'am') ? ['ሉክሰሪ የዩአይ/ዩኤክስ (UI/UX) ዲዛይን እና ሲኤምኤስ', 'በሁለት ቋንቋዎች (አማርኛ እና እንግሊዝኛ)', 'መሰረታዊ ኤስኢኦ (SEO) እና የድርጅት ኢሜሎች'] : ['Custom Luxury UI/UX Design & CMS', 'Bilingual support (Amharic & English)', 'Basic SEO & Corporate Emails']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ልዩ ድረ-ገጽ መተግበሪያ (ፕሪሚየም)' : 'Custom Web App (Premium)', 
                'price' => '120,000 ETB', 
                'budget' => 120000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['የተጠቃሚ እና ደንበኛ አካውንት / ዳሽቦርድ', 'የክፍያ መስመር ማቀናጀት (ቴሌብር/ባንክ)', 'ከፍተኛ ደህንነት እና የ 1 ዓመት ድጋፍ'] : ['User & Customer accounts / Dashboard', 'Payment gateway integration (Telebirr)', 'Advanced security & 1-year support']
            ]
        ]
    ],
    8 => [
        'title' => ($current_lang === 'am') ? 'የማስታወቂያ ቡስቲንግ' : 'Advertisement & Boosting',
        'tiers' => [
            [
                'name' => ($current_lang === 'am') ? 'የዘመቻ ኪክስታርት (መሰረታዊ)' : 'Campaign Kickstart (Basic)', 
                'price' => '15,000 ETB', 
                'budget' => 15000, 
                'features' => ($current_lang === 'am') ? ['በ 1 ማህበራዊ ሚዲያ ላይ ማተኮር (ፌስቡክ ወይም ኢንስታግራም)', 'የታለመላቸው ተጠቃሚዎች ጥናት', 'ወርሃዊ የሂደት ሪፖርት'] : ['1 Platform focus (FB or IG)', 'Target audience research & A/B testing', 'Monthly progress reporting']
            ],
            [
                'name' => ($current_lang === 'am') ? 'የዕድገት ማባዣ (ስታንዳርድ)' : 'Growth Multiplier (Standard)', 
                'price' => '35,000 ETB', 
                'budget' => 35000, 
                'features' => ($current_lang === 'am') ? ['በ 2 ማህበራዊ ሚዲያ መድረኮች ላይ መስራት', 'የተወዳዳሪዎች ትንተና እና ማስታወቂያዎች', 'ዝርዝር ትንታኔዎች እና ዕለታዊ በጀት'] : ['2 Social media platforms', 'Competitor analysis & Retargeting ads', 'Detailed analytics & Daily budget']
            ],
            [
                'name' => ($current_lang === 'am') ? 'ኢንተርፕራይዝ ስኬል (ፕሪሚየም)' : 'Enterprise Scale (Premium)', 
                'price' => '80,000 ETB', 
                'budget' => 80000, 
                'popular' => true, 
                'features' => ($current_lang === 'am') ? ['ሁሉም የማህበራዊ ሚዲያ መድረኮች', 'ታዋቂ ሰዎችን (Influencers) ማቀናጀት', 'ልዩ የሚዲያ ማናጀር'] : ['All Social Media Platforms', 'Influencer outreach & management', 'Dedicated Media Manager']
            ]
        ]
    ]
];

// ደንበኛው ፓኬጅ መርጦ "Select & Pay Now" ሲጫን ትዕዛዙን በዳታቤዝ መዝግቦ በቀጥታ ወደ ክፍያ ገጽ መውሰድ
if (isset($_GET['action']) && $_GET['action'] === 'order') {
    $tier_idx = (int)($_GET['tier'] ?? 0);
    if (isset($packages_data[$preselected_service]['tiers'][$tier_idx])) {
        $selected_tier = $packages_data[$preselected_service]['tiers'][$tier_idx];
        $proj_title = $packages_data[$preselected_service]['title'] . ' - ' . $selected_tier['name'];
        $budget = $selected_tier['budget'];

        if (!empty($_SESSION['user'])) {
            $db = db();
            $customer_id = 0;
            if (isset($_SESSION['user']['customer_id'])) {
                $customer_id = (int)$_SESSION['user']['customer_id'];
            } elseif (isset($_SESSION['user']['user_id'])) {
                $customer_id = (int)$_SESSION['user']['user_id'];
            } elseif (isset($_SESSION['user_id'])) {
                $customer_id = (int)$_SESSION['user_id'];
            }

            try {
                $stmt = $db->prepare("
                    INSERT INTO service_requests (customer_id, service_id, project_title, budget, status, payment_status, created_at) 
                    VALUES (?, ?, ?, ?, 'Pending', 'unpaid', NOW())
                ");
                $stmt->execute([$customer_id, $preselected_service, $proj_title, $budget]);
                $request_id = $db->lastInsertId();

                header("Location: customer/pay.php?id=" . $request_id);
                exit;
            } catch (Exception $e) {
                echo "<div style='color:red; background:#111; padding:20px; font-family:monospace;'>Database Insert Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                exit;
            }
        } else {
            header("Location: register.php?service_id={$preselected_service}&budget={$budget}&title=" . urlencode($proj_title));
            exit;
        }
    }
}

$cur_service_data = $packages_data[$preselected_service] ?? $packages_data[1];

// የቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'     => 'የአገልግሎት ፓኬጆች | ንስር ፊልም ፕሮዳክሽን',
        'heading'        => $cur_service_data['title'],
        'sub_heading'    => 'የሚፈልጉትን ፓኬጅ ይምረጡና በቀጥታ ወደ ክፍያ ይቀጥሉ',
        'btn_select_pkg' => 'ይህንን ፓኬጅ ምረጥ እና ክፈል →',
        'popular_badge'  => 'RECOMMENDED',
        'lang_toggle'    => 'English'
    ],
    'en' => [
        'page_title'     => 'Service Packages | Nesere Film Production',
        'heading'        => $cur_service_data['title'],
        'sub_heading'    => 'Select a package tier to proceed directly to payment',
        'btn_select_pkg' => 'Select & Pay Now →',
        'popular_badge'  => 'RECOMMENDED',
        'lang_toggle'    => 'አማርኛ'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/includes/header.php';
?>

<style>
:root {
    --pkg-bg: #0B0B0B;
    --pkg-card: #121316;
    --pkg-border: #1E2026;
    --pkg-gold: #D4AF37;
    --pkg-gold-light: #F2D16B;
    --pkg-muted: #8E929D;
    --pkg-white: #FFFFFF;
}

.packages-wrapper {
    max-width: 1200px;
    margin: 40px auto 100px;
    padding: 0 20px;
    color: var(--pkg-white);
}

.packages-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--pkg-border);
    padding-bottom: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.packages-top-bar h1 {
    font-size: 28px;
    margin: 0 0 6px 0;
    color: #fff;
}

.service-pills-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-bottom: 40px;
    padding: 14px;
    background: #111216;
    border: 1px solid var(--pkg-border);
    border-radius: 12px;
}

.srv-pill-link {
    padding: 8px 16px;
    border-radius: 20px;
    background: #16171D;
    border: 1px solid var(--pkg-border);
    color: var(--pkg-muted);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.srv-pill-link:hover, .srv-pill-link.active {
    background: rgba(212, 175, 55, 0.15);
    border-color: var(--pkg-gold);
    color: #FFFFFF;
}

.packages-selector-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
    gap: 25px;
}

.pkg-select-card {
    background: #14151B;
    border: 1px solid var(--pkg-border);
    border-radius: 14px;
    padding: 32px 25px;
    position: relative;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.pkg-select-card:hover {
    border-color: var(--pkg-gold);
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}

.pkg-select-card.popular {
    border-color: var(--pkg-gold);
    background: linear-gradient(180deg, rgba(212, 175, 55, 0.08), #14151B);
    box-shadow: 0 0 25px rgba(212, 175, 55, 0.15);
}

.pkg-card-title {
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 12px 0;
}

.pkg-price-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.pkg-card-price {
    font-size: 28px;
    font-weight: 800;
    color: var(--pkg-gold-light);
    letter-spacing: 0.5px;
}

.pkg-recommended-pill {
    background: linear-gradient(135deg, var(--pkg-gold-light), var(--pkg-gold));
    color: #000;
    font-size: 10px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 12px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-block;
}

.pkg-card-features {
    list-style: none;
    padding: 0;
    margin: 0 0 30px 0;
    flex-grow: 1;
}

.pkg-card-features li {
    font-size: 13.5px;
    color: #D1D1D6;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    display: flex;
    align-items: center;
    gap: 10px;
}

.pkg-card-features li i {
    color: var(--pkg-gold);
    font-size: 13px;
}

.btn-select-direct {
    display: block;
    width: 100%;
    padding: 13px;
    border-radius: 8px;
    color: #000;
    background: linear-gradient(135deg, var(--pkg-gold-light), var(--pkg-gold));
    text-align: center;
    font-size: 13.5px;
    font-weight: 800;
    text-decoration: none;
    box-sizing: border-box;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-select-direct:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

.pkg-select-card:not(.popular) .btn-select-direct {
    background: #1C1D24;
    border: 1px solid var(--pkg-border);
    color: #fff;
}

.pkg-select-card:not(.popular) .btn-select-direct:hover {
    border-color: var(--pkg-gold);
    color: var(--pkg-gold);
}
</style>

<div class="packages-wrapper">
    <div class="packages-top-bar">
        <div>
            <h1><?= htmlspecialchars($str['heading']) ?></h1>
            <p style="color: var(--pkg-muted); font-size: 14px; margin: 0;"><?= htmlspecialchars($str['sub_heading']) ?></p>
        </div>

        <div>
            <a href="service_request.php?service_id=<?= $preselected_service ?>&lang=<?= ($current_lang === 'am') ? 'en' : 'am' ?>" style="padding: 8px 15px; background: #15161B; border: 1px solid var(--pkg-border); border-radius: 6px; color: var(--pkg-gold); text-decoration: none; font-size: 13px; font-weight: 700;">
                <i class="fa fa-globe"></i> <?= htmlspecialchars($str['lang_toggle']) ?>
            </a>
        </div>
    </div>

    <!-- ሁሉንም አገልግሎቶች መቀያየሪያ -->
    <div class="service-pills-bar">
        <?php foreach ($packages_data as $sid => $sdata): ?>
            <a href="service_request.php?service_id=<?= $sid ?>&lang=<?= $current_lang ?>" class="srv-pill-link <?= ($preselected_service === $sid) ? 'active' : '' ?>">
                <?= htmlspecialchars($sdata['title']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- ፓኬጆቹ የሚታዩበት ግሪድ -->
    <div class="packages-selector-grid">
        <?php foreach ($cur_service_data['tiers'] as $idx => $tier): ?>
            <div class="pkg-select-card <?= !empty($tier['popular']) ? 'popular' : '' ?>">
                <div class="pkg-card-title"><?= htmlspecialchars($tier['name']) ?></div>

                <div class="pkg-price-row">
                    <span class="pkg-card-price"><?= htmlspecialchars($tier['price']) ?></span>
                    <?php if (!empty($tier['popular'])): ?>
                        <span class="pkg-recommended-pill"><?= htmlspecialchars($str['popular_badge']) ?></span>
                    <?php endif; ?>
                </div>

                <ul class="pkg-card-features">
                    <?php foreach ($tier['features'] as $feat): ?>
                        <li><i class="fa fa-check"></i> <?= htmlspecialchars($feat) ?></li>
                    <?php endforeach; ?>
                </ul>

                <a href="service_request.php?service_id=<?= $preselected_service ?>&action=order&tier=<?= $idx ?>" class="btn-select-direct">
                    <?= htmlspecialchars($str['btn_select_pkg']) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>