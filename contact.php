<?php
/**
 * NESERE Film Production - Public Contact Page
 * Saves inquiries to database (messages table) for Admin review
 * Luxury Black & Gold Theme | Bilingual (Amharic & English)
 */
require_once __DIR__ . '/config/config.php';

$db = db();
$error = '';
$success = '';

// ቋንቋ መቀየሪያ
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

// የትኛው ሰንጠረዥ እንዳለ ማረጋገጥ (messages ወይስ contact_messages)
$msg_table = 'messages';
try {
    $db->query("SELECT 1 FROM messages LIMIT 1");
} catch (Exception $e) {
    $msg_table = 'contact_messages';
}

// መልዕክት ሲላክ (Form Submit Handler)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'send_message') {
    check_csrf();

    $name    = trim(post('name'));
    $email   = trim(post('email'));
    $phone   = trim(post('phone'));
    $subject = trim(post('subject'));
    $message = trim(post('message'));

    if (empty($name) || empty($email) || empty($message)) {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን ስምዎን፣ ኢሜይልዎን እና መልዕክትዎን በትክክል ይሙሉ!' 
            : 'Please fill in your name, email, and message!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን ትክክለኛ የኢሜይል አድራሻ ያስገቡ!' 
            : 'Please enter a valid email address!';
    } else {
        try {
            $cols = $db->query("DESCRIBE {$msg_table}")->fetchAll(PDO::FETCH_COLUMN);

            $fields = ['name', 'email', 'message'];
            $values = [$name, $email, $message];
            $placeholders = ['?', '?', '?'];

            if (in_array('phone', $cols, true)) {
                $fields[] = 'phone';
                $values[] = $phone;
                $placeholders[] = '?';
            }
            if (in_array('subject', $cols, true)) {
                $fields[] = 'subject';
                $values[] = $subject;
                $placeholders[] = '?';
            }
            if (in_array('is_read', $cols, true)) {
                $fields[] = 'is_read';
                $values[] = 0;
                $placeholders[] = '?';
            }
            if (in_array('created_at', $cols, true)) {
                $fields[] = 'created_at';
                $values[] = date('Y-m-d H:i:s');
                $placeholders[] = '?';
            }

            $sql = "INSERT INTO {$msg_table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $db->prepare($sql);
            $stmt->execute($values);

            // ለአድሚን ማሳወቂያ መላክ
            if (function_exists('notify_admins')) {
                notify_admins("አዲስ የኮንታክት መልዕክት ከ " . $name . " ደርሷል!");
            }

            $success = ($current_lang === 'am') 
                ? 'መልዕክትዎ በተሳካ ሁኔታ ደርሶናል፤ በቅርቡ እናገኝዎታለን!' 
                : 'Your message has been sent successfully! We will get back to you shortly.';
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// የቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'    => 'ያግኙን | ንስር ፊልም ፕሮዳክሽን',
        'badge'         => 'አድራሻችን',
        'hero_title'    => 'ከእኛ ጋር ይገናኙ',
        'hero_sub'      => 'ስለ ፊልም፣ ቪዲዮ እና ሚዲያ ፕሮዳክሽን ጥያቄ ወይም አስተያየት ካለዎት መልዕክትዎን ይላኩልን።',
        'info_title'    => 'የመገናኛ መረጃዎች',
        'info_desc'     => 'በቀጥታ የስልክ መስመሮቻችን፣ በኢሜይል ወይም በስቱዲዮአችን በአካል በመገኘት ማነጋገር ይችላሉ።',
        'lbl_address'   => 'የስቱዲዮ አድራሻ',
        'val_address'   => 'አዲስ አበባ፣ ኢትዮጵያ',
        'lbl_phone'     => 'የቀጥታ ስልክ መስመሮች',
        'val_phone'     => '+251 970701196 / +251 940695261',
        'lbl_email'     => 'የኢሜይል አድራሻዎች',
        'val_email'     => 'contact@neserefilms.com / info@neserefilms.com',
        'lbl_hours'     => 'የስራ ሰዓት',
        'val_hours'     => 'ከሰኞ - ቅዳሜ፡ ከጠዋቱ 2:30 - ማታ 12:00 (8:30 AM - 6:00 PM)',
        'form_title'    => 'ቀጥተኛ መልዕክት ይላኩ',
        'lbl_name'      => 'ሙሉ ስም *',
        'lbl_email_f'   => 'የኢሜይል አድራሻ *',
        'lbl_phone_f'   => 'ስልክ ቁጥር',
        'lbl_subject_f' => 'ርዕሰ ጉዳይ',
        'lbl_msg'       => 'መልዕክትዎ *',
        'btn_send'      => 'መልዕክቱን ላክ',
        'lang_toggle'   => 'English'
    ],
    'en' => [
        'page_title'    => 'Contact Us | Nesere Film Production',
        'badge'         => 'GET IN TOUCH',
        'hero_title'    => 'Contact Our Studio',
        'hero_sub'      => 'Have inquiries about films, commercials, or event coverage? Send us a message.',
        'info_title'    => 'Studio Information',
        'info_desc'     => 'Feel free to reach out to us via direct phone lines, email, or visit our studio in person.',
        'lbl_address'   => 'Studio Location',
        'val_address'   => 'Addis Ababa, Ethiopia',
        'lbl_phone'     => 'Direct Lines',
        'val_phone'     => '+251 970701196 / +251 940695261',
        'lbl_email'     => 'Email Addresses',
        'val_email'     => 'contact@neserefilms.com / info@neserefilms.com',
        'lbl_hours'     => 'Working Hours',
        'val_hours'     => 'Monday - Saturday: 8:30 AM - 6:00 PM',
        'form_title'    => 'Send Direct Inquiry',
        'lbl_name'      => 'Full Name *',
        'lbl_email_f'   => 'Email Address *',
        'lbl_phone_f'   => 'Phone Number',
        'lbl_subject_f' => 'Subject',
        'lbl_msg'       => 'Message *',
        'btn_send'      => 'Send Message',
        'lang_toggle'   => 'አማርኛ'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

if (file_exists(__DIR__ . '/includes/header.php')) {
    require_once __DIR__ . '/includes/header.php'; 
}
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #0B0B0B;
            --card-bg: #121316;
            --border-clr: #1E2026;
            --gold: #D4AF37;
            --gold-light: #F2D16B;
            --text-muted: #8E929D;
            --text-white: #FFFFFF;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-white);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
        }

        .contact-container {
            max-width: 1140px;
            margin: 50px auto 90px;
            padding: 0 20px;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 45px;
        }

        .contact-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold);
            color: var(--gold-light);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .contact-header h1 {
            font-size: 34px;
            margin: 0 0 10px 0;
            color: #FFFFFF;
        }

        .contact-header p {
            color: var(--text-muted);
            font-size: 14.5px;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-layout {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 35px;
        }

        @media (max-width: 900px) {
            .contact-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Info Card */
        .info-card {
            background: var(--card-bg);
            border: 1px solid var(--border-clr);
            border-radius: 12px;
            padding: 35px 28px;
            height: fit-content;
        }

        .info-card h2 {
            font-size: 20px;
            margin: 0 0 10px 0;
            color: #fff;
        }

        .info-card p {
            color: var(--text-muted);
            font-size: 13.5px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 24px;
        }

        .info-item-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #181920;
            border: 1px solid var(--border-clr);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-item-text strong {
            display: block;
            font-size: 12px;
            color: var(--gold-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .info-item-text span {
            font-size: 13.5px;
            color: #E2E2E8;
            line-height: 1.5;
            display: block;
        }

        .info-item-text a {
            color: #E2E2E8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .info-item-text a:hover {
            color: var(--gold);
        }

        /* Form Card */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border-clr);
            border-radius: 12px;
            padding: 35px 30px;
        }

        .form-card h2 {
            font-size: 20px;
            margin: 0 0 25px 0;
            color: #fff;
            border-bottom: 1px solid var(--border-clr);
            padding-bottom: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        @media (max-width: 580px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 13px;
            color: #D1D1D6;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .field input, .field textarea {
            width: 100%;
            padding: 12px;
            background: #0E0F12;
            border: 1px solid var(--border-clr);
            border-radius: 6px;
            color: #FFFFFF;
            font-size: 14px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }

        .field input:focus, .field textarea:focus {
            border-color: var(--gold);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            border: none;
            border-radius: 6px;
            color: #000;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 5px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .alert-box {
            padding: 13px 16px;
            border-radius: 6px;
            font-size: 13.5px;
            margin-bottom: 22px;
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid #e74c3c;
            color: #ff6b6b;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="contact-container">
    <div class="contact-header">
        <span class="contact-badge"><?= e($str['badge']) ?></span>
        <h1><?= e($str['hero_title']) ?></h1>
        <p><?= e($str['hero_sub']) ?></p>
    </div>

    <div class="contact-layout">
        <!-- Contact Information Side -->
        <div class="info-card">
            <h2><?= e($str['info_title']) ?></h2>
            <p><?= e($str['info_desc']) ?></p>

            <!-- Studio Location -->
            <div class="info-item">
                <div class="info-item-icon"><i class="fa fa-map-marker-alt"></i></div>
                <div class="info-item-text">
                    <strong><?= e($str['lbl_address']) ?></strong>
                    <span><?= e($str['val_address']) ?></span>
                </div>
            </div>

            <!-- Direct Lines -->
            <div class="info-item">
                <div class="info-item-icon"><i class="fa fa-phone-alt"></i></div>
                <div class="info-item-text">
                    <strong><?= e($str['lbl_phone']) ?></strong>
                    <span>
                        <a href="tel:+251970701196">+251 970701196</a> / 
                        <a href="tel:+251940695261">+251 940695261</a>
                    </span>
                </div>
            </div>

            <!-- Email Addresses -->
            <div class="info-item">
                <div class="info-item-icon"><i class="fa fa-envelope"></i></div>
                <div class="info-item-text">
                    <strong><?= e($str['lbl_email']) ?></strong>
                    <span>
                        <a href="mailto:contact@neserefilms.com">contact@neserefilms.com</a><br>
                        <a href="mailto:info@neserefilms.com">info@neserefilms.com</a>
                    </span>
                </div>
            </div>

            <!-- Working Hours -->
            <div class="info-item">
                <div class="info-item-icon"><i class="fa fa-clock"></i></div>
                <div class="info-item-text">
                    <strong><?= e($str['lbl_hours']) ?></strong>
                    <span><?= e($str['val_hours']) ?></span>
                </div>
            </div>
        </div>

        <!-- Contact Form Side -->
        <div class="form-card">
            <h2><?= e($str['form_title']) ?></h2>

            <?php if ($error): ?>
                <div class="alert-box alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-box alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="contact.php">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="send_message">

                <div class="form-row">
                    <div class="field">
                        <label><?= e($str['lbl_name']) ?></label>
                        <input type="text" name="name" placeholder="John Doe" required>
                    </div>

                    <div class="field">
                        <label><?= e($str['lbl_email_f']) ?></label>
                        <input type="email" name="email" placeholder="john@example.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label><?= e($str['lbl_phone_f']) ?></label>
                        <input type="tel" name="phone" placeholder="+251 9...">
                    </div>

                    <div class="field">
                        <label><?= e($str['lbl_subject_f']) ?></label>
                        <input type="text" name="subject" placeholder="Inquiry about video production">
                    </div>
                </div>

                <div class="field">
                    <label><?= e($str['lbl_msg']) ?></label>
                    <textarea name="message" rows="5" placeholder="<?= ($current_lang === 'am') ? 'መልዕክትዎን እዚህ ይጻፉ...' : 'Type your inquiry details here...' ?>" required></textarea>
                </div>

                <button type="submit" class="btn-submit"><?= e($str['btn_send']) ?></button>
            </form>
        </div>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php'; 
}
?>
</body>
</html>