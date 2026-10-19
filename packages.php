<?php
require_once __DIR__ . '/config/config.php';

// የሲስተሙን Packages ከ service_packages table ማምጫ
$stmt = db()->query("SELECT * FROM service_packages ORDER BY price ASC");
$packages = $stmt->fetchAll();

$title = 'Our Packages';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="gold">Our Packages</h1>
            <p class="text-secondary">Choose the best package for your film production and media needs.</p>
        </div>

        <?php show_flash(); ?>

        <div class="row g-4">
            <?php foreach ($packages as $pkg): ?>
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-gold bg-dark text-light">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title gold"><?= e($pkg['title'] ?? $pkg['package_name'] ?? 'Package') ?></h3>
                            <h2 class="my-3"><?= e(etb($pkg['price'] ?? 0)) ?></h2>
                            <p class="card-text text-secondary flex-grow-1">
                                <?= nl2br(e($pkg['description'] ?? '')) ?>
                            </p>
                            <a href="service_request.php?package_id=<?= (int)($pkg['package_id'] ?? $pkg['id'] ?? 0) ?>" class="btn btn-gold w-100 mt-3">
                                Select Package
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$packages): ?>
                <div class="col-12 text-center text-secondary my-5">
                    <h3>No packages available at the moment.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>