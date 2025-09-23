<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/site.webmanifest">
    <?php if (isset($settings['base_font']) && $settings['base_font'] !== 'System'): ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($settings['base_font']) ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php endif; ?>

    <title><?= htmlspecialchars($title ?? 'Page'); ?> | <?= htmlspecialchars($settings['site_name'] ?? 'My App'); ?></title>

    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.css" integrity="sha512-kT0v1BxcibEO2Yc+6Z3W1gNsN+2cZ/U6uITqHhIJl8SAvt9vpO8llugdCPXA7cCnp8G1xbuSqHNMRaR3Zlz9yA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            /* Color Variables */
            --primary-color: <?= htmlspecialchars($settings['theme_color'] ?? '#007bff'); ?>;
            --primary-color-darker: <?= htmlspecialchars(adjustBrightness($settings['theme_color'] ?? '#007bff', -20)); ?>;

            /* Font Variable */
            --font-family-base: <?= getFontFamily($settings['base_font'] ?? 'System'); ?>;
        }
    </style>
</head>
<body class="<?= htmlspecialchars($settings['theme_mode'] ?? 'light') ?>-theme density-<?= htmlspecialchars($settings['layout_density'] ?? 'comfortable') ?>">

<div class="app-container">
    <main class="main-content-area">
        <?php if ($title == 'Login' || $title == 'Register'): ?>
        <div class="main-content-login">
            <?php else: ?>
            <div class="main-content">
                <?php endif; ?>
                <?php if (isset($user)): ?>
                <div class="page-header">
                    <div>
                        <span class="site-name">
                            <?= htmlspecialchars($settings['site_name'] ?? 'My App') ?>
                        </span>
                        <h1><?= htmlspecialchars($title ?? 'Dashboard'); ?></h1>
                    </div>

                    <div class="header-nav-actions">
                        <nav class="main-menu">
                            <ul>
                                <li><a href="/" class="<?= ($title == 'Dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                            </ul>
                        </nav>

                        <?php if (isset($user)): ?>
                            <div class="profile-dropdown">
                                <button type="button" class="profile-dropdown-toggle" id="profile-dropdown-toggle" aria-label="Account Menu">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                    </svg>
                                </button>
                                <div class="profile-dropdown-menu" id="profile-dropdown-menu">
                                    <div class="dropdown-header">
                                        <p class="dropdown-username"><?= htmlspecialchars($user->getFullName() ?? 'Guest User'); ?></p>
                                        <p class="dropdown-email"><?= htmlspecialchars($user->email ?? 'Guest User'); ?></p>
                                    </div>
                                    <a href="/profile" class="dropdown-item">My Profile</a>
                                    <a href="/settings" class="dropdown-item">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="/logout" class="dropdown-item dropdown-item-logout">Logout</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
        <?php endif; ?>