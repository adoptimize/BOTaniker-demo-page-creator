<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willkommen bei Die BOTaniker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="content-container">
        <header class="header">
            <div class="logo-container">
                <img src="cropped-diebotaniker_logo_tsp_pure.png" alt="Die Botaniker Logo" class="header-logo">
                <span class="logo-text"><?php include('text-logo.txt'); ?></span>
            </div>
        </header>

        <main class="main-content">
            <div class="area-a">
                <div class="welcome-box">
                    <img src="kundenlogo.png" alt="Kleines Logo">
                    <h1><?php include('text-welcome.h1.txt'); ?></h1>
                    <p><?php include('text-text.p.txt'); ?></p>
                </div>
            </div>

            <div class="area-b">
                <div id="iframe-placeholder" class="iframe-container">
                    <iframe src="<?php include('chatbot-url.url'); ?>" allowfullscreen></iframe>
                </div>

            </div>
        </main>

        <footer class="footer">
            <div class="logo-container">
                <img src="cropped-diebotaniker_logo_tsp_pure.png" alt="Die Botaniker Logo" class="footer-logo">
                <span class="logo-text">Die BOTaniker</span>
            </div>
            <p>&copy; <?= date("Y"); ?> Die BOTaniker<br><a href="https://www.diebotaniker.de/impressum/" target="dieBotaniker">Impressum</a> <a href="https://www.diebotaniker.de/datenschutz/" target="dieBotaniker">Datenschutz</a></p>
        </footer>
    </div>

</body>
</html>
