<?php

$title = 'Contact';

include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_csrf();

    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $message   = trim($_POST['message'] ?? '');

    if ($full_name === '' || $email === '' || $message === '') {

        flash('danger', 'Please fill in all required fields.');

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        flash('danger', 'Please enter a valid email address.');

    } else {

        /*
         * messages.user_id is NOT NULL in your current database.
         * Therefore the public contact form needs a user account.
         */

        if (!logged()) {

            flash(
                'warning',
                'Please login or create a customer account before sending a message.'
            );

        } else {

            try {

                $subject = 'Contact Form - ' . $full_name;

                $stmt = db()->prepare(
                    'INSERT INTO messages
                    (user_id, subject, message)
                    VALUES (?, ?, ?)'
                );

                $stmt->execute([
                    $_SESSION['user']['user_id'],
                    $subject,
                    $message
                ]);

                flash(
                    'success',
                    'Your message has been sent successfully.'
                );

            } catch (Exception $e) {

                flash(
                    'danger',
                    'Unable to send your message. Please try again.'
                );
            }
        }
    }

    header('Location: contact.php');
    exit;
}

?>

<section class="section">

    <div class="container">

        <div class="row g-5">

            <!-- CONTACT INFORMATION -->

            <div class="col-md-5">

                <p class="gold">CONTACT US</p>

                <p class="text-secondary mt-4">
                    Whether you're planning a film project,
                    TV commercial, YouTube podcast,
                    social media management, or a special
                    event such as a wedding or birthday,
                    <strong>Nesere Film Production</strong>
                    is ready to help bring your vision to life.
                </p>

                <div class="mt-4">

                    <p>
                        <strong>Address:</strong><br>
                        Addis Ababa, Ethiopia, A.A 1000
                    </p>

                    <p>
                        <strong>Hours:</strong><br>
                        Mon-Sun 8:00AM - 6:00PM
                    </p>

                    <p>
                        <strong>Phone:</strong><br>
                        +251970701196 / +251940695261
                    </p>

                    <p>
                        <strong>Email:</strong><br>
                        contact@neserefilmproduction.com
                    </p>

                </div>

            </div>


            <!-- CONTACT FORM -->

            <div class="col-md-7">

                <h2 class="gold mb-3">
                    Get in Touch!
                </h2>

                <p class="text-secondary mb-4">
                    Get in touch with us to discuss your ideas,
                    request a quote, or schedule a consultation.
                    Our team will respond promptly and guide
                    you through the next steps.
                </p>

                <?php show_flash(); ?>


                <form method="POST">

                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="csrf"
                        value="<?= csrf() ?>"
                    >


                    <!-- FULL NAME -->

                    <div class="mb-4">

                        <label class="form-label">
                            Full Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="full_name"
                            class="form-control"
                            placeholder="Input Full Name"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-4">

                        <label class="form-label">
                            Email
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Input Email"
                            required
                        >

                    </div>


                    <!-- MESSAGE -->

                    <div class="mb-4">

                        <label class="form-label">
                            Message
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="message"
                            class="form-control"
                            rows="7"
                            placeholder="Type Here Your Message"
                            required
                        ></textarea>

                    </div>


                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="btn btn-gold px-5 py-3"
                    >
                        SUBMIT
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>