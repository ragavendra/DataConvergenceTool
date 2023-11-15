<?php
//debug
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required

include 'GlobalVariables.php';

//on pageload
session_start();

$tIdleTimeActual = time() - $_SESSION['timestamp'];

if (isset($_SESSION['myusername']) and ($tIdleTimeActual < $tIdleTimeSet)) {

    //update the time
    $_SESSION['timestamp'] = time();

?>

    <html>

    <head>

        <link rel="stylesheet" href="style3.css" type="text/css">
        <meta charset="utf-8" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css" />
        <script type="text/javascript" src="jquery.js"></script>
        <script type='text/javascript' src='jquery.autocomplete.js'></script>
        <link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
        <script>
            $().ready(function() {
                $("#Material").autocomplete("Material.php", {
                    width: 50,
                    matchContains: true,
                    selectFirst: false
                });
            });

            $().ready(function() {
                $("#RegisterGroup").autocomplete("RegisterGroup.php", {
                    width: 100,
                    matchContains: true,
                    selectFirst: false
                });
            });
        </script>

        <p class="home">
            <a href=dataconvergencetool.php>Home</A>
        </p>
        <p class="home">
            <a href=dataconvergencetoolAMS.php>ADCS-MDMS only</A>
        </p>
        <p class="logout">
            <a href=PasswordReset.php>Password Reset</A>
        </p>
        <p class="logout">
            <a href=logout.php>Log out</A>
        </p>
        <title>SMI Data Mining Tool - SMI BCHydro</title>
        <h1>SMI Data Mining Tool - SMI BCHydro (Beta)</h1>
    </head>

    <body>
        <p class="medium">
        <form name="dataconvergencetool" method="post" action="querytest.php" onsubmit="return validateForm()">

            <table border="0">
                <tr>
                    <td>Meter Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><input type="text" size="12" maxlength="12" name="MeterNumber"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 4613635, 461%</td>
                    <td>To: <input type="text" size="12" maxlength="12" name="MeterNumberTo"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>

                <tr>
                    <td>Installation: </td>
                    <td><input type="text" size="12" maxlength="12" name="Installation"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 316332</td>
                </tr>

                <tr>
                    <td><label for="Material">Meter Type: </td>
                    <td><input type="text" size="4" maxlength="4" name="Material" id="Material"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. P264</td>
                </tr>

                <tr>
                    <td>Register Group: </td>
                    <td><input type="text" size="8" maxlength="8" name="RegisterGroup" id="RegisterGroup"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. P264A_01</td>
                </tr>

                <tr>
                    <td>Rate Category: </td>
                    <td><input type="text" size="12" maxlength="12" name="RateCategory"></td>>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. BCRSE1101</td>
                </tr>

                <tr>
                    <td>Billing Class: </td>
                    <td><input type="text" size="12" maxlength="12" name="BillingClass"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. BCRS</td>
                </tr>

                <tr>
                    <td>MRUnit: </td>
                    <td><input type="text" size="12" maxlength="12" name="MRUnit"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. B055401B</td>
                </tr>

                <tr>
                    <td>Move In Date from: </td>
                    <td><input type="date" size="10" maxlength="10" name="MoveInDate"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 2012-12-31</td>
                    <td>To: <input type="date" size="10" maxlength="10" name="MoveInDateTo"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>

                <tr>
                    <td>Limit rows: </td>
                    <td><input type="number" size="6" maxlength="6" name="RowLimit"></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 123</td>
                </tr>


                <tr>
                    <td>Environment: </td>
                    <td><select name="Environment">
                            <option value="QA1">QA1</option>
                            <option value="STAGE">STAGE</option>
                            <option value="QA1_RT">QA1_RT</option>
                        </select></td>
                </tr>

                <tr>
                    <td>Load: </td>
                    <td>
                        <input type="hidden" name="MDMS" value="0" />
                        <input type="checkbox" name="MDMS" value="1" checked>MDMS<br>
                        <input type="hidden" name="ADCS" value="0" />
                        <input type="checkbox" name="ADCS" value="1" checked>ADCS
                    </td>
                </tr>

                <tr>
                    <td>Export as: </td>
                    <td><input type="radio" value="XLSX" name="XLSX">XLSX<br /><input type="radio" value="HTML" name="XLSX" checked="checked">HTML<br /><br /></td>
                </tr>

                <tr>
                    <td>
                        <input type="Submit" value="Submit">
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="reset" value="Clear Form">
                    </td>
                </tr>

        </form>
        </p>
        <tr>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                Last SAP QA1 Data Update: 15 July 2013 - 3.00 PM PST
                <script src="dataconvergencetool.js"></script>
            </td>
        </tr>
        </table>
        </br></br>
        <center>
            <td>
                Author - Ragavendra BN -
                <a href="mailto:user@afcemail.com?Subject=Data Convergence Tool" target="_top">
                    user@afcemail.com</a>
        </center>
        </td>
        </center>
        </br></br>
    </body>

    </html>

<?php
} else {
    session_destroy();
    session_unset();
    echo "Invalid session or Session timed out. Please login to access this page";

?>
    <br /><br />
    <a href=main_login.php>Login page</A>
<?php
}
?>