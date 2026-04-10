<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$current_page    = 'contact';
$page_title      = 'Contact Us — Matrix Technical Services';
$meta_description = 'Get in touch with Matrix Technical Services. We\'d love to hear about your business and how we can help.';

// ================================================================
// FORM HANDLER (POST)
// ================================================================
$errors   = [];
$sent     = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Sanitise inputs ----------------------------------------
    $name    = trim(htmlspecialchars($_POST['name']    ?? '', ENT_QUOTES, 'UTF-8'));
    $email   = trim(htmlspecialchars($_POST['email']   ?? '', ENT_QUOTES, 'UTF-8'));
    $phone   = trim(htmlspecialchars($_POST['phone']   ?? '', ENT_QUOTES, 'UTF-8'));
    $service = trim(htmlspecialchars($_POST['service'] ?? '', ENT_QUOTES, 'UTF-8'));
    $message = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));

    // --- Validate -----------------------------------------------
    if ($name === '') {
        $errors[] = 'Please enter your name.';
    }
    if (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($message === '') {
        $errors[] = 'Please enter your message.';
    }

    // --- Send via SMTP (PHPMailer) -------------------------------
    if (empty($errors)) {
        $config_path = __DIR__ . '/config.php';

        if (!file_exists($config_path)) {
            $errors[] = 'Mail configuration not found. Please set up config.php.';
        } else {
            $cfg = require $config_path;

            $vendor_path = __DIR__ . '/vendor/autoload.php';
            if (!file_exists($vendor_path)) {
                $errors[] = 'PHPMailer not installed. Please run <code>composer install</code>.';
            } else {
                require_once $vendor_path;

                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = $cfg['smtp_host'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $cfg['smtp_username'];
                    $mail->Password   = $cfg['smtp_password'];
                    $mail->SMTPSecure = $cfg['smtp_secure'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = (int) $cfg['smtp_port'];
                    $mail->CharSet    = 'UTF-8';

                    // Sender / recipient
                    $mail->setFrom($cfg['smtp_from'], $cfg['smtp_from_name']);
                    $mail->addAddress($cfg['mail_to']);
                    $mail->addReplyTo($email, $name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Website Enquiry from ' . $name;
                    $mail->Body    = '
                        <html><body style="font-family:sans-serif;color:#222;max-width:600px;margin:0 auto">
                        <div style="background:#0d1b2a;padding:24px 32px;border-radius:8px 8px 0 0">
                            <h2 style="color:#fff;margin:0">New Website Enquiry</h2>
                            <p style="color:#90caf9;margin:6px 0 0;font-size:0.9em">Matrix Technical Services</p>
                        </div>
                        <div style="padding:28px 32px;background:#f5f7fa;border-radius:0 0 8px 8px;border:1px solid #e8ecf0;border-top:none">
                            <table style="width:100%;border-collapse:collapse">
                                <tr><td style="padding:10px 0;border-bottom:1px solid #e8ecf0;font-weight:700;width:130px;color:#555">Name</td><td style="padding:10px 0;border-bottom:1px solid #e8ecf0">' . $name . '</td></tr>
                                <tr><td style="padding:10px 0;border-bottom:1px solid #e8ecf0;font-weight:700;color:#555">Email</td><td style="padding:10px 0;border-bottom:1px solid #e8ecf0"><a href="mailto:' . $email . '">' . $email . '</a></td></tr>
                                <tr><td style="padding:10px 0;border-bottom:1px solid #e8ecf0;font-weight:700;color:#555">Phone</td><td style="padding:10px 0;border-bottom:1px solid #e8ecf0">' . ($phone ?: '—') . '</td></tr>
                                <tr><td style="padding:10px 0;border-bottom:1px solid #e8ecf0;font-weight:700;color:#555">Service</td><td style="padding:10px 0;border-bottom:1px solid #e8ecf0">' . ($service ?: '—') . '</td></tr>
                            </table>
                            <div style="margin-top:20px">
                                <p style="font-weight:700;color:#555;margin-bottom:8px">Message</p>
                                <p style="background:#fff;border:1px solid #e8ecf0;border-radius:6px;padding:14px 16px;margin:0;line-height:1.7">' . nl2br($message) . '</p>
                            </div>
                        </div>
                        </body></html>';
                    $mail->AltBody = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nService: {$service}\n\nMessage:\n{$message}";

                    $mail->send();

                    // Redirect with success flag (PRG pattern)
                    header('Location: /contact?sent=1');
                    exit;

                } catch (Exception $e) {
                    error_log('PHPMailer error: ' . $e->getMessage());
                    error_log('PHPMailer debug: ' . $mail->ErrorInfo);
                    $errors[] = 'Sorry, there was a problem sending your message. Please try again or contact us directly.';
                }
            }
        }
    }
}

// Flash messages from redirect
$flash_sent  = isset($_GET['sent'])  && $_GET['sent']  === '1';
$flash_error = isset($_GET['error']) && $_GET['error'] === '1';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page hero -->
<section class="contact-hero">
    <div class="container">
        <h1>Get In Touch</h1>
        <p>Have a question, need a quote, or just want to find out how we can help? We'd love to hear from you.</p>
    </div>
</section>

<!-- Contact section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-layout">

            <!-- Contact info -->
            <div class="contact-info">
                <h3>Contact Information</h3>

                <div class="contact-info-item">
                    <div class="contact-info-item__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1.24h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.82a16 16 0 0 0 6 6l.82-.82a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <h4>Phone</h4>
                        <p><a href="tel:+442038134148">020 3813 4148</a></p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-item__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <h4>Email</h4>
                        <p><a href="mailto:info@matrixtechnical.co.uk">info@matrixtechnical.co.uk</a></p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-item__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <h4>Location</h4>
                        <p>United Kingdom</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-item__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <h4>Business Hours</h4>
                        <p>Monday – Friday: 9am – 5:30pm</p>
                    </div>
                </div>
            </div>

            <!-- Contact form card -->
            <div class="contact-form-card">
                <h3>Send Us a Message</h3>

                <?php if ($flash_sent): ?>
                <div class="alert alert--success" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Thank you! Your message has been sent — we'll be in touch shortly.
                </div>
                <?php endif; ?>

                <?php if ($flash_error || !empty($errors)): ?>
                <div class="alert alert--error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php if (!empty($errors)): ?>
                        <?= implode(' ', $errors) ?>
                    <?php else: ?>
                        Sorry, there was a problem sending your message. Please try again.
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="/contact" class="js-contact-form" novalidate>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="text" id="name" name="name" required
                                   placeholder="John Smith"
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required
                                   placeholder="john@company.co.uk"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                   placeholder="020 3813 4148"
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="service">Service of Interest</label>
                            <select id="service" name="service">
                                <option value="">— Please select —</option>
                                <option value="Connectivity"     <?= (($_POST['service'] ?? '') === 'Connectivity')     ? 'selected' : '' ?>>Connectivity</option>
                                <option value="Managed Networks" <?= (($_POST['service'] ?? '') === 'Managed Networks') ? 'selected' : '' ?>>Managed Networks</option>
                                <option value="EPOS Systems"     <?= (($_POST['service'] ?? '') === 'EPOS Systems')     ? 'selected' : '' ?>>EPOS Systems</option>
                                <option value="Payments"         <?= (($_POST['service'] ?? '') === 'Payments')         ? 'selected' : '' ?>>Payments</option>
                                <option value="IT Services"      <?= (($_POST['service'] ?? '') === 'IT Services')      ? 'selected' : '' ?>>IT Services</option>
                                <option value="General Enquiry"  <?= (($_POST['service'] ?? '') === 'General Enquiry')  ? 'selected' : '' ?>>General Enquiry</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" required
                                  placeholder="Tell us about your business and what you're looking for…"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center">
                        Send Message
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>

                </form>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
