<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$current_page    = 'signup';
$page_title      = 'Get Started — Matrix Technical Services';
$meta_description = 'Start your journey with Matrix Technical Services. Tell us about your business and service requirements.';

// ================================================================
// SPAM PROTECTION
// ================================================================
define('SIGNUP_SECRET', 'mts-signup-form-v1');
$form_stamp = time();
$form_nonce = hash_hmac('sha256', (string)$form_stamp, SIGNUP_SECRET);

// ================================================================
// FORM HANDLER (POST)
// ================================================================
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Spam checks --------------------------------------------
    $honeypot    = $_POST['_company_url'] ?? '';
    $stamp       = (int)($_POST['_stamp']  ?? 0);
    $nonce       = $_POST['_nonce']  ?? '';
    $age         = time() - $stamp;
    $valid_nonce = hash_equals(hash_hmac('sha256', (string)$stamp, SIGNUP_SECRET), $nonce);

    if ($honeypot !== '' || !$valid_nonce || $age < 3 || $age > 3600) {
        header('Location: /signup?sent=1');
        exit;
    }

    // --- Sanitise inputs ----------------------------------------
    $first_name   = trim(htmlspecialchars($_POST['first_name']   ?? '', ENT_QUOTES, 'UTF-8'));
    $last_name    = trim(htmlspecialchars($_POST['last_name']    ?? '', ENT_QUOTES, 'UTF-8'));
    $job_title    = trim(htmlspecialchars($_POST['job_title']    ?? '', ENT_QUOTES, 'UTF-8'));
    $company      = trim(htmlspecialchars($_POST['company']      ?? '', ENT_QUOTES, 'UTF-8'));
    $email        = trim(htmlspecialchars($_POST['email']        ?? '', ENT_QUOTES, 'UTF-8'));
    $phone        = trim(htmlspecialchars($_POST['phone']        ?? '', ENT_QUOTES, 'UTF-8'));
    $address1     = trim(htmlspecialchars($_POST['address1']     ?? '', ENT_QUOTES, 'UTF-8'));
    $address2     = trim(htmlspecialchars($_POST['address2']     ?? '', ENT_QUOTES, 'UTF-8'));
    $city         = trim(htmlspecialchars($_POST['city']         ?? '', ENT_QUOTES, 'UTF-8'));
    $county       = trim(htmlspecialchars($_POST['county']       ?? '', ENT_QUOTES, 'UTF-8'));
    $postcode     = trim(htmlspecialchars($_POST['postcode']     ?? '', ENT_QUOTES, 'UTF-8'));
    $country      = trim(htmlspecialchars($_POST['country']      ?? 'United Kingdom', ENT_QUOTES, 'UTF-8'));
    $services     = array_map(function($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); },
                        (array)($_POST['services'] ?? []));
    $num_sites    = trim(htmlspecialchars($_POST['num_sites']    ?? '', ENT_QUOTES, 'UTF-8'));
    $current_prov = trim(htmlspecialchars($_POST['current_prov'] ?? '', ENT_QUOTES, 'UTF-8'));
    $timeline     = trim(htmlspecialchars($_POST['timeline']     ?? '', ENT_QUOTES, 'UTF-8'));
    $notes        = trim(htmlspecialchars($_POST['notes']        ?? '', ENT_QUOTES, 'UTF-8'));
    $consent      = isset($_POST['consent']);

    // --- Validate -----------------------------------------------
    if ($first_name === '')  $errors[] = 'Please enter your first name.';
    if ($last_name === '')   $errors[] = 'Please enter your last name.';
    if ($company === '')     $errors[] = 'Please enter your company name.';
    if (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL))
                             $errors[] = 'Please enter a valid email address.';
    if ($phone === '')       $errors[] = 'Please enter your phone number.';
    if ($address1 === '')    $errors[] = 'Please enter your address.';
    if ($city === '')        $errors[] = 'Please enter your city or town.';
    if ($postcode === '')    $errors[] = 'Please enter your postcode.';
    if (empty($services))   $errors[] = 'Please select at least one service.';
    if ($num_sites === '')   $errors[] = 'Please enter the number of sites or locations.';
    if (!$consent)           $errors[] = 'Please confirm your agreement to the Privacy Policy.';

    // --- Send via PHPMailer -------------------------------------
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
                    $mail->isSMTP();
                    $mail->Host       = $cfg['smtp_host'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $cfg['smtp_username'];
                    $mail->Password   = $cfg['smtp_password'];
                    $mail->SMTPSecure = $cfg['smtp_secure'] === 'ssl'
                        ? PHPMailer::ENCRYPTION_SMTPS
                        : PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = (int) $cfg['smtp_port'];
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom($cfg['smtp_from'], $cfg['smtp_from_name']);
                    $mail->addAddress($cfg['mail_to']);
                    $mail->addReplyTo($email, $first_name . ' ' . $last_name);

                    $services_str = implode(', ', $services) ?: '—';
                    $addr_full    = $address1 . ($address2 ? '<br>' . $address2 : '');
                    $addr_plain   = $address1 . ($address2 ? "\n" . $address2 : '');

                    $row = function($label, $value) {
                        return '<tr>
                            <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;font-weight:600;width:160px;color:#475569;font-size:0.88em">' . $label . '</td>
                            <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;font-size:0.88em;color:#0f172a">' . $value . '</td>
                        </tr>';
                    };

                    $mail->isHTML(true);
                    $mail->Subject = 'New Service Enquiry — ' . $company;
                    $mail->Body = '
                        <html><body style="font-family:\'Inter\',sans-serif;color:#0f172a;max-width:640px;margin:0 auto">
                        <div style="background:#0a1628;padding:24px 32px;border-radius:6px 6px 0 0">
                            <h2 style="color:#fff;margin:0;font-size:1.2em">New Service Enquiry</h2>
                            <p style="color:#7dd3f0;margin:6px 0 0;font-size:0.85em">Matrix Technical Services — Get Started Form</p>
                        </div>
                        <div style="padding:28px 32px;background:#f8fafc;border-radius:0 0 6px 6px;border:1px solid #e2e8f0;border-top:none">

                            <h3 style="color:#0a1628;margin:0 0 12px;font-size:0.75em;text-transform:uppercase;letter-spacing:0.1em;border-bottom:2px solid #0284c7;padding-bottom:6px">Contact Details</h3>
                            <table style="width:100%;border-collapse:collapse;margin-bottom:24px">
                                ' . $row('Name', $first_name . ' ' . $last_name)
                                . $row('Job Title', $job_title ?: '—')
                                . $row('Company', $company)
                                . $row('Email', '<a href="mailto:' . $email . '" style="color:#0284c7">' . $email . '</a>')
                                . $row('Phone', $phone) . '
                            </table>

                            <h3 style="color:#0a1628;margin:0 0 12px;font-size:0.75em;text-transform:uppercase;letter-spacing:0.1em;border-bottom:2px solid #0284c7;padding-bottom:6px">Business Address</h3>
                            <table style="width:100%;border-collapse:collapse;margin-bottom:24px">
                                ' . $row('Address', $addr_full)
                                . $row('City', $city)
                                . $row('County', $county ?: '—')
                                . $row('Postcode', $postcode)
                                . $row('Country', $country) . '
                            </table>

                            <h3 style="color:#0a1628;margin:0 0 12px;font-size:0.75em;text-transform:uppercase;letter-spacing:0.1em;border-bottom:2px solid #0284c7;padding-bottom:6px">Service Requirements</h3>
                            <table style="width:100%;border-collapse:collapse">
                                ' . $row('Services', $services_str)
                                . $row('No. of Sites', $num_sites)
                                . $row('Current Provider', $current_prov ?: '—')
                                . $row('Timeline', $timeline ?: '—')
                                . $row('Notes', $notes ? nl2br($notes) : '—') . '
                            </table>
                        </div>
                        </body></html>';

                    $mail->AltBody = "New Service Enquiry — {$company}\n\n"
                        . "CONTACT DETAILS\n"
                        . "Name: {$first_name} {$last_name}\nJob Title: {$job_title}\nCompany: {$company}\nEmail: {$email}\nPhone: {$phone}\n\n"
                        . "BUSINESS ADDRESS\n{$addr_plain}\n{$city}\n" . ($county ? "{$county}\n" : '') . "{$postcode}\n{$country}\n\n"
                        . "SERVICE REQUIREMENTS\nServices: {$services_str}\nNo. of Sites: {$num_sites}\n"
                        . "Current Provider: {$current_prov}\nTimeline: {$timeline}\n\nNotes:\n{$notes}";

                    $mail->send();
                    header('Location: /signup?sent=1');
                    exit;

                } catch (Exception $e) {
                    error_log('PHPMailer error: ' . $e->getMessage());
                    error_log('PHPMailer debug: ' . $mail->ErrorInfo);
                    $errors[] = 'Sorry, there was a problem submitting your enquiry. Please try again or contact us directly.';
                }
            }
        }
    }
}

$flash_sent = isset($_GET['sent']) && $_GET['sent'] === '1';

require_once __DIR__ . '/includes/header.php';

// Helper to re-populate fields after a server-side error
function old(string $key, string $default = ''): string {
    return htmlspecialchars($_POST[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}
function checked_if(string $key, string $value): string {
    return in_array($value, (array)($_POST['services'] ?? []), true) ? ' checked' : '';
}
function selected_if(string $key, string $value): string {
    return (($_POST[$key] ?? '') === $value) ? ' selected' : '';
}
?>

<!-- Page hero -->
<section class="services-hero">
    <div class="container">
        <h1>Get Started</h1>
        <p>Tell us about your business and requirements — we'll be in touch within one working day.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <?php if ($flash_sent): ?>
        <div class="alert alert--success" style="max-width:700px;margin:0 auto" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            <div>
                <strong>Enquiry received — thank you!</strong><br>
                A member of our team will review your requirements and be in touch within one working day.
            </div>
        </div>
        <?php else: ?>

        <!-- Step indicator -->
        <div class="step-indicator" aria-label="Form progress">
            <div class="step-indicator__step active" data-step="1">
                <div class="step-indicator__num">1</div>
                <span>Your Details</span>
            </div>
            <div class="step-indicator__connector"></div>
            <div class="step-indicator__step" data-step="2">
                <div class="step-indicator__num">2</div>
                <span>Business Address</span>
            </div>
            <div class="step-indicator__connector"></div>
            <div class="step-indicator__step" data-step="3">
                <div class="step-indicator__num">3</div>
                <span>Requirements</span>
            </div>
            <div class="step-indicator__connector"></div>
            <div class="step-indicator__step" data-step="4">
                <div class="step-indicator__num">4</div>
                <span>Review &amp; Submit</span>
            </div>
        </div>

        <!-- Form card -->
        <div class="signup-card">

            <?php if (!empty($errors)): ?>
            <div class="alert alert--error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= implode(' ', $errors) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/signup" class="js-signup-form" novalidate>

                <!-- Spam protection -->
                <div class="form-honeypot" aria-hidden="true">
                    <input type="text" name="_company_url" tabindex="-1" autocomplete="off" value="">
                </div>
                <input type="hidden" name="_stamp" value="<?= $form_stamp ?>">
                <input type="hidden" name="_nonce" value="<?= $form_nonce ?>">

                <!-- ================================================
                     STEP 1 — Your Details
                     ================================================ -->
                <div class="signup-step active" data-step="1">
                    <h3 class="signup-step__title">Your Details</h3>
                    <p class="signup-step__desc">Tell us who you are and the best way to reach you.</p>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="text" id="first_name" name="first_name" required
                                   data-error="Please enter your first name."
                                   placeholder="John"
                                   value="<?= old('first_name') ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="text" id="last_name" name="last_name" required
                                   data-error="Please enter your last name."
                                   placeholder="Smith"
                                   value="<?= old('last_name') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="job_title">Job Title</label>
                            <input type="text" id="job_title" name="job_title"
                                   placeholder="IT Manager"
                                   value="<?= old('job_title') ?>">
                        </div>
                        <div class="form-group">
                            <label for="company">Company Name <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="text" id="company" name="company" required
                                   data-error="Please enter your company name."
                                   placeholder="Acme Ltd"
                                   value="<?= old('company') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="su_email">Email Address <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="email" id="su_email" name="email" required
                                   data-error="Please enter a valid email address."
                                   placeholder="john@acme.co.uk"
                                   value="<?= old('email') ?>">
                        </div>
                        <div class="form-group">
                            <label for="su_phone">Phone Number <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="tel" id="su_phone" name="phone" required
                                   data-error="Please enter your phone number."
                                   placeholder="020 3813 4148"
                                   value="<?= old('phone') ?>">
                        </div>
                    </div>

                    <div class="signup-step__nav">
                        <span></span>
                        <button type="button" class="btn btn--primary signup-next">
                            Next: Business Address
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- ================================================
                     STEP 2 — Business Address
                     ================================================ -->
                <div class="signup-step" data-step="2">
                    <h3 class="signup-step__title">Business Address</h3>
                    <p class="signup-step__desc">Your primary business or billing address.</p>

                    <div class="form-group">
                        <label for="address1">Address Line 1 <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                        <input type="text" id="address1" name="address1" required
                               data-error="Please enter your address."
                               placeholder="123 Business Park"
                               value="<?= old('address1') ?>">
                    </div>

                    <div class="form-group">
                        <label for="address2">Address Line 2</label>
                        <input type="text" id="address2" name="address2"
                               placeholder="Suite 4"
                               value="<?= old('address2') ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City / Town <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="text" id="city" name="city" required
                                   data-error="Please enter your city or town."
                                   placeholder="London"
                                   value="<?= old('city') ?>">
                        </div>
                        <div class="form-group">
                            <label for="county">County</label>
                            <input type="text" id="county" name="county"
                                   placeholder="Greater London"
                                   value="<?= old('county') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="postcode">Postcode <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="text" id="postcode" name="postcode" required
                                   data-error="Please enter your postcode."
                                   placeholder="EC1A 1BB"
                                   value="<?= old('postcode') ?>">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country">
                                <option value="United Kingdom"<?= selected_if('country', 'United Kingdom') ?: ' selected' ?>>United Kingdom</option>
                                <option value="Ireland"<?= selected_if('country', 'Ireland') ?>>Ireland</option>
                                <option value="Other"<?= selected_if('country', 'Other') ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="signup-step__nav">
                        <button type="button" class="btn btn--outline-blue signup-back">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button type="button" class="btn btn--primary signup-next">
                            Next: Requirements
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- ================================================
                     STEP 3 — Service Requirements
                     ================================================ -->
                <div class="signup-step" data-step="3">
                    <h3 class="signup-step__title">Service Requirements</h3>
                    <p class="signup-step__desc">Help us understand what you need so we can tailor our response.</p>

                    <div class="form-group">
                        <label>Services Required <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                        <div class="services-checkboxes">
                            <label class="form-check">
                                <input type="checkbox" name="services[]" value="Connectivity"<?= checked_if('services', 'Connectivity') ?>>
                                Connectivity
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="services[]" value="Managed Networks"<?= checked_if('services', 'Managed Networks') ?>>
                                Managed Networks
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="services[]" value="EPOS Systems"<?= checked_if('services', 'EPOS Systems') ?>>
                                EPOS Systems
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="services[]" value="Payments"<?= checked_if('services', 'Payments') ?>>
                                Payments
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="services[]" value="IT Services"<?= checked_if('services', 'IT Services') ?>>
                                IT Services
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="num_sites">Number of Sites / Locations <span style="color:var(--blue)" aria-hidden="true">*</span></label>
                            <input type="number" id="num_sites" name="num_sites" min="1" required
                                   data-error="Please enter the number of sites."
                                   placeholder="1"
                                   value="<?= old('num_sites') ?>">
                        </div>
                        <div class="form-group">
                            <label for="current_prov">Current Provider</label>
                            <input type="text" id="current_prov" name="current_prov"
                                   placeholder="e.g. BT, Virgin Media"
                                   value="<?= old('current_prov') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="timeline">Expected Timeline</label>
                        <select id="timeline" name="timeline">
                            <option value="">— Please select —</option>
                            <option value="As soon as possible"<?= selected_if('timeline', 'As soon as possible') ?>>As soon as possible</option>
                            <option value="1–3 months"<?= selected_if('timeline', '1–3 months') ?>>1–3 months</option>
                            <option value="3–6 months"<?= selected_if('timeline', '3–6 months') ?>>3–6 months</option>
                            <option value="6+ months"<?= selected_if('timeline', '6+ months') ?>>6+ months</option>
                            <option value="Just exploring"<?= selected_if('timeline', 'Just exploring') ?>>Just exploring</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Requirements</label>
                        <textarea id="notes" name="notes"
                                  placeholder="Any details about your current setup, specific requirements, or questions…"><?= old('notes') ?></textarea>
                    </div>

                    <div class="signup-step__nav">
                        <button type="button" class="btn btn--outline-blue signup-back">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button type="button" class="btn btn--primary signup-next">
                            Review Enquiry
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- ================================================
                     STEP 4 — Review & Submit
                     ================================================ -->
                <div class="signup-step" data-step="4">
                    <h3 class="signup-step__title">Review &amp; Submit</h3>
                    <p class="signup-step__desc">Please check your details before submitting your enquiry.</p>

                    <div id="signup-summary"></div>

                    <div class="form-group" style="margin-top:28px">
                        <label class="form-check form-check--consent">
                            <input type="checkbox" name="consent" required
                                   data-error="You must agree to the Privacy Policy to continue."
                                   <?= isset($_POST['consent']) ? 'checked' : '' ?>>
                            I have read and agree to the
                            <a href="/privacy" target="_blank" rel="noopener">Privacy Policy</a>
                            and consent to Matrix Technical Services contacting me regarding my enquiry.
                        </label>
                    </div>

                    <div class="signup-step__nav">
                        <button type="button" class="btn btn--outline-blue signup-back">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <button type="submit" class="btn btn--primary">
                            Submit Enquiry
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
