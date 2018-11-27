<?php
/**
 * Patient Tracker (Patient Flow Board)
 *
 * This program displays the information entered in the Calendar program ,
 * allowing the user to change status and view those changed here and in the Calendar
 * Will allow the collection of length of time spent in each status
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * @author  Terry Hill <terry@lilysystems.com>
 * @author  Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2015-2017 Terry Hill <terry@lillysystems.com>
 * @copyright Copyright (c) 2017 Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2017 Ray Magauran <magauran@medexbank.com>
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */
require_once("dbconfig.php");
require_once "../globals.php";


use OpenEMR\Core\Header;

// These settings are sticky user preferences linked to a given page.
// mdsupport - user_settings prefix
?>
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


img.logo{
    height: 22%;
    width: 18%;
    margin: auto;
}
.table {
    width: 50%;
    max-width: 100%;
    margin-bottom: 5px;
    margin: auto;
}
td {
    width: 50%;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}
th{
  width: 50%;
  border-top: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  
}

</style>

<body class="body_top">

<div class="container">
  <img src="/public/images/logo-full-con.png" class="img-responsive logo">          
  <table class="table" width="400px">
    <tbody>
      <tr style="background: #efebeb;">
        <th>Name of Business:</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Company Phone Number:</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Fax Number:</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Official Email Address:</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Official Website:</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Room/Floor:</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Building:</th>
        <td>Moe</td>
      </tr>

      <tr>
        <th>Street</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>District</th>
        <td>Moe</td>
      </tr>
      <tr>
        <th>Provider Category:</th>
        <td>Moe</td>
      </tr>

     
    </tbody>
  </table>

          
  
</div>

</html>
    
                   