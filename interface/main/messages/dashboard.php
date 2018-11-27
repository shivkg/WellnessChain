<?php
/**
 * Message and Reminder Center UI
 * 2013/02/08 Minor tweaks by EMR Direct to allow integration with Direct messaging
 * 2013-03-27 by sunsetsystems: Fixed some weirdness with assigning a message recipient,
 * and allowing a message to be closed with a new note appended and no recipient.
 * @Package OpenEMR
 * @link http://www.open-emr.org
 * @author OpenEMR Support LLC
 * @author Roberto Vasquez robertogagliotta@gmail.com
 * @author Rod Roark rod@sunsetsystems.com
 * @author Brady Miller brady.g.miller@gmail.com
 * @author Ray Magauran magauran@medfetch.com
 * @copyright Copyright (c) 2010 OpenEMR Support LLC
 * @copyright Copyright (c) 2017 MedEXBank.com
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */
require_once("dbconfig.php");
require_once("../../globals.php");
require_once("$srcdir/pnotes.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/log.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/gprelations.inc.php");
require_once "$srcdir/user.inc";
require_once("$srcdir/MedEx/API.php");

use OpenEMR\Core\Header;

$MedEx = new MedExApi\MedEx('MedExBank.com');

if ($GLOBALS['medex_enable'] == '1') {
    $logged_in = $MedEx->login();
    if ($_REQUEST['SMS_bot']) {
        $MedEx->display->SMS_bot($logged_in);
        exit();
    }
}

$setting_bootstrap_submenu = prevSetting('', 'setting_bootstrap_submenu', 'setting_bootstrap_submenu', ' ');
//use $uspfx as the first variable for page/script specific user settings instead of '' (which is like a global but you have to request it).
$uspfx = substr(__FILE__, strlen($webserver_root)) . '.';
$rcb_selectors = prevSetting($uspfx, 'rcb_selectors', 'rcb_selectors', 'block');
$rcb_facility = prevSetting($uspfx, 'form_facility', 'form_facility', '');
$rcb_provider = prevSetting($uspfx, 'form_provider', 'form_provider', $_SESSION['authUserID']);

if (($_POST['setting_bootstrap_submenu']) ||
    ($_POST['rcb_selectors'])) {
    // These are not form elements. We only ever change them via ajax, so exit now.
    exit();
}

?>
<html>
<head>
    
    <link rel="stylesheet" href="<?php echo $webroot; ?>/interface/main/messages/css/dash.css?v=<?php echo $v_js_includes; ?>" type="text/css">
    <!-- <link rel="stylesheet"  href="<?php echo $GLOBALS['web_root']; ?>/library/css/bootstrap_navbar.css?v=<?php echo $v_js_includes; ?>" type="text/css"> -->
     
    <?php Header::setupHeader(['datetime-picker', 'jquery-ui', 'jquery-ui-redmond', 'opener', 'moment', 'pure']); ?>

    <script>
        var xljs1 = '<?php echo xl('Preferences updated successfully'); ?>';
        var format_date_moment_js = '<?php echo attr(DateFormatRead("validateJS")); ?>';
        <?php require_once "$srcdir/restoreSession.php"; ?>
    </script>

    

    <link rel="shortcut icon" href="<?php echo $webroot; ?>/sites/default/favicon.ico" />

        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="MedEx Bank">
    <meta name="author" content="OpenEMR: MedExBank">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .btn {
            border: solid black 0.5pt;
            box-shadow: 3px 3px 3px #7b777760;
        }
        .ui-datepicker-year {
            color: #000;
        }
        .fa{
            font-size: 50px;
        }
        p.pat_data {
   font-size: 20px;
    color: #fff;
    padding: 40px 0;
    background: #627cb6;
    margin: 0 !important;
}

    </style>
<body>
    <section class="">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
     <div class="hospital clm">
         
            <p class="pat_data"> Pattient List</p><p>
            <i class="fa fa-user" aria-hidden="true"></i></p>
            <?php
                $GetProduct = "SELECT * FROM patient_data ";
                $RunProduct = $con->query($GetProduct);
                 $ProductRow = mysqli_num_rows($RunProduct);
                  
                   ?>
        <span><?php echo $ProductRow;?></span>
     </div>
            </div>
            <div class="col-md-3">
     <div class="hospital clm">
         
            <p>Hospital List</p><p>
            <i class="fa fa-hospital-o" aria-hidden="true"></i></p>
     <?php
        $Facility = "SELECT * FROM facility ";
        $RunFacility = $con->query($Facility);
         $FacilityRow = mysqli_num_rows($RunFacility);
           
           ?>
        <span><?php echo $FacilityRow;?></span>
     </div>
            </div>
            <div class="col-md-3">
     <div class="hospital clm">
        
            <p>Users List</p><p>
             <i class="fa fa-users" aria-hidden="true"></i></p>
            <?php
                $User = "SELECT * FROM users ";
                $RunUsers = $con->query($User);
                 $UserRow = mysqli_num_rows($RunUsers);
                   
                   ?>

        <span><?php echo $UserRow;?></span>
     </div>
            </div>
            <div class="col-md-3">
     <div class="hospital clm">
         
            <p>Users List</p><p>
            <i class="fa fa-users" aria-hidden="true"></i></p>
            <?php
                $User = "SELECT * FROM users ";
                $RunUsers = $con->query($User);
                 $UserRow = mysqli_num_rows($RunUsers);
                   
                   ?>

        <span><?php echo $UserRow;?></span>
     </div>
            </div>
        </div>
        </div>
        </section>









</body>
</html>
