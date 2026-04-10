<?php
// $current_page should be set by the including page: 'home', 'services', 'contact'
$current_page = $current_page ?? 'home';

function nav_class(string $page, string $current): string {
    return 'navbar__link' . ($page === $current ? ' active' : '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Matrix Technical Services — expert connectivity, managed networks, EPOS, payments and IT services.') ?>">
    <title><?= htmlspecialchars($page_title ?? 'Matrix Technical Services') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='6' fill='%231e88e5'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='system-ui,sans-serif' font-weight='900' font-size='18' fill='white'>M</text></svg>">
</head>
<body>

<header class="navbar">
    <div class="container">
        <div class="navbar__inner">

            <!-- Logo -->
            <a href="/" class="navbar__logo" aria-label="Matrix Technical Services — Home">
                <div class="navbar__logo-icon">MTS</div>
                <div class="navbar__logo-text">
                    Matrix Technical Services
                    <span>Professional IT Solutions</span>
                </div>
            </a>

            <!-- Mobile toggle -->
            <button class="navbar__toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Navigation -->
            <nav class="navbar__nav" role="navigation" aria-label="Main navigation">

                <a href="/" class="<?= nav_class('home', $current_page) ?>">Home</a>

                <!-- Services dropdown -->
                <div class="navbar__dropdown <?= $current_page === 'services' ? 'active' : '' ?>">
                    <a href="/services"
                       class="navbar__link navbar__dropdown-toggle <?= $current_page === 'services' ? 'active' : '' ?>"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Services
                        <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </a>

                    <div class="navbar__dropdown-menu" role="menu">
                        <a href="/services#connectivity" role="menuitem">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                            Connectivity
                        </a>
                        <a href="/services#managed-networks" role="menuitem">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="2" width="6" height="6" rx="1"/><rect x="16" y="2" width="6" height="6" rx="1"/><rect x="9" y="16" width="6" height="6" rx="1"/>
                                <path d="M5 8v4h14V8M12 12v4"/>
                            </svg>
                            Managed Networks
                        </a>
                        <a href="/services#epos-systems" role="menuitem">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                            </svg>
                            EPOS Systems
                        </a>
                        <a href="/services#payments" role="menuitem">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                            Payments
                        </a>
                        <a href="/services#it-services" role="menuitem">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><polyline points="8 21 12 17 16 21"/>
                            </svg>
                            IT Services
                        </a>
                    </div>
                </div>

                <a href="/contact" class="<?= nav_class('contact', $current_page) ?>">Contact</a>

            </nav>
        </div>
    </div>
</header>
