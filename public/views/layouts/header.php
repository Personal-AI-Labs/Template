<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/site.webmanifest">

    <title><?php echo htmlspecialchars($title ?? 'Template'); ?></title>

    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.css" integrity="sha512-kT0v1BxcibEO2Yc+6Z3W1gNsN+2cZ/U6uITqHhIJl8SAvt9vpO8llugdCPXA7cCnp8G1xbuSqHNMRaR3Zlz9yA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

<div class="app-container">
    <main class="main-content-area">
        <?php if ($title == 'Login' || $title == 'Register'): ?>
        <div class="main-content-login">
        <?php else: ?>
        <div class="main-content">
        <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="page-header">
                <div>
                    <h1><?php echo htmlspecialchars($title ?? 'Dashboard'); ?></h1>
                </div>

                <div class="page-header-actions">
                        <a href="/logout" class="btn btn-logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                            </svg>
                            <span>Logout</span>
                        </a>
                </div>
            </div>
            <?php endif; ?>
