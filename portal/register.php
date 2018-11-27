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
.formsec h2{     text-transform: uppercase;
    font-size: 22px;
    text-align: center;
    padding-bottom: 30px;}
    input[type="password"] {
    background-color: rgba(0,0,0,0.6) !important;
    border: 1px solid #ccc;
    padding: 18px;
}
.formsec h3{ font-size:20px;}
.formsec label{ font-weight:normal; width:100%;}
.formsec small{ display:block; font-size:11px;}
input.adinput{ padding:7px; width:100%;}
input.locate{padding:7px; max-width:120px;}
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
p.check_box {
    width: 50%;
    float: left;
    margin-top: 25px;
    margin-bottom: 0;
}
select {
    width: 100%;
    height: 35px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    -webkit-appearance: menulist;
}
textarea {
    width: 100%;
    background: rgba(0,0,0,0.6);
}
input[type="checkbox"] {
    width: 35px;
    height: 20px;
    background: rgba(0,0,0,0.6);
}
</style>
<div class="formbg">
   <form  class="formsec" action="" method="POST" onsubmit="return process()">
        <h2>Healthcare Provider Registration From</h2>

<label>User Name<input class="adinput" type="text" name="room" /></label>


<ul class="fild2">
	<li><span>Password</span> <label class="numbs"><input class="adinput" type="password" name="password" /></label></li>
    <li><span>Re-type Password</span> <label class="numbs"><input class="adinput" type="password" name="password" /></label></li>
    <li><span>First Name</span> <label><input class="adinput" type="text" name="fname" /></label></li>
    <li><span>Middle Name</span> <label><input class="adinput" type="text" name="mname" /></label> </li>
    <li><span>Last Name</span> <label><input class="adinput" type="text" name="lname" /></label> </li>
    <li><label>
        <p class="check_box">Provider:<input type="checkbox" name="vehicle1" value="Bike"> </p><p class="check_box">Calendar:
  <input type="checkbox" name="vehicle2" value="Car"> </p>
    </label> </li>


<li><span>Default Facility:</span> <label><select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label></li>
 <li><span>Federal Tax ID:</span> <label><input class="adinput" type="text" name="lname" /></label> </li>

 <li><span> Federal Drug ID:</span> <label><input class="adinput" type="text" name="fname" /></label></li>
 <li><span>UPIN:</span> <label><input class="adinput" type="text" name="lname" /></label> </li>
 <li><span>See Authorizations:</span> <label><select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label></li>
 <li><span>NPI:</span> <label><input class="adinput" type="text" name="lname" /></label> </li>

 <li><span> Job Description:</span> <label><input class="adinput" type="text" name="fname" /></label></li>

 <li><span>Provider Type:</span>
  <label><select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label> </li>
<ul class="fild2">  

    <li><span>Main Menu Role:</span> <label><select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label></li>
    <li><span>Patient Menu Role:</span> <label><select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label> </li>
     <li><span>Taxonomy:    </span> <label><input class="adinput" type="text" name="email" /></label></li>
    <li><span>State License Number:</span> <label><input class="adinput" type="text" name="website" /></label> </li>
    <li><span>NewCrop eRX Role:</span> <label><select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label> </li>
<li><span>Weno Provider ID:</span> <label><input class="adinput" type="text" name="website" /></label> </li>
</ul>
<label>User Name<select class="adinput">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select></label>
<label>Additional Info:<textarea rows="4" cols="50">

</textarea></label>


</ul>








<button class="btn btn-success btnsubmit pull-right" type="submit">Submit</button>
    </form>
    </div>
</div><!-- container -->


</body>
</html>
