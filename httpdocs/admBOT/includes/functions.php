<?php 
    /* functions for the admBOT 
    * Mon Aug 11 10:55:10 AM CEST 2025 Mathias E. Koch  <mk@adoptimize.de>
    * 
    */


    /* remove all chars which are not characters 
    * so that a valid directory name can be created
    *
    * @param string     $input
    * @return string
    */
    function createDirectoryName($string = '') 
    {
        if(empty($string))  {
            return false;
        }

        $clean = preg_replace('/[^a-zA-Z]/', ' ', $string);
        $parts = array_filter(explode(' ', strtolower($clean)));
        $result = '';

        foreach ($parts as $part) {
            $result .= ucfirst($part);
        }
        return $result;
    } // createDirectoryName



    /* Hashes a user's password and saves it to a .htpasswd file.
    * returns the location of the passwd File
    *
    * @param string $folder the folder where the file is created.
    * @param string $pass The plain-text password.
    * @param string $user The username.
    * @return string 
    */
    function createPasswordHashAndFile($folder, $pass, $user) 
    {
        // crypt psw
        $pswHash = password_hash($pass, PASSWORD_DEFAULT);
        // save psw
        $htpasswdFile = $folder.'/.htpasswd';
        file_put_contents($htpasswdFile, $user.':'.$pswHash.PHP_EOL);
        return $htpasswdFile;
    } // createPasswordHashAndFile



    /* Creates and configures a .htaccess file for basic authentication.
    *
    * @param string $folder The directory name to create the .htaccess file in.
    * @param string $passWdFileLocation The location of the .htpasswd file.
    * @return void
    */
    function createHtaccess($folder, $passWdFileLocation) 
    {
        $htaccessFile   = $folder.'/.htaccess';
        $folder         = str_replace('../', '', $folder);
        $location       = $_SERVER['DOCUMENT_ROOT'].'/'.$folder;
        $htaccess       = 'AuthType Basic
AuthName "Bitte verwenden Sie den Benutzernamen und das Passwort, welches Ihnen gesendet wurde"
AuthUserFile '.$location.'/.htpasswd
Require valid-user
php_flag log_errors on
php_value error_log /usr/home/diebotan/public_html/diebotaniker.de/PHP_error.log
';
        file_put_contents($htaccessFile, $htaccess.PHP_EOL);

    } // createHtaccess



    /* Creates a text file with the chatbot's URL.
    *
    * @param string $folder The directory name where the file will be created.
    * @param string $chatBotUrl The URL of the chatbot to be saved.
    * @return void
    */
    function createChatBotUrlTxtFile($folder, $chatbotUrl)
    {
        $chatbotUrlFile = $folder.'/chatbot-url.url';
        file_put_contents($chatbotUrlFile, trim($chatbotUrl));
    } // createChatBotUrlTxtFile

?>
