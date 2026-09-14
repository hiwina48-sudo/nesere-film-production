<?php require_once __DIR__.'/../config/config.php'; $base=$base??''; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title??'NESERE Film Production')?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="<?=$base?>assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center"
           href="<?=$base?>index.php">

            <img
                src="<?=$base?>assets/images/logo nesere icon.png"
                alt="NESERE Film Production"
                style="height:55px; width:auto; object-fit:contain;"
                class="me-2"
            >

       

        </a>

        <button
            class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#nav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="nav" class="collapse navbar-collapse">

            <ul class="navbar-nav ms-auto">

                <li>
                    <a class="nav-link"
                       href="<?=$base?>index.php">
                        Home
                    </a>
                </li>

                <li>
                    <a class="nav-link"
                       href="<?=$base?>about.php">
                        About
                    </a>
                </li>

                <li>
                    <a class="nav-link"
                       href="<?=$base?>services.php">
                        Services
                    </a>
                </li>

                <li>
                    <a class="nav-link"
                       href="<?=$base?>portfolio.php">
                        Portfolio
                    </a>
                </li>

                <li>
                    <a class="nav-link"
                       href="<?=$base?>packages.php">
                        Packages
                    </a>
                </li>

                <li>
                    <a class="nav-link"
                       href="<?=$base?>contact.php">
                        Contact
                    </a>
                </li>

                <?php if(logged()): ?>

                    <li>
                        <a class="nav-link gold"
                           href="<?=$base.(admin()?'admin/index.php':'customer/dashboard.php')?>">
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a class="nav-link"
                           href="<?=$base?>logout.php">
                            Logout
                        </a>
                    </li>

                <?php else: ?>

                    <li>
                        <a class="nav-link"
                           href="<?=$base?>login.php">
                            Login
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>
</nav>

<main class="pt-5">