<?php
/**
 * NESERE Film Production - Admin Portfolio Manager
 * Upload Projects, YouTube Video Links, and Cover Photos
 * Luxury Black & Gold Theme | Bilingual (Amharic & English)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth_check.php';

// ቋንቋ መቀየሪያ (Language Switcher)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

$db = db();
$error = '';

// 1. አዲስ ስራ/ፕሮጀክት መለጠፍ (Create Portfolio Item)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'create_project') {
    check_csrf();

    $title       = trim(post('title'));
    $title_am    = trim(post('title_am'));
    $category    = post('category');
    $client_name = trim(post('client_name'));
    $video_url   = trim(post('video_url'));
    $description = trim(post('description'));

    if (empty($title) || empty($category)) {
        $error = ($current_lang === 'am') 
            ? 'እባክዎን የፕሮጀክቱን ርዕስ እና ምድብ ይምረጡ!' 
            : 'Please enter project title and select a category!';
    } else {
        // የካቨር ፎቶ መስቀል
        $cover_image = null;
        if (!empty($_FILES['cover_image']['name'])) {
            [$name, $up_error] = save_upload($_FILES['cover_image'], IMAGE_TYPES, 'portfolio_');
            if ($up_error) {
                $error = $up_error;
            } else {
                $cover_image = $name;
            }
        }

        if (empty($error)) {
            try {
                // ዳታቤዝ ላይ ያሉትን አምዶች መፈተሽ
                $cols = $db->query("DESCRIBE projects")->fetchAll(PDO::FETCH_COLUMN);

                $fields = ['title', 'category', 'client_name', 'video_url', 'description', 'cover_image'];
                $values = [$title, $category, $client_name, $video_url, $description, $cover_image];
                $placeholders = ['?', '?', '?', '?', '?', '?'];

                if (in_array('title_am', $cols, true)) {
                    $fields[] = 'title_am';
                    $values[] = $title_am;
                    $placeholders[] = '?';
                }
                if (in_array('created_at', $cols, true)) {
                    $fields[] = 'created_at';
                    $values[] = date('Y-m-d H:i:s');
                    $placeholders[] = '?';
                }

                $sql = "INSERT INTO projects (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $db->prepare($sql);
                $stmt->execute($values);

                flash('success', ($current_lang === 'am') 
                    ? 'አዲስ ስራ በተሳካ ሁኔታ ወደ ፖርትፎሊዮ ተጨምሯል!' 
                    : 'New work published to portfolio successfully!');
                
                redirect('portfolio.php');
            } catch (Exception $e) {
                $error = "DB Error: " . $e->getMessage();
            }
        }
    }
}

// 2. ፕሮጀክት መሰረዝ (Delete Action)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $token = $_GET['csrf'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        flash('danger', ($current_lang === 'am') ? 'የደህንነት ጊዜ አልፎበታል፤ እባክዎን እንደገና ይሞክሩ።' : 'Session expired, please try again.');
        redirect('portfolio.php');
    }

    $del_id = (int)$_GET['id'];
    try {
        $col_id = in_array('project_id', $db->query("DESCRIBE projects")->fetchAll(PDO::FETCH_COLUMN), true) ? 'project_id' : 'id';
        
        // ፎቶውን ከ uploads/ ፎልደር ማጥፋት
        $st = $db->prepare("SELECT cover_image FROM projects WHERE {$col_id} = ? LIMIT 1");
        $st->execute([$del_id]);
        $row = $st->fetch();
        if ($row && !empty($row['cover_image'])) {
            delete_upload($row['cover_image']);
        }

        // ከዳታቤዝ ላይ ማስወገድ
        $del_stmt = $db->prepare("DELETE FROM projects WHERE {$col_id} = ?");
        $del_stmt->execute([$del_id]);

        flash('success', ($current_lang === 'am') ? 'ፕሮጀክቱ በተሳካ ሁኔታ ተሰርዟል!' : 'Project deleted successfully!');
    } catch (Exception $e) {
        flash('danger', 'Error: ' . $e->getMessage());
    }
    redirect('portfolio.php');
}

// ያሉትን ስራዎች መጥራት
$projects = [];
try {
    $col_id = in_array('project_id', $db->query("DESCRIBE projects")->fetchAll(PDO::FETCH_COLUMN), true) ? 'project_id' : 'id';
    $projects = $db->query("SELECT * FROM projects ORDER BY {$col_id} DESC")->fetchAll();
} catch (Exception $e) {
    $projects = [];
}

// የቋንቋ መዝገበ-ቃላት
$lang_dict = [
    'am' => [
        'page_title'    => 'የስራዎች ማሳያ ማስተዳደሪያ | Nesere Films',
        'back_btn'      => 'ወደ ዳሽቦርድ ተመለስ',
        'main_title'    => 'የስራዎች ማሳያ (Portfolio) ማስተዳደሪያ',
        'form_heading'  => '+ አዲስ ስራ/ፕሮጀክት መለጠፊያ',
        'lbl_title_en'  => 'የፕሮጀክት ርዕስ (English) *',
        'lbl_title_am'  => 'የፕሮጀክት ርዕስ (በአማርኛ)',
        'lbl_category'  => 'የስራው ምድብ (Category) *',
        'lbl_client'    => 'የደንበኛ/የድርጅት ስም',
        'lbl_video'     => 'የቪዲዮ ሊንክ (YouTube URL)',
        'lbl_cover'     => 'የካቨር ፎቶ (Cover Image)',
        'lbl_desc'      => 'የስራው አጭር ማብራሪያ',
        'btn_submit'    => 'ስራውን ለጥፍ (Publish)',
        'table_heading' => 'እስካሁን የተለጠፉ ስራዎች',
        'col_item'      => 'ስራ / ፎቶ',
        'col_category'  => 'ምድብ',
        'col_client'    => 'ደንበኛ / ቪዲዮ',
        'col_action'    => 'ድርጊት',
        'btn_delete'    => 'ሰርዝ',
        'confirm_del'   => 'ይህንን ፕሮጀክት መሰረዝ ይፈልጋሉ?',
        'no_projects'   => 'እስካሁን ምንም የተለጠፈ ስራ የለም።',
        'lang_toggle'   => 'English'
    ],
    'en' => [
        'page_title'    => 'Portfolio Manager | Nesere Films',
        'back_btn'      => 'Back to Dashboard',
        'main_title'    => 'Portfolio & Showcases Manager',
        'form_heading'  => '+ Post New Project',
        'lbl_title_en'  => 'Project Title (English) *',
        'lbl_title_am'  => 'Project Title (Amharic)',
        'lbl_category'  => 'Category *',
        'lbl_client'    => 'Client / Brand Name',
        'lbl_video'     => 'YouTube Video URL',
        'lbl_cover'     => 'Cover Image',
        'lbl_desc'      => 'Project Description',
        'btn_submit'    => 'Publish to Portfolio',
        'table_heading' => 'Published Works',
        'col_item'      => 'Project / Media',
        'col_category'  => 'Category',
        'col_client'    => 'Client / Video',
        'col_action'    => 'Action',
        'btn_delete'    => 'Delete',
        'confirm_del'   => 'Are you sure you want to delete this project?',
        'no_projects'   => 'No portfolio items found.',
        'lang_toggle'   => 'አማርኛ'
    ]
];

$str = $lang_dict[$current_lang];
$page_title = $str['page_title'];

require_once __DIR__ . '/../includes/header.php';
?>

<style>
:root {
    --adm-card: #121316;
    --adm-border: #1E2026;
    --adm-gold: #D4AF37;
    --adm-gold-light: #F2D16B;
    --adm-muted: #8E929D;
    --adm-white: #FFFFFF;
}

.adm-container {
    max-width: 1260px;
    margin: 40px auto 90px;
    padding: 0 20px;
}

.adm-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--adm-border);
    padding-bottom: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.adm-top-bar h1 {
    font-size: 24px;
    color: var(--adm-white);
    margin: 6px 0 0 0;
}

.adm-back-link {
    color: var(--adm-muted);
    text-decoration: none;
    font-size: 13.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.adm-back-link:hover {
    color: var(--adm-gold);
}

.btn-lang-toggle {
    padding: 8px 14px;
    border-radius: 6px;
    background: #17181D;
    border: 1px solid var(--adm-border);
    color: var(--adm-gold);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 30px;
}

@media (max-width: 990px) {
    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}

.form-box-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    padding: 30px 25px;
    height: fit-content;
}

.form-box-card h2 {
    font-size: 18px;
    color: var(--adm-white);
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--adm-border);
}

.adm-field {
    margin-bottom: 18px;
}

.adm-field label {
    display: block;
    font-size: 13px;
    color: #D1D1D6;
    margin-bottom: 7px;
    font-weight: 600;
}

.adm-field input, .adm-field select, .adm-field textarea {
    width: 100%;
    padding: 12px;
    background: #0E0F12;
    border: 1px solid var(--adm-border);
    border-radius: 6px;
    color: #FFFFFF;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
}

.adm-field input:focus, .adm-field select:focus, .adm-field textarea:focus {
    border-color: var(--adm-gold);
}

.btn-submit-gold {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, var(--adm-gold-light), var(--adm-gold));
    border: none;
    border-radius: 6px;
    color: #000;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 5px;
}

.table-box-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    overflow-x: auto;
}

.portfolio-table {
    width: 100%;
    border-collapse: collapse;
}

.portfolio-table th {
    background: #16171D;
    padding: 14px 18px;
    font-size: 12.5px;
    color: var(--adm-gold);
    text-transform: uppercase;
    text-align: left;
    border-bottom: 1px solid var(--adm-border);
}

.portfolio-table td {
    padding: 16px 18px;
    font-size: 13.5px;
    color: #D1D1D6;
    border-bottom: 1px solid var(--adm-border);
    vertical-align: middle;
}

.project-thumb {
    width: 70px;
    height: 48px;
    border-radius: 6px;
    object-fit: cover;
    background: #222;
    border: 1px solid var(--adm-border);
}

.category-tag {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid var(--adm-gold);
    color: var(--adm-gold-light);
    font-size: 11px;
    font-weight: 600;
}

.btn-del-item {
    color: #ff6b6b;
    text-decoration: none;
    font-size: 12.5px;
    padding: 6px 12px;
    border-radius: 4px;
    background: rgba(231, 76, 60, 0.1);
    border: 1px solid rgba(231, 76, 60, 0.25);
    display: inline-block;
}

.btn-del-item:hover {
    background: #e74c3c;
    color: #fff;
}
</style>

<div class="adm-container">
    <div class="adm-top-bar">
        <div>
            <a href="index.php" class="adm-back-link"><i class="fa fa-arrow-left"></i> <?= e($str['back_btn']) ?></a>
            <h1><?= e($str['main_title']) ?></h1>
        </div>

        <div>
            <a href="portfolio.php?lang=<?= ($current_lang === 'am') ? 'en' : 'am' ?>" class="btn-lang-toggle">
                <i class="fa fa-globe"></i> <?= e($str['lang_toggle']) ?>
            </a>
        </div>
    </div>

    <?php show_flash(); ?>

    <?php if ($error): ?>
        <div style="background: rgba(231,76,60,0.15); border: 1px solid #e74c3c; color: #ff6b6b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13.5px;">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <div class="portfolio-grid">
        <!-- አዲስ ፕሮጀክት መለጠፊያ ፎርም -->
        <div class="form-box-card">
            <h2><?= e($str['form_heading']) ?></h2>

            <form method="POST" action="portfolio.php" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create_project">

                <div class="adm-field">
                    <label><?= e($str['lbl_title_en']) ?></label>
                    <input type="text" name="title" placeholder="e.g. Documentary Film" required>
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_title_am']) ?></label>
                    <input type="text" name="title_am" placeholder="ምሳሌ፡ አጭር የዶክመንተሪ ፊልም">
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_category']) ?></label>
                    <select name="category" required>
                        <?php foreach (PORTFOLIO_CATEGORIES as $cat): ?>
                            <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_client']) ?></label>
                    <input type="text" name="client_name" placeholder="e.g. Ethiopian Airlines">
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_video']) ?></label>
                    <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_cover']) ?></label>
                    <input type="file" name="cover_image" accept="image/*">
                </div>

                <div class="adm-field">
                    <label><?= e($str['lbl_desc']) ?></label>
                    <textarea name="description" rows="3" placeholder="ስለ ስራው አጭር ማብራሪያ..."></textarea>
                </div>

                <button type="submit" class="btn-submit-gold"><?= e($str['btn_submit']) ?></button>
            </form>
        </div>

        <!-- የተለጠፉ ስራዎች ሰንጠረዥ -->
        <div class="table-box-card">
            <table class="portfolio-table">
                <thead>
                    <tr>
                        <th><?= e($str['col_item']) ?></th>
                        <th><?= e($str['col_category']) ?></th>
                        <th><?= e($str['col_client']) ?></th>
                        <th style="width: 80px;"><?= e($str['col_action']) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php foreach ($projects as $p): ?>
                            <?php 
                                $p_id = $p['project_id'] ?? ($p['id'] ?? 0);
                                $img_src = !empty($p['cover_image']) ? '../uploads/' . $p['cover_image'] : '../assets/images/default.jpg';
                                $disp_title = ($current_lang === 'am' && !empty($p['title_am'])) ? $p['title_am'] : $p['title'];
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="<?= e($img_src) ?>" class="project-thumb" alt="Thumb" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2270%22 height=%2248%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23222%22/></svg>'">
                                        <div>
                                            <strong style="color: #fff; font-size: 14px;"><?= e($disp_title) ?></strong>
                                            <?php if (!empty($p['description'])): ?>
                                                <div style="font-size: 12px; color: var(--adm-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    <?= e($p['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-tag"><?= e($p['category']) ?></span>
                                </td>
                                <td>
                                    <div style="color: #fff; font-size: 13px;"><?= e($p['client_name'] ?: '—') ?></div>
                                    <?php if (!empty($p['video_url'])): ?>
                                        <a href="<?= e($p['video_url']) ?>" target="_blank" style="color: var(--adm-gold); font-size: 11.5px; text-decoration: none;">
                                            <i class="fa fa-play-circle"></i> Watch Video
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="portfolio.php?action=delete&id=<?= (int)$p_id ?>&csrf=<?= csrf() ?>" 
                                       class="btn-del-item" 
                                       onclick="return confirm('<?= e($str['confirm_del']) ?>');">
                                        <i class="fa fa-trash"></i> <?= e($str['btn_delete']) ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--adm-muted);">
                                <?= e($str['no_projects']) ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/../includes/footer.php'; 
?>