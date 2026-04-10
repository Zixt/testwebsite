<?php
$current_page    = '';
$page_title      = 'Privacy Policy — Matrix Technical Services';
$meta_description = 'Privacy policy for Matrix Technical Services — how we collect, use, and protect your data.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="services-hero">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p>How we collect, use, and protect your personal information.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="policy-content">

            <p class="policy-updated">Last updated: <?= date('F Y') ?></p>

            <h2>1. Who We Are</h2>
            <p>Matrix Technical Services is the data controller for the personal data we collect. You can contact us at <a href="mailto:info@matrixtechnical.co.uk">info@matrixtechnical.co.uk</a> or <a href="tel:+442038134148">020 3813 4148</a>.</p>

            <h2>2. What Data We Collect</h2>
            <p>We may collect the following personal data:</p>
            <ul style="margin:12px 0 16px 24px; display:flex; flex-direction:column; gap:6px; list-style:disc">
                <li>Name, email address, and telephone number (submitted via our contact form or during service enquiries)</li>
                <li>Business name and address (for service provision)</li>
                <li>Technical data such as IP address, browser type, and usage data when you visit our website</li>
            </ul>

            <h2>3. How We Use Your Data</h2>
            <p>We use your personal data to:</p>
            <ul style="margin:12px 0 16px 24px; display:flex; flex-direction:column; gap:6px; list-style:disc">
                <li>Respond to your enquiries and provide our services</li>
                <li>Manage our client relationship and fulfil contractual obligations</li>
                <li>Send service-related communications</li>
                <li>Comply with legal and regulatory requirements</li>
            </ul>

            <h2>4. Legal Basis for Processing</h2>
            <p>We process your data on the following bases: performance of a contract, our legitimate business interests, compliance with a legal obligation, or your consent where required.</p>

            <h2>5. Data Sharing</h2>
            <p>We do not sell your personal data. We may share data with trusted third-party service providers (e.g. email delivery, accounting software) strictly for the purposes of operating our business. All third parties are required to handle data securely and in accordance with GDPR.</p>

            <h2>6. Data Retention</h2>
            <p>We retain personal data for as long as necessary to fulfil the purposes for which it was collected, or as required by law. Client records are typically retained for six years following the end of the business relationship.</p>

            <h2>7. Your Rights</h2>
            <p>Under UK GDPR you have the right to: access your data, correct inaccuracies, request erasure, restrict or object to processing, and data portability. To exercise any of these rights, contact us at <a href="mailto:info@matrixtechnical.co.uk">info@matrixtechnical.co.uk</a>.</p>

            <h2>8. Cookies</h2>
            <p>Our website may use essential cookies to ensure basic functionality. We do not use tracking or advertising cookies without your consent.</p>

            <h2>9. Security</h2>
            <p>We implement appropriate technical and organisational measures to protect your personal data against unauthorised access, loss, or disclosure.</p>

            <h2>10. Complaints</h2>
            <p>If you have concerns about how we handle your data, you have the right to lodge a complaint with the Information Commissioner's Office (ICO) at <a href="https://ico.org.uk" target="_blank" rel="noopener">ico.org.uk</a>.</p>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
