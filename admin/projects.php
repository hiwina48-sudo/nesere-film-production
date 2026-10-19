<?php
require_once __DIR__ . '/auth_check.php';

$db = db();
$error = '';

// 1. Delete Project
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    check_csrf();
    $project_id = (int)($_GET['id'] ?? 0);
    if ($project_id > 0) {
        $stmt = $db->prepare("DELETE FROM projects WHERE project_id = ?");
        $stmt->execute([$project_id]);
        flash('success', 'ፕሮጀክቱ በተሳካ ሁኔታ ተሰርዟል።');
        redirect('projects.php');
    }
}

// 2. Add / Edit Project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_project'])) {
    check_csrf();
    $project_id   = (int)post('project_id');
    $title        = post('title');
    $category     = post('category');
    $video_url    = post('video_url');
    $description  = post('description');
    
    // Image Upload Handling
    $thumbnail = post('existing_thumbnail');
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $file_name = 'proj_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $file_name)) {
            $thumbnail = 'uploads/' . $file_name;
        }
    }

    if (!empty($title) && !empty($category)) {
        if ($project_id > 0) {
            // Update
            $stmt = $db->prepare("UPDATE projects SET title = ?, category = ?, thumbnail = ?, video_url = ?, description = ? WHERE project_id = ?");
            $stmt->execute([$title, $category, $thumbnail, $video_url, $description, $project_id]);
            flash('success', 'ፕሮጀክቱ በተሳካ ሁኔታ ተሻሽሏል።');
        } else {
            // Create
            $stmt = $db->prepare("INSERT INTO projects (title, category, thumbnail, video_url, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $category, $thumbnail, $video_url, $description]);
            flash('success', 'አዲስ ፕሮጀክት በተሳካ ሁኔታ ተጨምሯል።');
        }
        redirect('projects.php');
    } else {
        $error = 'እባክዎን ዋና ዋናዎቹን መስኮች (ርዕስ እና ካቴጎሪ) ይሙሉ!';
    }
}

// Edit Mode Check
$edit_project = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit') {
    $edit_id = (int)($_GET['id'] ?? 0);
    if ($edit_id > 0) {
        $stmt = $db->prepare("SELECT * FROM projects WHERE project_id = ?");
        $stmt->execute([$edit_id]);
        $edit_project = $stmt->fetch();
    }
}

// Fetch All Projects
$stmt = $db->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <title>ፕሮጀክቶች ማስተዳደሪያ | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #0f0f11; color: #fff; font-family: Segoe UI, sans-serif; margin: 0; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #18181c; border-right: 1px solid #2a2a32; padding: 20px; }
        .sidebar a { color: #a0a0ab; text-decoration: none; display: block; padding: 10px; border-radius: 6px; margin-bottom: 5px; }
        .sidebar a.active, .sidebar a:hover { background: #f39c12; color: #fff; }
        .main-content { flex: 1; padding: 30px; }
        .form-box, .table-box { background: #18181c; border: 1px solid #2a2a32; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #a0a0ab; }
        .form-control { width: 100%; padding: 10px; background: #0f0f11; border: 1px solid #333; color: #fff; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { padding: 10px 20px; background: #f39c12; border: none; color: #fff; font-weight: bold; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #2a2a32; text-align: left; }
        th { color: #f39c12; background: #0f0f11; }
        .btn-edit { color: #3498db; text-decoration: none; margin-right: 10px; }
        .btn-delete { color: #e74c3c; text-decoration: none; }
        .alert-danger { background: rgba(231,76,60,0.2); color: #e74c3c; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h3 style="color:#f39c12;">NESERE ADMIN</h3>
            <a href="index.php">ዳሽቦርድ</a>
            <a href="requests.php">አገልግሎት ጥያቄዎች</a>
            <a href="projects.php" class="active">ፕሮጀክቶች</a>
            <a href="users.php">ተጠቃሚዎች</a>
            <a href="../logout.php" style="color:#e74c3c;">ውጣ</a>
        </div>
        <div class="main-content">
            <h1>ፕሮጀክቶች (Portfolio) ማስተዳደሪያ</h1>
            <?php show_flash(); ?>
            <?php if ($error): ?><div class="alert-danger"><?= e($error) ?></div><?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="form-box">
                <h2 style="color:#f39c12; margin-top:0;"><?= $edit_project ? 'ፕሮጀክት አስተካክል' : 'አዲስ ፕሮጀክት ጨምር' ?></h2>
                <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="save_project" value="1">
                    <input type="hidden" name="project_id" value="<?= e($edit_project['project_id'] ?? 0) ?>">
                    <input type="hidden" name="existing_thumbnail" value="<?= e($edit_project['thumbnail'] ?? '') ?>">

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 2;">
                            <label>የፕሮጀክት ርዕስ *</label>
                            <input type="text" name="title" class="form-control" value="<?= e($edit_project['title'] ?? '') ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>ካቴጎሪ *</label>
                            <select name="category" class="form-control" required>
                                <option value="Movie" <?= ($edit_project['category'] ?? '') === 'Movie' ? 'selected' : '' ?>>ፊልም (Movie)</option>
                                <option value="Music Video" <?= ($edit_project['category'] ?? '') === 'Music Video' ? 'selected' : '' ?>>ሙዚቃ ቪዲዮ</option>
                                <option value="Commercial" <?= ($edit_project['category'] ?? '') === 'Commercial' ? 'selected' : '' ?>>ማስታወቂያ (Commercial)</option>
                                <option value="Documentary" <?= ($edit_project['category'] ?? '') === 'Documentary' ? 'selected' : '' ?>>ዶክመንተሪ</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>የቪዲዮ ሊንክ (YouTube/Vimeo Embed URL)</label>
                            <input type="url" name="video_url" class="form-control" value="<?= e($edit_project['video_url'] ?? '') ?>" placeholder="https://www.youtube.com/embed/...">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>ከቨር ምስል (Thumbnail Image)</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>ማብራሪያ/መግለጫ</label>
                        <textarea name="description" class="form-control" rows="3"><?= e($edit_project['description'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn-submit"><?= $edit_project ? 'አድስ (Update)' : 'መዝግብ (Save)' ?></button>
                    <?php if ($edit_project): ?>
                        <a href="projects.php" style="color:#a0a0ab; margin-left:15px;">ሰርዝ (Cancel)</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Table List -->
            <div class="table-box">
                <h2>የፕሮጀክቶች ዝርዝር</h2>
                <table>
                    <thead>
                        <tr>
                            <th># ID</th>
                            <th>ምስል</th>
                            <th>ርዕስ</th>
                            <th>ካቴጎሪ</th>
                            <th>ቀን</th>
                            <th>ተግባር</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                            <tr>
                                <td>#<?= e($p['project_id']) ?></td>
                                <td>
                                    <?php if (!empty($p['thumbnail'])): ?>
                                        <img src="../<?= e($p['thumbnail']) ?>" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <span style="color:#666;">ምስል የለም</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($p['title']) ?></td>
                                <td><?= e($p['category']) ?></td>
                                <td><?= e(date('Y-m-d', strtotime($p['created_at']))) ?></td>
                                <td>
                                    <a href="projects.php?action=edit&id=<?= e($p['project_id']) ?>" class="btn-edit">አስተካክል</a>
                                    <a href="projects.php?action=delete&id=<?= e($p['project_id']) ?>&csrf_token=<?= e($_SESSION['csrf_token'] ?? '') ?>" class="btn-delete" onclick="return confirm('እርግጠኛ ነዎት ይህ ፕሮጀክት ይጥፋ?');">አጥፋ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>