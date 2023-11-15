<?php
//debug
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required
//debug

session_start();
if (isset($_SESSION['myusername'])) {

?>

    <html>

    <body>
        Login Successful
    </body>

    </html>

<?php
}
?>