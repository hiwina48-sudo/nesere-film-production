<?php
/**
 * NESERE Film Production - Public Portfolio Page
 * Displays works added from Admin Panel
 * Luxury Dark & Gold Theme | Bilingual (Amharic & English)
 */
require_once __DIR__ . '/config/config.php';

$db = db();

// ቋንቋ መቀየሪያ
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'am';
    $_SESSION['lang'] = $lang;
}
$current_lang = $_SESSION['lang'] ?? 'am';

// የምድብ ማጣሪያ (Category Filter)
$selected_cat = trim($_GET['cat'] ?? '');
$params = [];

$sql = "SELECT * FROM projects ";
if (!empty($selected_cat)) {
    $sql .= " WHERE category = ? ";
    $params[] = $selected_cat;
}

// አዲስ የተለጠፉት ቀድመው እንዲወጡ
try {
    $cols = $db->query("DESCRIBE projects")->fetchAll(PDO::FETCH_COLUMN);
    $order_col = in_array('project_id', $cols, true) ? 'project_id' : 'id';
    $sql .= " ORDER BY {$order_col} DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $projects = $stmt->fetchAll();
} catch (Exception $e) {
    $projects = [];
}

$page_title = ($current_lang === 'am') ? 'የስራዎቻችን ማሳያ | Nesere Films' : 'Portfolio & Showcases | Nesere Films';

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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .port-container {
            max-width: 1200px;
            margin: 50px auto 90px;
            padding: 0 20px;
        }

        .port-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .port-badge {
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

        .port-header h1 {
            font-size: 34px;
            margin: 0 0 10px 0;
            color: #FFFFFF;
        }

        .port-header p {
            color: var(--text-muted);
            font-size: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Filter Tabs */
        .category-filters {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 45px;
        }

        .cat-btn {
            padding: 8px 18px;
            border-radius: 25px;
            background: #15161B;
            border: 1px solid var(--border-clr);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.25s;
        }

        .cat-btn:hover, .cat-btn.active {
            border-color: var(--gold);
            color: #000;
            background: var(--gold);
        }

        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .project-card {
            background: var(--card-bg);
            border: 1px solid var(--border-clr);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, border-color 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .project-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold);
        }

        .project-thumb-box {
            position: relative;
            width: 100%;
            height: 220px;
            background: #181920;
            overflow: hidden;
        }

        .project-thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .project-card:hover .project-thumb-box img {
            transform: scale(1.05);
        }

        .project-info {
            padding: 22px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cat-tag {
            color: var(--gold);
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .project-title {
            font-size: 18px;
            color: #FFFFFF;
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        .project-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .project-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-clr);
            padding-top: 14px;
            font-size: 13px;
        }

        .client-tag {
            color: #A0A0AB;
        }

        .btn-watch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }

        .btn-watch:hover {
            color: var(--gold-light);
        }
    </style>
</head>
<body>

<div class="port-container">
    <div class="port-header">
        <span class="port-badge"><?= ($current_lang === 'am') ? 'የንስር ፊልም ስራዎች' : 'OUR PORTFOLIO' ?></span>
        <h1><?= ($current_lang === 'am') ? 'የተሰሩ ስራዎች እና ፕሮዳክሽኖች' : 'Featured Works & Productions' ?></h1>
        <p><?= ($current_lang === 'am') ? 'እስካሁን ያዘጋጀናቸው ፊልሞች፣ የቴሌቪዥን ማስታወቂያዎች፣ ሰርጎች እና ዲጂታል ይዘቶች' : 'Explore our collection of films, commercial advertisements, events, and creative media.' ?></p>
    </div>

    <!-- Category Filter Tabs -->
    <div class="category-filters">
        <a href="portfolio.php" class="cat-btn <?= empty($selected_cat) ? 'active' : '' ?>">
            <?= ($current_lang === 'am') ? 'ሁሉም ስራዎች' : 'All Works' ?>
        </a>
        <?php foreach (PORTFOLIO_CATEGORIES as $cat): ?>
            <a href="portfolio.php?cat=<?= urlencode($cat) ?>" class="cat-btn <?= ($selected_cat === $cat) ? 'active' : '' ?>">
                <?= e($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Projects Grid -->
    <div class="projects-grid">
        <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $p): ?>
                <?php 
                    $img_val = $p['cover_image'] ?? ($p['image'] ?? ($p['thumbnail'] ?? ''));
                    $img_src = !empty($img_val) ? 'uploads/' . $img_val : 'assets/images/default.jpg';
                    $disp_title = ($current_lang === 'am' && !empty($p['title_am'])) ? $p['title_am'] : $p['title'];
                ?>
                <div class="project-card">
                    <div class="project-thumb-box">
                        <img src="<?= e($img_src) ?>" alt="<?= e($disp_title) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22350%22 height=%22220%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23181920%22/><text x=%2250%%22 y=%2250%%22 fill=%22%23D4AF37%22 font-size=%2214%22 text-anchor=%22middle%22 dy=%22.3em%22>Nesere Films</text></svg>'">
                    </div>

                    <div class="project-info">
                        <div>
                            <span class="cat-tag"><?= e($p['category'] ?? 'Film') ?></span>
                            <h3 class="project-title"><?= e($disp_title) ?></h3>
                            <?php if (!empty($p['description'])): ?>
                                <p class="project-desc"><?= e($p['description']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="project-footer">
                            <span class="client-tag"><?= e($p['client_name'] ?? 'Production') ?></span>
                            <?php if (!empty($p['video_url'])): ?>
                                <a href="<?= e($p['video_url']) ?>" target="_blank" class="btn-watch">
                                    <i class="fa fa-play-circle"></i> <?= ($current_lang === 'am') ? 'ተመልከት' : 'Watch Video' ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--text-muted);">
                <i class="fa fa-film" style="font-size: 40px; color: var(--border-clr); margin-bottom: 15px; display: block;"></i>
                <p><?= ($current_lang === 'am') ? 'በዚህ ምድብ እስካሁን የተለጠፈ ስራ የለም።' : 'No projects found in this category yet.' ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    require_once __DIR__ . '/includes/footer.php'; 
}
?>
</body>
</html>