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
 *  along with this program.  If not, see <>.
 *
 * @package Wellness Chain
 * @author GTS Team
 * @author 
 * @link 
 */

    //setting the session & other config options
    session_start();
ob_start();
    //don't require standard Wellness Chain authorization in globals.php
    $ignoreAuth = 1;

    //For redirect if the site on session does not match
    $landingpage = "index.php?site=".$_GET['site'];

    //includes
    require_once('../interface/globals.php');
     require_once('../emr/library/authentication/password_hashing.php');

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
    <title><?php echo xlt('Patient Portal Login'); ?></title>
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
   /* function process() {
        if (!(validate())) {
            alert ('<?php// echo addslashes(xl('Field(s) are missing!')); ?>');
            return false;
        }
    }*/
    function validate() {
        var a = document.getElementById("pass").value;
        var b = document.getElementById("pass_new").value;
       /* if (document.getElementById('uname').value == "") {
            document.getElementById('uname').style.border = "1px solid red";
            alert("empty");
            return false;
        }
        if (document.getElementById('pass').value == "") {
            document.getElementById('pass').style.border = "1px solid red";
            alert("empty");
            return false;
        }
        if (document.getElementById('pass_new').value == "") {
            document.getElementById('pass_new').style.border = "1px solid red";
            alert("empty");
            return false;
        }*/
        if (a!=b) {
           alert("Passwords do no match");
           return false;
        }
    }
   /* function process_new_pass() {
        if (!(validate_new_pass())) {
            alert ('<?php //echo addslashes(xl('Field(s) are missing!')); ?>');
            return false;
        }
        if (document.getElementById('pass_new').value != document.getElementById('pass_new_confirm').value) {
            alert ('<?php //echo addslashes(xl('The new password fields are not the same.')); ?>');
            return false;
        }
        if (document.getElementById('pass').value == document.getElementById('pass_new').value) {
            alert ('<?php //echo addslashes(xl('The new password can not be the same as the current password.')); ?>');
            return false;
        }
    }*/

   /* function validate_new_pass() {
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
    }*/
</script>
</head>
<body class="skin-blue" id="particles-js">
<br><br>
<div class="container text-center">
    <?php if (isset($_SESSION['password_update']) || isset($_GET['password_update'])) {
        $_SESSION['password_update']=1;
        ?>
      
    <?php } elseif (isset($_GET['requestNew'])) { ?>
    
    <?php } else {
?>  <!-- Main logon -->


<?php

// $_SESSION['pwncid']=$_GET['id'];



if(isset($_POST['submit'])){
    session_start();

    $pids = $_GET['id'];
    $_SESSION['pwncid']=$pids;
    
   
    // echo $_SESSION['pwncid'];
    // die();


    $query = "SELECT * FROM patient_data where patient_id = '$pids'";

    $result = sqlStatement($query);  
    $email = $result->fields['email'];    
    $hash = oemr_password_hash($_POST['pass'], "$2a$05$Kd4WAB.lYG.tYa1tL3xxWc$");
    $insertUserSQL ="UPDATE patient_access_onsite SET portal_pwd='".$hash."',portal_salt='$2a$05$Kd4WAB.lYG.tYa1tL3xxWc$',portal_username='".$_POST['uname']."' , portal_email='".$email."' WHERE pid='".$result->fields['id']."'";
    if(sqlInsert($insertUserSQL)){
        $to = $result->fields['email'];
        $subject = "Password Successfully save on Wellnesschain";

        $message = "
        <html>
        <head>
        <title>Please Login</title>
        </head>
        <body>
        <p>http://13.251.197.241/portal/</p>
        <table>
        <tr>
        <th>User Name</th>
        <th>Email</th>
        <th>Password</th>
        </tr>
        <tr>
        <td>".$_POST['uname']."</td>
        <td>".$result->fields['email']."</td>
        <td>".$_POST['pass']."</td>
        </tr>
        </table>
        </body>
        </html>
        ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <noreply@wellnesschain.io>' . "\r\n";

        mail($to,$subject,$message,$headers);


        echo "<script>alert('Your Password have been sent on your email. Check your email inbox and also possibly your spam folder.')</script>";

        echo '<script>window.location = "http://13.251.197.241/portal/";</script>';

    }
     die("vijay2");

}

?>

    <div id="wrapper" class="row centerwrapper text-center">
    <img class="img-responsive center-block login_logo" src="<?php echo $GLOBALS['images_static_relative']; ?>/login_logo.png" />
    <form  class="form-inline text-center" action="#" method="post" onSubmit="return validate()">
        <div class="row">
                <div class="col-sm-12 text-center">
                    <fieldset>
                        <legend class="bg-primary"><h3><?php echo xlt('Create Password'); ?></h3></legend>
                        <div class="well">
                        <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group inline">
                                        <label class="control-label" for="uname"><?php echo xlt('Patient ID')?></label>
                                        <div class="controls inline-inputs">
                                            <input type="text" class="form-control" name="uname" id="uname" type="text" autocomplete="on" value="<?php 
                                            echo $_GET['id'];?>" readonly>
                                            
                                        </div>
                                                                      </div>
                                    <div class="form-group inline">
                                        <label class="control-label" for="pass"><?php echo xlt('Create Password')?></label>
                                        <div class="controls inline-inputs">
                                            <input class="form-control" name="pass" id="pass" type="password" required autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label class="control-label" for="pass_new"><?php echo xlt('Confirm Password')?></label>
                                    <div class="controls inline-inputs">
                                        <input class="form-control" name="pass_new" id="pass_new" type="password" required autocomplete="on">
                                    </div>
                                </div>
                            </div>
                       
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <input type="submit" class="btn btn-success confirm_btn" value="submit" name="submit" ><?php //echo xlt('Submit');?>
                        </div>


                    </fieldset>
                </div>
          </div>
            <?php if (!(empty($hiddenLanguageField))) {
                echo $hiddenLanguageField; } ?>
    </form>
    </div><!-- div wrapper -->
    <?php } ?> <!--  logon wrapper -->
</div><!-- container -->


<script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/particles.js?v=<?php echo $v_js_includes; ?>"></script>

     <script type="text/javascript">
    particlesJS("particles-js", {"particles":{"number":{"value":360,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});var count_particles, stats, update; stats = new Stats; stats.setMode(0); stats.domElement.style.position = 'absolute'; stats.domElement.style.left = '0px'; stats.domElement.style.top = '0px'; document.body.appendChild(stats.domElement); count_particles = document.querySelector('.js-count-particles'); update = function() { stats.begin(); stats.end(); if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) { count_particles.innerText = window.pJSDom[0].pJS.particles.array.length; } requestAnimationFrame(update); }; requestAnimationFrame(update);;
</script>

</body>
</html>
