<?php
$current_page    = '';
$page_title      = 'IT & Technology Services in London | Matrix Technical Services';
$meta_description = 'Managed IT, WiFi installation, business connectivity, EPOS systems, and payment solutions for London businesses. Expert support from Matrix Technical Services. Call 020 3813 4148.';

$page_schema = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => ['LocalBusiness', 'ProfessionalService'],
    'name'        => 'Matrix Technical Services',
    'description' => 'Complete managed technology solutions for London businesses — WiFi installation, business connectivity, EPOS systems, payment solutions, and IT support.',
    'telephone'   => '+442038134148',
    'email'       => 'info@matrixtechnical.co.uk',
    'url'         => 'https://matrixtechnical.co.uk',
    'areaServed'  => [
        ['@type' => 'City', 'name' => 'London', 'addressCountry' => 'GB'],
        ['@type' => 'AdministrativeArea', 'name' => 'Greater London'],
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name'  => 'Technology Services for London Businesses',
        'itemListElement' => [
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Business WiFi Installation London', 'url' => 'https://matrixtechnical.co.uk/wifi-installation-london']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Business Connectivity & Leased Lines London', 'url' => 'https://matrixtechnical.co.uk/business-connectivity-london']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'EPOS Systems London', 'url' => 'https://matrixtechnical.co.uk/epos-systems-london']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Managed Networks London']],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'IT Support & Services London']],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

require_once __DIR__ . '/includes/header.php';
?>

<section class="services-hero">
    <div class="container">
        <h1>Technology Services for London Businesses</h1>
        <p>Matrix Technical Services delivers complete managed technology solutions across Greater London — from WiFi installation and leased lines to EPOS systems, payments, and IT support.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="section-header">
            <h2>What We Do in London</h2>
            <span class="section-divider"></span>
            <p>We are a UK-based managed technology provider serving London businesses of every size. Whether you run a single office, a restaurant group, or a multi-site retail chain — we provide the connectivity, infrastructure, and support to keep your operations running reliably.</p>
        </div>

        <div class="services-grid" style="margin-bottom:64px">

            <div class="service-card">
                <div class="service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01"/></svg>
                </div>
                <h3>WiFi Installation, London</h3>
                <p>Enterprise-grade wireless networks for offices, retail, hospitality, and multi-site businesses — surveyed, installed, and managed by our engineers.</p>
                <a href="/wifi-installation-london" class="service-card__link">
                    Learn more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="service-card">
                <div class="service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <h3>Business Connectivity, London</h3>
                <p>Dedicated leased lines, business fibre broadband, and 4G/5G failover — with guaranteed uptime SLAs and a single point of contact.</p>
                <a href="/business-connectivity-london" class="service-card__link">
                    Learn more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="service-card">
                <div class="service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                </div>
                <h3>EPOS Systems, London</h3>
                <p>Point-of-sale system supply, installation, and support for London retailers, restaurants, and hospitality venues — with integrated payments and networking.</p>
                <a href="/epos-systems-london" class="service-card__link">
                    Learn more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="service-card">
                <div class="service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="6" height="6" rx="1"/><rect x="16" y="2" width="6" height="6" rx="1"/><rect x="9" y="16" width="6" height="6" rx="1"/><path d="M5 8v4h14V8M12 12v4"/></svg>
                </div>
                <h3>Managed Networks, London</h3>
                <p>Fully managed LAN, WAN, and wireless infrastructure — designed, deployed, and monitored around the clock for London businesses.</p>
                <a href="/services#managed-networks" class="service-card__link">
                    Learn more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="service-card">
                <div class="service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <h3>Payment Solutions, London</h3>
                <p>Integrated card payment terminals, contactless solutions, and online payment gateways — with competitive transaction rates for London businesses.</p>
                <a href="/services#payments" class="service-card__link">
                    Learn more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="service-card">
                <div class="service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><polyline points="8 21 12 17 16 21"/></svg>
                </div>
                <h3>IT Support, London</h3>
                <p>Helpdesk support, hardware, cloud solutions, and cybersecurity — your complete IT department for London businesses of every size.</p>
                <a href="/services#it-services" class="service-card__link">
                    Learn more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

        </div>

        <div class="seo-two-col">
            <div>
                <h2>Why London Businesses Choose Matrix Technical Services</h2>
                <p>London businesses need technology partners who respond fast, understand commercial environments, and don't pass you between departments when something goes wrong. Matrix Technical Services is a single-supplier solution — covering connectivity, networking, EPOS, payments, and IT — so you deal with one team that knows your setup.</p>
                <ul class="seo-list">
                    <li><strong>Single point of contact</strong> — one number for all your technology issues</li>
                    <li><strong>UK-based engineers</strong> — on-site across Greater London</li>
                    <li><strong>Multi-site experience</strong> — managing technology across multiple London locations</li>
                    <li><strong>No lock-in</strong> — we recommend what's right for your business, not what earns us the most margin</li>
                    <li><strong>24/7 monitoring</strong> — proactive alerts before issues become outages</li>
                </ul>
            </div>
            <div>
                <h2>London Areas We Cover</h2>
                <p>Our engineers are based in and around London and operate across all Greater London boroughs, including:</p>
                <ul class="seo-list">
                    <li>City of London &amp; Canary Wharf</li>
                    <li>Westminster, Soho &amp; Mayfair</li>
                    <li>Shoreditch, Hackney &amp; East London</li>
                    <li>Southwark, Bermondsey &amp; South London</li>
                    <li>Camden, Islington &amp; North London</li>
                    <li>Hammersmith, Fulham &amp; West London</li>
                    <li>Greenwich, Lewisham &amp; South East London</li>
                    <li>All outer London boroughs</li>
                </ul>
                <p style="margin-top:12px">We also serve businesses in Surrey, Berkshire, and the wider South East.</p>
            </div>
        </div>

    </div>
</section>

<section class="cta-band">
    <div class="container">
        <h2>Technology solutions for your London business</h2>
        <p>Tell us what you need and we'll put together the right solution.</p>
        <a href="/signup" class="btn btn--primary">
            Get Started
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
