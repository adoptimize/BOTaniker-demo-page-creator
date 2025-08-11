<?php 

/* 
* the interface creates a webpage for new customers to test our bot
*  serveral jobs are done
* custom BASIC AUTH Created
* folder for the demo bot is created
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

#print_r($_POST); die;


/* this is the template which is used to create the customers welcome page
* it is out of reach for any body who has a ftp access, so that it wont get wrecked
*
* the editor need to edit the following files in the customers folder
*
*                    'text-logo.txt',
*                    'text-logo.txt',  
*                    'text-text.p.txt',  
*                    'text-welcome.h1.txt',
*/
$templateDir = '../../template/';

/* the directory where the new customer welcome page is created 
* its right below the subdomain main Dir
*/
$mainDir = '../';

/* default chatbot url */
$chatbotUrl = isset($_POST['chatbotUrl']) ? $_POST['chatbotUrl'] : 'https://ai-platform.kauz.ai/chat/main/?group=DieBOTaniker';



/* local functions */
require __DIR__.'/includes/functions.php';

/* PHP mail inclusion */
require __DIR__.'/PHPMailer-master/src/Exception.php';
require __DIR__.'/PHPMailer-master/src/PHPMailer.php';
require __DIR__.'/PHPMailer-master/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* the email send to */
$sendTo = 'appletree@diebotaniker.de';
$sendTo = 'adoptimizemk@gmail.com';


    if(isset($_POST) && !empty($_POST)) {

        $error = [];

        if(empty($_POST['user'])) {
            $error['user'] = 'Benutzernamen angeben!';
        } 
        if(empty($_POST['pass'])) {
            $error['pass'] = 'Passwort angeben!';
        } 

        if(empty($_POST['unternehmensname'])) {
            $error['pass'] = 'Ein Name für das Unternehmen um einen Ordner für den Demo-Bot zu kreieren wird benötigt!';
        } 


        if(empty($error)) {

            // create folderNamee from Unternehmensname
            $folder = createDirectoryName($_POST['unternehmensname']);
            $folder.= '_'.uniqid();
            $url    = 'https://'.$_SERVER['HTTP_HOST'].'/'.$folder;

            $filesToCopy = [
                    'bg_demobot.png',
                    'cropped-diebotaniker_logo_tsp_pure.png',
                    'kundenlogo.png',
                    'index.php',
                    'style.css',
                    'text-logo.txt',
                    'text-logo.txt',  
                    'text-text.p.txt',  
                    'text-welcome.h1.txt',
            ];
            mkdir($mainDir.$folder);

            foreach($filesToCopy AS $file) {
                copy($templateDir.$file, $mainDir.$folder.'/'.$file);
            }

            // htpasswd
            $passWdFileLocation = createPasswordHashAndFile($mainDir.$folder, $_POST['pass'], $_POST['user']);
            // htaccess
            createHtaccess($mainDir.$folder, $passWdFileLocation);
            // chatbotUrl
            createChatBotUrlTxtFile($mainDir.$folder, $chatbotUrl);

            // url auf die der Kunde zurueckgreift
            $url ='https://'. $_SERVER['HTTP_HOST'].'/'.$folder;


            // Erstellen einer PHPMailer-Instanz
            $mail = new PHPMailer(true);

            try {
                // Server-Einstellungen
                $mail->isSMTP();                                            // SMTP-Versand aktivieren
                $mail->Host       = 'web198.dogado.net';                       // SMTP-Server angeben
                $mail->SMTPAuth   = true;                                   // SMTP-Authentifizierung aktivieren
                $mail->Username   = 'mk@diebotaniker.de';                     // SMTP-Benutzername
                $mail->Password   = '1nloKt4&R_qkfQ!C';                               // SMTP-Passwort
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // TLS-Verschlüsselung aktivieren
                $mail->Port       = 587;                                    // TCP-Port für TLS

                // Empfänger
                $mail->setFrom('mk@diebotaniker.de', 'DEMO BOT');
                $mail->addAddress($sendTo, 'Appletree');

                // Inhalt
                $mail->isHTML(false);                                        // Als einfachen Text senden
                $mail->Subject = 'dieBOTaniker // DEMO-Seite v.0 erstellt';
                $mail->Body    = 'Eine Demo BOT - Seite wurde eingerichtet.

Die Wurzeln fest in das Erdreich druecken. Ein Kunde kommt!

Kunde: '.$_POST['unternehmensname'].'

Login Daten:
Benutzername: '.$_POST['user'].'
Passwort: '.$_POST['pass'].'
URL: '.$url.'

';

            $mail->send();
            echo 'Nachricht wurde gesendet';
        } catch (Exception $e) {
            echo "Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}";
        }
    }
}

    

    

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willkommen bei den BOTanikern</title>
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
                <div class="welcome-box" style="text-align:left">
                    <img src="kundenlogo.png" alt="Logo">
                    <h1>AdminBereich um einen Demo-Bot einzurichten</h1>
                    <h2>Beschreibung</h2>
                    <p><b>Name des Unternehmens:</b><br>Aus diesem Namen wird ein Ordner generiert in dem der DemoBot läuft. <small>Es werden sonderzeichen und Zahlen entfernt und ein valider Ordnername erstellt. Das kann ggf. etwas "komisch" aussehen. Ggf. deshalb gleich nur einen Namen eingeben. Die Verschlüsselung am Ende bleibt erhalten.</small></p>
                    <p><b>Benutzername:</b><br>F&uuml;r den Login auf der Demoseite</p>
                    <p><b>Passwort:</b><br>F&uuml;r den Login auf der Demoseite</p>
                    <p><b>ChatBOT URL:</b><br>Die URL die im aiStudio konfiguriert wurde.<br>Es sollte genügen, wenn das was hinter "?group=" eingetragen ist auszuwechseln. Je nach URL wird eine "Bubble" angezeigt, oder das Chatfenster. Im Kauz-Admin-Bereich wird in der Regel die Bubble als URL vorgegeben. Wohingegen die DEMO-Seiten für das bereits geöffnete Chat-Fenster ausgelegt sind.</p>

                </div>
            </div>

            <div class="area-b">
                <div id="iframe-placeholder" class="iframe-container">
                    <p>
                    <form name="demoAdm" method="POST">
                        <?php
                            if(!empty($error)) {
                                echo '<b style="color:red">';
                                echo join('<br>',$error);
                                echo '</b>';
                            }

                            

                            
                            $psw = '';

                            #echo '<input type="hidden" name="folder" value="'.$folder.'">';
                            #echo '<input type="hidden" name="url" value="'.$url.'">';
                        ?>  
                        <table style="text-align:left">

                        <tr>
                            <td valign="top" width="100%">Name des Unternehmens:</td>
                            <td valign="top"><input type="text" name="unternehmensname" value="<?php echo @$_POST['user'] ?>" /></td>
                        </tr>
                        <tr>
                            <td valign="top">Benutzername:</td>
                            <td valign="top"><input type="text" name="user" value="<?php echo @$_POST['user'] ?>" /></td>
                        </tr>
                        <tr>
                            <td valign="top">BPasswort für den Zugang:</td>
                            <td valign="top"><input type="text" name="pass" value="<?php echo @$_POST['pass'] ?>" /></td>
                        </tr>
                        <tr>
                            <td valign="top">ChatBOT URL aus dem<br>Kauz Backend:</td>
                            <td valign="top"><textarea name="chatbotUrl" rows="3" cols="60"><?php echo $chatbotUrl; ?></textarea></td>
                        </tr>
                        <tr>
                            <td valign="top" colspan="2"><hr></td>
                        </tr>
<?php 
 
                            if(isset($folder) && !empty($folder)) {
                                echo '<tr>
                                        <td valign="top"><h2>KUNDEN URL:</h2></td>
                                        <td valign="top"><h2><a href="'.$url.'" target="_blank">'.$url.'</a></h2>
                                            Wenn mehere Bots hintereinander angelegt werden, dann erscheint hier die des Vorgängers. 
                                            Nicht verwirren lasseen, <br>oder hier Kilicken: <button type="button" onclick="resetFormAndReload()">Reset & Reload</button>
                                        </td>
                                     </tr>';
                            } else {

                                echo '<tr>
                                        <td valign="top"><h2>KUNDEN URL:</h2></td>
                                        <td valign="top"><h2>Die URL wird nach dem Generieren des Demo-Bots angezeigt.</h2></td>
                                     </tr>';
                            } 
?>


                        </table>
                        <input type="submit">
<script>
  function resetFormAndReload() {
    // Reset the form to its initial state
    document.getElementById('demoAdm').reset();
    
    // Reload the entire page
    window.location.reload();
  }
</script>
                        
                    
                    </form>
                    </p>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="logo-container">
                <img src="cropped-diebotaniker_logo_tsp_pure.png" alt="Die Botaniker Logo" class="footer-logo">
                <span class="logo-text">die BOTaniker</span>
            </div>
            <p>&copy; 2025 dieBOTaniker&copy; <br><a href="https://www.diebotaniker.de/impressum/" target="dieBotaniker">Impressum</a> <a href="https://www.diebotaniker.de/datenschutz/" target="dieBotaniker">Datenschutz</a></p>
        </footer>
    </div>

</body>
</html>
