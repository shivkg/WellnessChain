<?php
/**
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 * Copyright (C) 2011 Cassian LUP <cassi.lup@gmail.com>
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEMR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @author Cassian LUP <cassi.lup@gmail.com>
 * @link http://www.open-emr.org
 */

    //setting the session & other config options
    session_start();

    //don't require standard openemr authorization in globals.php
    $ignoreAuth = 1;

    //For redirect if the site on session does not match
    $landingpage = "index.php?site=".$_GET['site'];

    //includes
    require_once('../interface/globals.php');

    use OpenEMR\Core\Header;

    ini_set("error_log", E_ERROR || ~E_NOTICE);
    //exit if portal is turned off
if (!(isset($GLOBALS['portal_onsite_two_enable'])) || !($GLOBALS['portal_onsite_two_enable'])) {
    echo htmlspecialchars(xl('Patient Portal is turned off'), ENT_NOQUOTES);
    exit;
}

    // security measure -- will check on next page.
    $_SESSION['itsme'] = 1;
    //

    //
    // Deal with language selection
    //
    // collect default language id (skip this if this is a password update)
if (!(isset($_SESSION['password_update']) || isset($_GET['requestNew']))) {
    $res2 = sqlStatement("select * from lang_languages where lang_description = ?", array($GLOBALS['language_default']));
    for ($iter = 0; $row = sqlFetchArray($res2); $iter++) {
        $result2[$iter] = $row;
    }

    if (count($result2) == 1) {
        $defaultLangID = $result2[0]{"lang_id"};
        $defaultLangName = $result2[0]{"lang_description"};
    } else {
        //default to english if any problems
        $defaultLangID = 1;
        $defaultLangName = "English";
    }

  // set session variable to default so login information appears in default language
    $_SESSION['language_choice'] = $defaultLangID;
  // collect languages if showing language menu
    if ($GLOBALS['language_menu_login']) {
        // sorting order of language titles depends on language translation options.
        $mainLangID = empty($_SESSION['language_choice']) ? '1' : $_SESSION['language_choice'];
        if ($mainLangID == '1' && !empty($GLOBALS['skip_english_translation'])) {
            $sql = "SELECT * FROM lang_languages ORDER BY lang_description, lang_id";
            $res3=SqlStatement($sql);
        } else {
          // Use and sort by the translated language name.
            $sql = "SELECT ll.lang_id, " .
                 "IF(LENGTH(ld.definition),ld.definition,ll.lang_description) AS trans_lang_description, " .
                 "ll.lang_description " .
                 "FROM lang_languages AS ll " .
                 "LEFT JOIN lang_constants AS lc ON lc.constant_name = ll.lang_description " .
                 "LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND " .
                 "ld.lang_id = ? " .
                 "ORDER BY IF(LENGTH(ld.definition),ld.definition,ll.lang_description), ll.lang_id";
            $res3=SqlStatement($sql, array($mainLangID));
        }
        for ($iter = 0; $row = sqlFetchArray($res3); $iter++) {
            $result3[$iter] = $row;
        }
        if (count($result3) == 1) {
          //default to english if only return one language
            $hiddenLanguageField = "<input type='hidden' name='languageChoice' value='1' />\n";
        }
    } else {
        $hiddenLanguageField = "<input type='hidden' name='languageChoice' value='".htmlspecialchars($defaultLangID, ENT_QUOTES)."' />\n";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo xlt('Patient Portal Register'); ?></title>
    <?php
        $css = $GLOBALS['css_header'];
        $GLOBALS['css_header'] = "";
        Header::setupHeader(['datetime-picker']);
        //$GLOBALS['css_header'] = $css;
    ?>
    <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery.gritter-1-7-4/js/jquery.gritter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery.gritter-1-7-4/css/jquery.gritter.css" />
    <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/emodal-1-2-65/dist/eModal.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/base.css?v=<?php echo $v_js_includes; ?>" />
    
    <link rel="stylesheet" type="text/css" href="assets/css/patient_login.css?v=<?php echo $v_js_includes; ?>" />
<script type="text/javascript">
    function process() {
        if (!(validate())) {
            alert ('<?php echo addslashes(xl('Field(s) are missing!')); ?>');
            return false;
        }
    }
    function validate() {
            var pass=true;
        if (document.getElementById('uname').value == "") {
        document.getElementById('uname').style.border = "1px solid red";
                pass=false;
        }
        if (document.getElementById('pass').value == "") {
        document.getElementById('pass').style.border = "1px solid red";
                pass=false;
        }
            return pass;
    }
    function process_new_pass() {
        if (!(validate_new_pass())) {
            alert ('<?php echo addslashes(xl('Field(s) are missing!')); ?>');
            return false;
        }
        if (document.getElementById('pass_new').value != document.getElementById('pass_new_confirm').value) {
            alert ('<?php echo addslashes(xl('The new password fields are not the same.')); ?>');
            return false;
        }
        if (document.getElementById('pass').value == document.getElementById('pass_new').value) {
            alert ('<?php echo addslashes(xl('The new password can not be the same as the current password.')); ?>');
            return false;
        }
    }

    function validate_new_pass() {
        var pass=true;
        if (document.getElementById('uname').value == "") {
            document.getElementById('uname').style.border = "1px solid red";
            pass=false;
        }
        if (document.getElementById('pass').value == "") {
            document.getElementById('pass').style.border = "1px solid red";
            pass=false;
        }
        if (document.getElementById('pass_new').value == "") {
            document.getElementById('pass_new').style.border = "1px solid red";
            pass=false;
        }
        if (document.getElementById('pass_new_confirm').value == "") {
            document.getElementById('pass_new_confirm').style.border = "1px solid red";
            pass=false;
        }
        return pass;
    }
</script>


</head>
<body class="skin-blue" id="particles-js">
<br><br>
<div class="container">
<style>
.formbg{  width:100%; max-width:600px; margin:0 auto; }
.full{ width:100%; float:left;}
.formsec{background-color:rgba(0,0,0,0.6); margin-bottom:50px; padding:20px; width:100%; float:left; border-radius:5px; color:#fff;}
.formsec h2{     text-align: center;
    font-size: 22px;
    padding-bottom: 30px;}
.formsec h3{ font-size:20px;}
.formsec label{ font-weight:normal; width:100%;}
.formsec small{ display:block; font-size:11px;}
input.adinput{ padding:7px; width:100%;}
input.locate{padding:7px; max-width:80px;}
.fild2, .fild3{ margin:0; padding:0; list-style:none; width:100%; float:left;}
.fild3 li, .fild2 li{ padding-bottom:10px;}
.fild3 li:first-child{ font-size:16px;}
.fild2 li{ width:50%; float:left;}
.fild3 li label{ margin-bottom:0;}
.fild2 li:nth-child(2n){  padding-left:10px;}
.numbs{ position:relative;}
.numbs span{ position:absolute; left:8px; top:8px}
.numbs input{ padding-left:50px;}
.btnsubmit{ background:#2486b4; border-color:#2486b4;}
.btnsubmit:hover{ background:#1050b6;}
ul.check_box li {
    display: inline-block;
    width: 48%;
    padding: 10px;
}
input[type="checkbox"] {
    height: 15px;
    width: 15px;
}
ul.check_box li span {
    padding: 0px 10px;
    font-size: 14px;
}

ul.check_box {
    margin-left: 0px !important;
    padding-left: 0;
    margin-bottom: 30px;
}
input.locate {
    background: transparent;
    margin-left: 10px;
}
.locate::-webkit-inner-spin-button, 
.locate::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}



</style>

<?php

if (isset($_POST['sub']))
{
     $name =$_POST['nameofbussiness'];
     $CompanyPhone=$_POST['phone'];
     $FaxNumber=$_POST['fax'];
     $OfficialEmailAddress=$_POST['email1'];
     $OfficialWebsite=$_POST['website'];
     $RoomFloor=$_POST['room'];
     $Building=$_POST['building'];
     $Street=$_POST['street'];
     $District=$_POST['district'];
     $Providercat=implode(',', $_POST['vehicle1']);
    
     $NumberofAdditional=$_POST['location'];
     $FullName = $_POST['Fname'];
     $Post=$_POST['post'];
     $IDNo=$_POST['idno'];
     $MobileNo=$_POST['mobile'];
     $UserEmailId=$_POST['email'];
     $OfficialWebsite=$_POST['website1'];







            $to = 'shivk.gts@gmail.com';
            $subject = 'Wellnesschain Provider Registration';
            $from = 'noreply@wellnesschain.io';
             
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
             
            // Create email headers
            $headers .= 'From: '.$user_email."\r\n".
                'Reply-To: '.$user_email."\r\n" .
                'X-Mailer: PHP/' . phpversion();
             
// Compose a simple HTML email message

                $message = '
                <html>
                <title>Sahin</title>
                <head>
                <style>

table {
    box-shadow:0 0 11px #090909;border: 1px solid #ccc;width: 550px;
    
}
td {
    text-align: left;
    padding: 6px 10px;
    width: 50%;
    border-top: 1px solid #ccc;
    
}
th{
  text-align: left;
  padding: 6px 10px;
  width: 50%;
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  
  
}
                </style>
</head>
                <body>
                <h2 style="padding-left: 22%;"><a href="http://wellnesschain.io/">Wellnesschain</a></h2>
                <table>
                  
                      <tr style="background: #efebeb; border: 1px solid #ccc;">
                        <th>Name of Business:</th>
                        <td>'.$name.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Company Phone Number:</th>
                        <td>'.$CompanyPhone.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Fax Number:</th>
                        <td>'.$FaxNumber.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Official Email Address:</th>
                        <td>'.$OfficialEmailAddress.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Official Website:</th>
                        <td>'.$OfficialWebsite.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Room/Floor:</th>
                        <td>'.$RoomFloor.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Building:</th>
                        <td>'.$Building.'</td>
                      </tr>

                      <tr style="border: 1px solid #000;">
                        <th>Street</th>
                        <td>'.$Street.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>District</th>
                        <td>'.$District.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Provider Category:</th>
                        <td>'.$Providercat.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;"> 
                        <th>Number Of Additional</th>
                        <td>'.$NumberofAdditional.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Full Name</th>
                        <td>'.$FullName.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Post:</th>
                        <td>'.$Post.'</td>
                      </tr>
                       <tr style="border: 1px solid #000;">
                        <th>ID No.</th>
                        <td>'.$IDNo.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Mobile:</th>
                        <td>'.$MobileNo.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>User Email.</th>
                        <td>'.$UserEmailId.'</td>
                      </tr>
                      <tr style="border: 1px solid #000;">
                        <th>Official Website:</th>
                        <td>'.$OfficialWebsite.'</td>
                      </tr>
                  </table>
                   </body></html>';

 
// Sending email
if(mail($to, $subject, $message, $headers)){
    echo "<script>alert('Thanks for registration, You will receive an email for further process in next 48 Hrs. Please follow the instruction as stated in the email for further processing')</script>";
    echo '<script>window.location = "http://13.251.197.241/interface/login/home.php";</script>';
} else{
    echo 'Unable to send email. Please try again.';
}
}
?>






<div class="formbg">
   <form  class="formsec" action="" method="POST" onsubmit="return process()">
    <div class="center-block" style="max-width:400px">
                           <img class="img-responsive center-block login_logo" src="/public/images/login_logo.png">
                        </div>
        <h2> Healthcare Provider Registration Form</h2>
<h3>PART - 1 Healthcare Provider (HCP) Information</h3>
<label>Name of Business / Corporation <input class="adinput" type="text" name="nameofbussiness" /></label>


<ul class="fild2">
	<li><span>Company Phone Number</span> <label class="numbs"><input class="adinput" type="text" name="phone" required="" /></label></li>
    <li><span>Fax Number</span> <label class="numbs"><input class="adinput" type="text" name="fax" /></label></li>
    <li><span>Official Email Address</span> <label><input class="adinput" type="text" name="email1" required="" /></label></li>
    <li><span>Official Website</span> <label><input class="adinput" type="text" name="website" /></label> </li>
</ul>

<ul class="fild3">
<li>Address of healthcare service location and major healthcare provider</li>
<li><label>Room/Floor</label> <input class="adinput" type="text" name="room" /></li>
<li><label>Building</label> <input class="adinput" type="text" name="building" /></li>
<li><label>Street</label> <input class="adinput" type="text" name="street" /></li>
<li><label>City</label> <input class="adinput" type="text" name="district" required="" /></li>
<div class="full">
<h3>Provider Category</h3>
</div>
<ul class="check_box">
    <li><input type="checkbox" name="vehicle1[]" value="General Hospital"><span>General Hospital</span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Specialist "><span>Specialist Hospital</span></li>
        <li><input type="checkbox" name="vehicle1[]" value="Government Hospital"><span>Government Hospital</span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Rehabilitation Centre"><span>Rehabilitation Centre</span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Dental Hospital"><span>Dental Hospital</span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Laboratory"><span>Laboratory </span></li>
        <li><input type="checkbox" name="vehicle1[]" value="Radiology"><span>Radiology </span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Pharmacy"><span>Pharmacy</span></li>
        <li><input type="checkbox" name="vehicle1[]" value="Occupational Therapy"><span>Occupational Therapy</span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Physiotherapy"><span>Physiotherapy</span></li>
        <li><input type="checkbox" name="vehicle1[]" value="Optometry"><span>Optometry</span></li>
    <li><input type="checkbox" name="vehicle1[]" value="Medical Insurance"><span>Medical Insurance</span></li>
</ul>
<li>Number of Additional Healthcare Service Location<input class="locate" type="number" name="location" /> <small>(Please Complete and submit Additional Healthcare Service Location form)</small></li>
</ul>

<div class="full">
<h3>PART - 2 Authorised Person and User Information</h3>
<p> Authorised Person</p>
</div>
<ul class="fild2">	
    <li><span>Full Name</span> <label><input class="adinput" type="text" name="Fname" required="" /></label></li>
    <li><span>Post</span> <label><input class="adinput" type="text" name="post" /></label> </li>
    <li><span>ID No.</span> <label><input class="adinput" type="text" name="idno" /></label></li>
    <li><span>Mobile No.</span> <label><input class="adinput" type="text" name="mobile" required="" /></label> </li>
    <li><span>Email Address</span> <label><input class="adinput" type="text" name="email" required="" /></label></li>
    <li><span>Official Website</span> <label><input class="adinput" type="text" name="website1" /></label> </li>
</ul>

<input type="submit" name="sub" value="Submit" class="btn btn-success btnsubmit pull-right">
    </form>
    </div>
</div><!-- container -->


</body>
</html>
