<?php
// Copy this file to config.php and fill in your real SMTP credentials.
// config.php is gitignored and should never be committed.
return [
    'smtp_host'      => 'smtp.example.com',   // e.g. mail.yourdomain.com
    'smtp_port'      => 587,                   // 587 (TLS) or 465 (SSL)
    'smtp_username'  => 'your@email.com',
    'smtp_password'  => 'yourpassword',
    'smtp_from'      => 'your@email.com',
    'smtp_from_name' => 'Matrix Technical Services',
    'mail_to'        => 'your@email.com',      // Where contact form emails are delivered
    'smtp_secure'    => 'tls',                 // 'tls' or 'ssl'
];
