<?php
// Nesere Film Production - Service Packages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/config.php';

// config.php exposes db() rather than a global $pdo variable.
try {
    $pdo = db();
} catch (Throwable $e) {
    error_log('Service packages database connection error: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Please check config/config.php and your database settings.');
}

if (!function_exists('h')) {
    function h($v): string {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}

$lang = $_GET['lang'] ?? $_POST['lang'] ?? ($_SESSION['lang'] ?? 'en');
$lang = in_array($lang, ['am','en'], true) ? $lang : 'en';
$_SESSION['lang'] = $lang;

$ui = [
'en'=>['heading'=>'Choose Your Service Package','intro'=>'Explore our production and digital services. Choose a service to compare its three packages.','services'=>'OUR SERVICES','packages'=>'AVAILABLE PACKAGES','recommended'=>'RECOMMENDED','select'=>'Select Package','month'=>'/ month','episode'=>'/ episode','project'=>'one-time project','request'=>'Request this package','project_title'=>'Project title','description'=>'Project details','location'=>'Location','date'=>'Preferred start date','continue'=>'Continue to Payment','back'=>'Back to packages','note'=>'Note','adnote'=>'Package fees cover agency management services. Advertising spend paid to platforms may be charged separately.','error'=>'Could not save the request. Check your service_requests table and column names.','required'=>'Project title is required.'],
'am'=>['heading'=>'የአገልግሎት ፓኬጅ ይምረጡ','intro'=>'የፊልም እና የዲጂታል አገልግሎቶቻችንን ይመልከቱ። አገልግሎት በመምረጥ ሶስቱን ፓኬጆች ያወዳድሩ።','services'=>'አገልግሎቶቻችን','packages'=>'የሚገኙ ፓኬጆች','recommended'=>'የምንመክረው','select'=>'ፓኬጁን ይምረጡ','month'=>'/ በወር','episode'=>'/ በአንድ ክፍል','project'=>'ለአንድ ፕሮጀክት','request'=>'ይህን ፓኬጅ ይጠይቁ','project_title'=>'የፕሮጀክት ርዕስ','description'=>'የፕሮጀክቱ ዝርዝር','location'=>'የስራ ቦታ','date'=>'መጀመር የሚፈልጉበት ቀን','continue'=>'ወደ ክፍያ ይቀጥሉ','back'=>'ወደ ፓኬጆች ተመለስ','note'=>'ማስታወሻ','adnote'=>'ዋጋው የኤጀንሲውን የማስተዳደር አገልግሎት ያካትታል። ለፕላትፎርሞች የሚከፈል ተጨማሪ የማስታወቂያ በጀት በተለይ ሊከፈል ይችላል።','error'=>'ጥያቄውን ማስቀመጥ አልተቻለም። service_requests ሰንጠረዥን ያረጋግጡ።','required'=>'የፕሮጀክት ርዕስ ያስፈልጋል።']
];
$t=$ui[$lang];

$services = [
1=>['en'=>'Film & Serial Drama','am'=>'ፊልም እና ተከታታይ ድራማ','p'=>[
['Starter','Standard Drama Package',24000,'24,000','episode',['Final Draft script preparation','Basic shooting schedule','Sony Alpha 7 IV camera','Standard lighting','Zoom H6 + DJI Mic 2 audio','Premiere Pro editing and color correction']],
['Standard','Cinematic Drama Pro',25000,'25,000','episode',['Set plan and shot lists','Blackmagic Pocket Cinema Camera 6K Pro','Full lighting setup','Zoom H8 multi-track audio','Professional editing','DaVinci Resolve grading and sound design']],
['Premium','Master VIP Serial Package',35000,'35,000','episode',['Full pre-production support','Location selection and casting assistance','Sony FX3 cinematic capture','Professional lighting and audio','After Effects VFX','Master color grading and audio mastering']]]],
2=>['en'=>'TV Commercials','am'=>'የቴሌቪዥን ማስታወቂያ','p'=>[
['Starter','Basic TVC',50000,'50,000+','project',['TVC up to 60 seconds','One shooting day at one location','4K camera and standard lighting','Professional voice-over and background music','Editing and text animations']],
['Standard','Corporate Standard',120000,'120,000+','project',['Up to two shooting days','Two locations','Sony FX3 or Blackmagic camera and drone shots','Casting assistance','Motion graphics and 2D animation','Color grading and sound design']],
['Premium','Cinematic Premium',350000,'350,000+','project',['Up to three shooting days','Multiple locations or studio','Full production crew','Original jingle and music production','Advanced VFX and 3D animation','Full talent management']]]],
3=>['en'=>'YouTube & Podcast','am'=>'ዩቲዩብ እና ፖድካስት','p'=>[
['Starter','Single Episode',18000,'18,000','episode',['Up to one hour recording','Two Sony Alpha camera angles','Studio microphone recording and mixing','Standard video editing','Basic color correction']],
['Standard','Monthly 4 Episodes',60000,'60,000','month',['Four episodes per month','Up to three cameras','Eight short clips for Shorts, TikTok or Reels','Multi-track audio recording','Professional editing and cinematic grading','YouTube thumbnail design']],
['Premium','Full Show VIP',130000,'130,000','month',['Four episodes per month','Custom set decoration and styling','Sony FX3 and Zoom H8 audio','Professional editing and grading','Thumbnail design','Unlimited short videos and Reels']]]],
4=>['en'=>'Social Media Management','am'=>'የሶሻል ሚዲያ ማኔጅመንት','p'=>[
['Starter','Starter Package',21400,'21,400','month',['Logo design','12 videos per month','TikTok management','Monthly report','Build a community on Telegram']],
['Standard','Standard Package',30600,'30,600','month',['Logo design','16 videos per month','Six graphics per month','Post boosting','Facebook and TikTok management','Monthly report and Telegram community']],
['Premium','Premium Package',41300,'41,300','month',['Logo design','20 videos per month','Eight graphics per month','Post boosting','Facebook, Instagram and TikTok management','Monthly report and Telegram community']]]],
5=>['en'=>'Wedding & Event Videography','am'=>'ሰርግ እና የዝግጅት ቀረጻ','p'=>[
['Starter','Classic Moments',45000,'45,000','project',['Full ceremony coverage','Two videographers','Professional photography','3–5 minute highlight film','Full event video and TikTok clip']],
['Standard','Royal Cinema',75000,'75,000','project',['4K multi-camera coverage','Drone footage where permitted','Teaser within 48 hours','DaVinci color grading','Cinematic event film']],
['Premium','Imperial Luxury',180000,'180,000','project',['Pre-wedding love story film','Director and four-person crew','Same Day Edit','Premium photography album','High-end cinematic production']]]],
6=>['en'=>'Post Production & Editing','am'=>'ድህረ-ቀረጻ እና ኤዲቲንግ','p'=>[
['Starter','Essential Edit',15000,'15,000+','project',['Standard cutting and assembling in Premiere Pro','Basic color correction','Simple text and title animations','Standard audio mixing']],
['Standard','Pro Cinematic Edit',35000,'35,000+','project',['Advanced editing and transitions','Cinematic grading in DaVinci Resolve','Sound design and background music','Motion graphics']],
['Premium','Masterpiece VFX & Mastering',80000,'80,000+','project',['Advanced visual effects in After Effects','Master color grading','Studio-checked audio mastering','3D logo animation','Trailer preparation']]]],
8=>['en'=>'Web Development','am'=>'ዌብሳይት እና ዌብ አፕሊኬሽን ልማት','p'=>[
['Starter','Portfolio Landing',25000,'25,000','project',['One year domain and hosting','Responsive mobile-friendly design','Landing page with up to five sections','Contact form and Google Maps','Fast loading and basic SSL security']],
['Standard','Corporate Portal',55000,'55,000','project',['One year domain and SSD hosting','Custom corporate UI/UX','Admin panel or CMS','Amharic and English support','SEO and social media integration','Corporate email accounts']],
['Premium','Custom Web App',120000,'120,000','project',['One year premium domain and cloud hosting','User and customer accounts/dashboard','Telebirr or Chapa payment integration','Automated workflows and notifications','Advanced security and daily backups','One year technical support and maintenance']]]],
9=>['en'=>'Advertisement & Boosting','am'=>'ማስታወቂያ እና ቦስቲንግ','p'=>[
['Starter','Campaign Kickstart',15000,'15,000','month',['One platform: Facebook or Instagram','Target audience research','Two ad copy variations','A/B testing','Monthly monitoring and report']],
['Standard','Growth Multiplier',35000,'35,000','month',['Campaigns on two platforms','Competitor analysis','Retargeting and remarketing','Creative optimization','Four ad copies','Daily budget management and analytics']],
['Premium','Enterprise Scale',80000,'80,000','month',['Meta, TikTok, Telegram, YouTube, LinkedIn and Google Ads','Sales funnel optimization','Influencer outreach and management','Dedicated media manager','VIP support and weekly strategy meetings']]]],
7=>['en'=>'Branding & Graphic Design','am'=>'ብራንዲንግ እና ግራፊክ ዲዛይን','p'=>[
['Starter','Brand Starter Kit',20000,'20,000','project',['Logo design','Business card','Letterhead','Two social media posts','One promotional poster']],
['Standard','Complete Brand Identity',40000,'30,000–50,000','project',['Two logo concepts','Complete brand identity','Three posters','Five social graphics','Three YouTube thumbnails','Typography and intro/outro motion graphic']],
['Premium','Premium Brand Suite',95000,'95,000','project',['Complete brand identity system','Five promotional posters','Ten social media designs','Five YouTube thumbnails','Motion intro and outro','Promotional banner','Priority delivery']]]]
];

$active = isset($_GET['service']) && isset($services[(int)$_GET['service']]) ? (int)$_GET['service'] : 4;
$error=''; $chosenIndex=null; $showForm=false;
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='request_package') {
    $active=(int)($_POST['service_id']??0);
    $chosenIndex=(int)($_POST['package_index']??-1);
    if (!isset($services[$active]) || !isset($services[$active]['p'][$chosenIndex])) {
        $error=$lang==='am'?'እባክዎ ፓኬጅ ይምረጡ።':'Please select a valid package.';
    } else {
        $showForm=true; $chosen=$services[$active]['p'][$chosenIndex];
        $customerId=(int)($_SESSION['user']['customer_id']??$_SESSION['user']['user_id']??$_SESSION['user']['id']??$_SESSION['user_id']??0);
        if (isset($_POST['confirm_request'])) {
            if ($customerId<=0) {
                header('Location: register.php?'.http_build_query(['service_id'=>$active,'budget'=>$chosen[2],'title'=>$chosen[1],'lang'=>$lang])); exit;
            }
            $title=trim($_POST['project_title']??$chosen[1]);
            if ($title==='') $error=$t['required'];
            else {
                try {
                    $stmt=$pdo->prepare("INSERT INTO service_requests (customer_id, service_id, project_title, description, location, preferred_date, budget, payment_status) VALUES (:cid,:sid,:title,:description,:location,:date,:budget,'Unpaid')");
                    $stmt->execute([':cid'=>$customerId,':sid'=>$active,':title'=>$title,':description'=>trim($_POST['description']??''),':location'=>trim($_POST['location']??''),':date'=>($_POST['preferred_date']??'')!==''?$_POST['preferred_date']:null,':budget'=>$chosen[2]]);
                    header('Location: customer/pay.php?id='.(int)$pdo->lastInsertId()); exit;
                } catch (Throwable $ex) { $error=$t['error']; }
            }
        }
    }
}
?>
<!doctype html>
<html lang="<?=h($lang)?>">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=h($lang==='am'?'የአገልግሎት ፓኬጆች | Nesere Film Production':'Service Packages | Nesere Film Production')?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Ethiopic:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome Social Media Icons -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  referrerpolicy="no-referrer"
/>

<style>
:root {
  --bg: #090909; --card: #151515; --gold: #d4af37; --gold-light: #f3dc87;
  --text: #f7f5ef; --muted: #b9b5aa; --line: rgba(212,175,55,.30);
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
  margin: 0; min-height: 100vh; color: var(--text); background-color: var(--bg);
  background-image: radial-gradient(ellipse at top, rgba(212,175,55,.14), transparent 48%);
  font-family: Inter, "Noto Sans Ethiopic", Arial, sans-serif; line-height: 1.6;
}
a { color: inherit; text-decoration: none; }
button, input, textarea { font: inherit; }
.container { width: min(1180px, 92%); margin-left: auto; margin-right: auto; }
.top { position: sticky; top: 0; z-index: 20; background: rgba(9,9,9,.96); border-bottom: 1px solid var(--line); }
.nav { min-height: 74px; display: flex; align-items: center; justify-content: space-between; gap: 18px; }
.brand { display: flex; align-items: center; gap: 12px; font-weight: 800; letter-spacing: .7px; }
.logo-img { width: 54px; height: 54px; object-fit: contain; display: block; flex-shrink: 0; filter: drop-shadow(0 0 8px rgba(212,175,55,.18)); }
.brand small { display: block; font-size: 10px; letter-spacing: 2px; color: var(--muted); }
.lang { display: flex; align-items: center; gap: 7px; }
.lang a { border: 1px solid var(--line); padding: 7px 12px; border-radius: 22px; font-size: 13px; }
.lang a.active { background: var(--gold); color: #080808; font-weight: 800; }
.hero { text-align: center; padding: 65px 0 35px; }
.eyebrow, .label { font-size: 11px; letter-spacing: 2.5px; color: var(--gold); font-weight: 800; }
.hero h1 { font-size: clamp(32px, 5vw, 54px); line-height: 1.15; margin: 14px auto; max-width: 850px; }
.hero p { color: var(--muted); max-width: 720px; margin: auto; }
.label { margin: 18px 0 14px; }
.services { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 17px; }
.service { min-height: 160px; position: relative; display: flex; align-items: flex-end; padding: 20px; border: 1px solid var(--line); border-radius: 17px; overflow: hidden; background: radial-gradient(circle at top right, rgba(212,175,55,.20), transparent 60%), linear-gradient(145deg, #222, #0b0b0b); transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease; }
.service:hover { transform: translateY(-5px); border-color: var(--gold); box-shadow: 0 8px 25px rgba(212,175,55,.12); }
.service span:not(.num) { color: var(--text); font-weight: 800; line-height: 1.4; }
.service .num { position: absolute; top: 12px; left: 12px; width: 30px; height: 30px; display: grid; place-items: center; background: #090909; border: 1px solid var(--line); color: var(--gold); border-radius: 50%; font-size: 12px; }
.service.active { border-color: var(--gold); background: linear-gradient(145deg, rgba(212,175,55,.18), #101010); box-shadow: 0 0 18px rgba(212,175,55,.15); }
.service.active span:not(.num) { color: var(--gold-light); }
.packages { padding: 45px 0 65px; }
.section-title { font-size: clamp(25px, 3vw, 35px); margin: 0; }
.sub { color: var(--muted); font-size: 14px; }
.grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; margin-top: 24px; align-items: stretch; }
.pkg { position: relative; min-width: 0; border: 1px solid var(--line); border-radius: 18px; padding: 25px; background: linear-gradient(150deg, #1b1b1b, #101010); display: flex; flex-direction: column; }
.pkg.recommended { border-color: var(--gold); box-shadow: 0 0 28px rgba(212,175,55,.10); }
.badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); white-space: nowrap; background: var(--gold); color: #080808; border-radius: 20px; padding: 4px 13px; font-size: 10px; font-weight: 900; }
.tier { font-size: 12px; letter-spacing: 2px; color: var(--gold); font-weight: 900; text-transform: uppercase; }
.pkg h3 { font-size: 21px; line-height: 1.3; margin: 9px 0; }
.price { font-size: 30px; font-weight: 800; overflow-wrap: anywhere; }
.price small { font-size: 11px; color: var(--muted); letter-spacing: 0; }
.unit { font-size: 12px; color: var(--muted); margin-bottom: 16px; }
.features { list-style: none; padding: 0; margin: 0 0 23px; display: grid; gap: 9px; flex: 1; }
.features li { font-size: 13px; color: #e6e2d8; display: flex; gap: 9px; }
.features li::before { content: "✓"; color: var(--gold); font-weight: 900; flex-shrink: 0; }
.btn { border: 0; background: var(--gold); color: #080808; font-weight: 900; border-radius: 9px; padding: 13px 15px; width: 100%; cursor: pointer; text-align: center; display: block; }
.btn:hover { background: var(--gold-light); }
.btn.outline { background: transparent; border: 1px solid var(--gold); color: var(--gold); }
.note, .formbox { margin: 22px 0; padding: 20px; border: 1px solid var(--line); border-radius: 14px; background: rgba(212,175,55,.04); color: var(--muted); font-size: 13px; }
.note strong { color: var(--gold); }
.formbox { max-width: 760px; margin: 0 auto 60px; background: var(--card); padding: 27px; }
.formbox h2 { margin: 0 0 6px; color: var(--text); }
.formgrid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
.field { display: grid; gap: 7px; min-width: 0; }
.field.full { grid-column: 1 / -1; }
.field label { font-size: 12px; color: var(--gold); font-weight: 700; }
.field input, .field textarea { width: 100%; background: #090909; border: 1px solid #454034; color: #fff; padding: 12px; border-radius: 8px; }
.field textarea { min-height: 90px; resize: vertical; }
.actions { display: flex; gap: 12px; margin-top: 17px; }
.actions > * { flex: 1; }
.error { margin: 18px 0; padding: 12px; border: 1px solid #a44; background: #321616; border-radius: 8px; color: #ffdada; }
.site-footer { background:#09090b; border-top:1px solid var(--line); padding:58px 4% 22px; margin-top:30px; }
.footer-container { max-width:1240px; margin:0 auto 38px; display:grid; grid-template-columns:1.35fr 1.1fr .9fr 1.1fr; gap:32px; }
.footer-col h4 { color:var(--gold); font-size:15px; margin:0 0 18px; letter-spacing:.3px; }
.footer-col p { color:var(--muted); font-size:13px; line-height:1.8; margin:0 0 14px; }
.footer-services-list,.footer-nav-links { list-style:none; margin:0; padding:0; }
.footer-services-list li,.footer-nav-links li { margin:0 0 10px; }
.footer-services-list a,.footer-nav-links a { color:#b9b5aa; font-size:13px; transition:color .2s,padding-left .2s; display:inline-block; }
.footer-services-list a:hover,.footer-nav-links a:hover,.footer-contact-item a:hover,.footer-bottom a:hover { color:var(--gold-light); padding-left:3px; }
.footer-contact-item { display:flex; gap:10px; margin-bottom:13px; color:var(--muted); font-size:13px; overflow-wrap:anywhere; }
.footer-contact-item .contact-icon { color:var(--gold); min-width:18px; }
.footer-contact-item a { color:#f7f5ef; }
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
.footer-bottom { border-top:1px solid rgba(255,255,255,.08); padding-top:20px; max-width:1240px; margin:0 auto; display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; color:#8e929d; font-size:12px; }
.footer-bottom a { color:#b9b5aa; margin-left:14px; }
@media(max-width:900px){.footer-container{grid-template-columns:repeat(2,minmax(0,1fr));}}
@media(max-width:560px){.footer-container{grid-template-columns:1fr;gap:26px}.site-footer{padding-top:42px}.footer-bottom{flex-direction:column}.footer-bottom a{margin:0 12px 0 0}}
@media (max-width: 850px) { .grid { grid-template-columns: 1fr; gap: 20px; } .services { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 560px) { .hero { padding: 43px 0 25px; } .services { gap: 9px; } .service { min-height: 140px; border-radius: 12px; padding: 14px; } .service span:not(.num) { font-size: 13px; } .formgrid { grid-template-columns: 1fr; } .field.full { grid-column: auto; } .actions { flex-direction: column; } .brand { font-size: 13px; } .logo-img { width: 44px; height: 44px; } .nav { min-height: 66px; } }
</style>
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<main>
<section class="container hero"><h1><?=h($t['heading'])?></h1><p><?=h($t['intro'])?></p></section>
<section class="container"><div class="label"><?=h($t['services'])?></div><div class="services">
<?php foreach($services as $id=>$s): ?>
<a class="service <?=$active===$id?'active':''?>" href="?lang=<?=h($lang)?>&amp;service=<?=$id?>">
<span class="num"><?=$id?></span>
<span><?=h($s[$lang])?></span></a>
<?php endforeach; ?>
</div></section>
<section class="container packages" id="packages">
<div class="label"><?=h($t['packages'])?></div><h2 class="section-title"><?=h($services[$active][$lang])?></h2><p class="sub"><?=$lang==='am'?'ለፍላጎትዎ የሚስማማውን ፓኬጅ ይምረጡ።':'Choose the package that best fits your project.'?></p>
<?php if($error!==''):?><div class="error"><?=h($error)?></div><?php endif;?>
<div class="grid">
<?php foreach($services[$active]['p'] as $i=>$p): $unit=$p[4]==='month'?$t['month']:($p[4]==='episode'?$t['episode']:$t['project']);?>
<article class="pkg <?=$i===1?'recommended':''?>">
<?php if($i===1):?><div class="badge"><?=h($t['recommended'])?></div><?php endif;?>
<div class="tier"><?=h($p[0])?></div><h3><?=h($p[1])?></h3><div class="price"><?=h($p[3])?> <small>ETB</small></div><div class="unit"><?=h($unit)?></div>
<ul class="features"><?php foreach($p[5] as $f):?><li><?=h($f)?></li><?php endforeach;?></ul>
<form method="post" action="?lang=<?=h($lang)?>#request-form"><input type="hidden" name="lang" value="<?=h($lang)?>"><input type="hidden" name="action" value="request_package"><input type="hidden" name="service_id" value="<?=$active?>"><input type="hidden" name="package_index" value="<?=$i?>"><button class="btn" type="submit"><?=h($t['select'])?></button></form>
</article>
<?php endforeach;?>
</div>
<?php if($active===8):?><div class="note"><strong><?=h($t['note'])?>:</strong> <?=h($t['adnote'])?></div><?php endif;?>
</section>
<?php if($showForm && isset($chosen)):?>
<section class="container" id="request-form"><div class="formbox">
<h2><?=h($t['request'])?>: <?=h($chosen[1])?></h2><p class="sub"><?=h($chosen[3])?> ETB</p>
<form method="post" action="?lang=<?=h($lang)?>#request-form">
<input type="hidden" name="lang" value="<?=h($lang)?>"><input type="hidden" name="action" value="request_package"><input type="hidden" name="confirm_request" value="1"><input type="hidden" name="service_id" value="<?=$active?>"><input type="hidden" name="package_index" value="<?=$chosenIndex?>">
<div class="formgrid">
<div class="field full"><label for="project_title"><?=h($t['project_title'])?></label><input id="project_title" name="project_title" required value="<?=h($_POST['project_title']??$chosen[1])?>"></div>
<div class="field full"><label for="description"><?=h($t['description'])?></label><textarea id="description" name="description"><?=h($_POST['description']??'')?></textarea></div>
<div class="field"><label for="location"><?=h($t['location'])?></label><input id="location" name="location" value="<?=h($_POST['location']??'')?>"></div>
<div class="field"><label for="preferred_date"><?=h($t['date'])?></label><input id="preferred_date" type="date" name="preferred_date" value="<?=h($_POST['preferred_date']??'')?>"></div>
</div><div class="actions"><a class="btn outline" href="?lang=<?=h($lang)?>&amp;service=<?=$active?>#packages"><?=h($t['back'])?></a><button class="btn" type="submit"><?=h($t['continue'])?></button></div>
</form></div></section>
<?php endif;?>
</main>
<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-col">
      <h4>NESERE FILM PRODUCTION</h4>
      <p><?=h($lang==='am'?'በንስር ፊልም ፕሮዳክሽን እያንዳንዱ ታሪክ በስሜት፣ በፈጠራ እና በዓላማ እንዲተረክ እናምናለን። ራዕይዎን ወደ ሲኒማቲክ ጥበብ እንቀይራለን።':'At Nesere Film Production, we tell stories with emotion, creativity, and purpose, transforming your vision into cinematic art.')?></p>
      <div class="footer-socials">
        <a class="footer-social-btn" href="https://t.me/@neserefilmproduction" target="_blank" rel="noopener noreferrer" aria-label="Telegram" title="Telegram"><i class="fa-brands fa-telegram" aria-hidden="true"></i></a>
        <a class="footer-social-btn" href="https://instagram.com/@neserefilmproduction" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
        <a class="footer-social-btn" href="https://x.com/@neserefilmproduction" target="_blank" rel="noopener noreferrer" aria-label="X" title="X"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
        <a class="footer-social-btn" href="https://tiktok.com/@neserefilmproduction" target="_blank" rel="noopener noreferrer" aria-label="TikTok" title="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
        <a class="footer-social-btn" href="https://facebook.com/@NesereFilmProduction" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h4><?=h($lang==='am'?'አገልግሎቶች':'Our Services')?></h4>
      <ul class="footer-services-list">
        <?php foreach ($services as $sid => $serviceItem): ?>
    <li>
        <a href="./service_request.php?lang=<?= h($lang) ?>&amp;service=<?= (int)$sid ?>#packages">
            <?= h($serviceItem[$lang]) ?>
        </a>
    </li>
<?php endforeach; ?>
      </ul>
    </div>
    <div class="footer-col">
      <h4><?=h($lang==='am'?'ፈጣን ሊንኮች':'Quick Links')?></h4>
      <ul class="footer-nav-links">
        <li><a href="index.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'ዋና ገጽ':'Home')?></a></li>
        <li><a href="about.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'ስለ እኛ':'About Us')?></a></li>
        <li><a href="services.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'አገልግሎቶች':'Services')?></a></li>
        <li><a href="portfolio.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'ስራዎቻችን':'Portfolio')?></a></li>
        <li><a href="contact.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'ያግኙን':'Contact Us')?></a></li>
        <li><a href="login.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'መግቢያ':'Login')?></a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4><?=h($lang==='am'?'ያግኙን':'Contact Us')?></h4>
      <div class="footer-contact-item"><span class="contact-icon">⌖</span><span><?=h($lang==='am'?'አዲስ አበባ፣ ኢትዮጵያ':'Addis Ababa, Ethiopia')?></span></div>
      <div class="footer-contact-item"><span class="contact-icon">☎</span><span><a href="tel:+251970701196">+251 970701196</a><br><a href="tel:+251940695261">+251 940695261</a></span></div>
      <div class="footer-contact-item"><span class="contact-icon">✉</span><a href="mailto:contact@neserefilms.com">contact@neserefilms.com</a></div>
      <div class="footer-contact-item"><span class="contact-icon">◷</span><span><?=h($lang==='am'?'ሰኞ - ቅዳሜ፡ 2:30 ጠዋት - 12:00 ከሰዓት':'Mon - Sat: 8:30 AM - 6:00 PM')?></span></div>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© <?=date('Y')?> NESERE Film Production. <?=h($lang==='am'?'መብቱ በህግ የተጠበቀ ነው።':'All Rights Reserved.')?></span>
    <span><a href="about.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'ስለ እኛ':'About Us')?></a><a href="contact.php?lang=<?=h($lang)?>"><?=h($lang==='am'?'ያግኙን':'Contact Us')?></a></span>
  </div>
</footer>
</body></html>
