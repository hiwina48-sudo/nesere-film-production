<?php

require_once __DIR__ . '/config/config.php';

if (!logged()) {
    header('Location: login.php');
    exit;
}

$title = 'Payment Successful';

include __DIR__ . '/includes/header.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card border-0 shadow-lg text-center p-4">

                <!-- SUCCESS ICON -->

                <div
                    class="mx-auto mb-4 rounded-circle
                           d-flex align-items-center justify-content-center"
                    style="
                        width:90px;
                        height:90px;
                        background:#e8f8ee;
                    "
                >

                    <i
                        class="fa-solid fa-check"
                        style="
                            color:#198754;
                            font-size:45px;
                        "
                    ></i>

                </div>


                <!-- TITLE -->

                <h2 class="fw-bold mb-3">

                    Payment Successful!

                </h2>


                <p class="text-muted mb-4">

                    Thank you for your payment.
                    Your payment has been completed successfully.

                </p>


                <!-- PAYMENT STATUS -->

                <div
                    class="rounded-3 p-3 mb-4"
                    style="background:#f8f9fa;"
                >

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Payment Status
                        </span>

                        <strong class="text-success">
                            PAID
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Payment Date
                        </span>

                        <strong>
                            <?= date('Y-m-d') ?>
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Customer
                        </span>

                        <strong>
                            <?= e($_SESSION['user']['full_name'] ?? 'Customer') ?>
                        </strong>

                    </div>

                </div>


                <!-- MESSAGE -->

                <div class="alert alert-success">

                    <i class="fa-solid fa-circle-check me-2"></i>

                    Your service request has been received.
                    Our team will contact you soon.

                </div>


                <!-- BUTTONS -->

                <div class="d-grid gap-2">

                    <a
                        href="customer/dashboard.php"
                        class="btn btn-dark"
                    >

                        <i class="fa-solid fa-gauge-high me-2"></i>

                        Go to Dashboard

                    </a>


                    <a
                        href="customer/requests.php"
                        class="btn btn-outline-dark"
                    >

                        <i class="fa-solid fa-file-lines me-2"></i>

                        View My Requests

                    </a>


                    <a
                        href="index.php"
                        class="btn btn-link text-decoration-none"
                    >

                        Back to Home

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<?php

include __DIR__ . '/includes/footer.php';

?>