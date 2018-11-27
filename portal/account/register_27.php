<?php
/**
 * Portal Registration Wizard
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * @author  Jerry Padgett <sjpadgett@gmail.com>
 * @copyright Copyright (c) 2017-2018 Jerry Padgett <sjpadgett@gmail.com>
 * @license https://www.gnu.org/licenses/agpl-3.0.en.html GNU Affero General Public License 3
 */
require_once("dbconfig.php");
require_once('../../emr/library/authentication/password_hashing.php');

session_start();
ob_start();
session_regenerate_id(true);

unset($_SESSION['itsme']);
$_SESSION['patient_portal_onsite_two'] = true;
$_SESSION['authUser'] = 'portal-user';
$_SESSION['pid'] = true;
$_SESSION['register'] = true;

$_SESSION['site_id'] = isset($_SESSION['site_id']) ? $_SESSION['site_id'] : 'default';
$landingpage = "index.php?site=" . $_SESSION['site_id'];

$ignoreAuth_onsite_portal_two = true;

require_once("../../interface/globals.php");


$res2 = sqlStatement("select * from lang_languages where lang_description = ?", array(
    $GLOBALS['language_default']
));
for ($iter = 0; $row = sqlFetchArray($res2); $iter ++) {
    $result2[$iter] = $row;
}
if (count($result2) == 1) {
    $defaultLangID = $result2[0]{"lang_id"};
    $defaultLangName = $result2[0]{"lang_description"};
} else {
    // default to english if any problems
    $defaultLangID = 1;
    $defaultLangName = "English";
}

if (! isset($_SESSION['language_choice'])) {
    $_SESSION['language_choice'] = $defaultLangID;
}
// collect languages if showing language menu
if ($GLOBALS['language_menu_login']) {
    // sorting order of language titles depends on language translation options.
    $mainLangID = empty($_SESSION['language_choice']) ? '1' : $_SESSION['language_choice'];
    if ($mainLangID == '1' && ! empty($GLOBALS['skip_english_translation'])) {
        $sql = "SELECT * FROM lang_languages ORDER BY lang_description, lang_id";
        $res3 = SqlStatement($sql);
    } else {
        // Use and sort by the translated language name.
        $sql = "SELECT ll.lang_id, " . "IF(LENGTH(ld.definition),ld.definition,ll.lang_description) AS trans_lang_description, " . "ll.lang_description " .
             "FROM lang_languages AS ll " . "LEFT JOIN lang_constants AS lc ON lc.constant_name = ll.lang_description " .
             "LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND " . "ld.lang_id = ? " .
             "ORDER BY IF(LENGTH(ld.definition),ld.definition,ll.lang_description), ll.lang_id";
        $res3 = SqlStatement($sql, array(
            $mainLangID
        ));
    }

    for ($iter = 0; $row = sqlFetchArray($res3); $iter ++) {
        $result3[$iter] = $row;
    }

    if (count($result3) == 1) {
        // default to english if only return one language
        $hiddenLanguageField = "<input type='hidden' name='languageChoice' value='1' />\n";
    }
} else {
    $hiddenLanguageField = "<input type='hidden' name='languageChoice' value='" . htmlspecialchars($defaultLangID, ENT_QUOTES) . "' />\n";
}

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo xlt('New Patient'); ?> | <?php echo xlt('Register'); ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta name="description" content="">

<link href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker-2-5-4/build/jquery.datetimepicker.min.css">
<link href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="./../assets/css/register.css" rel="stylesheet" type="text/css" />

<script src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-min-3-1-1/index.js" type="text/javascript"></script>

<script src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-datetimepicker-2-5-4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/emodal-1-2-65/dist/eModal.js"></script>

</head>

<body class="skin-blue" id="particles-js">
    <div class="regi_bg">
    <div class="container">
    <?php
            if(isset($_POST['sub'])){
             $patient_id = "WNCMRN00".$PatientId.time();
              
                    $fname=$_POST['fname']; 
                    $mname =$_POST['mname'];
                    $lname = $_POST['lname'];
                    $DOB = $_POST['dob'];
                    $email = $_POST['emailInput'];
                   $hash= oemr_password_hash($_POST['password']);

                 $insertUserSQL =
            "insert into patient_data (patient_id,fname,mname,lname,DOB,email) 
            values('$patient_id','$fname','$mname','$lname','$DOB','$email')";  

            $run= $con->query($insertUserSQL);

               if($run){
                    $P_get =
                "insert into patient_access_onsite (portal_username,portal_email,portal_pwd) values('$patient_id','  $email','$hash')";  

                $result=$con->query($P_get);

               }  
           
             echo "<script>alert('success.')</script>";
                echo '<script>window.location = "http://13.251.197.241/portal/";</script>';


                         

                   

              
                


            }

?>





       <form class="form-inline" id="startForm" role="form" action="" method="post">
            <input type="hidden" name="action" value="get_newpid">
            <div class="row">
                <div class="col-xs-12 col-md-7 col-md-offset-3 text-center">
                    <fieldset>
                        <legend class="bg-primary">Register</legend>
                        <div class="well">
                                                                        
                                <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group inline">
                                        <label class="control-label" for="fname">First</label>
                                        <div class="controls inline-inputs">
                                            <input type="text" class="form-control" name="fname" required="" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="form-group inline">
                                        <label class="control-label" for="mname">Middle</label>
                                        <div class="controls inline-inputs">
                                            <input type="text" class="form-control" name="mname" placeholder="Full or Initial">
                                        </div>
                                    </div>
                                    <div class="form-group inline">
                                        <label class="control-label" for="lname">Last Name</label>
                                        <div class="controls inline-inputs">
                                            <input type="text" class="form-control" name="lname" required="" placeholder="Enter Last">
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="control-label" for="dob">Birth Date</label>
                                <div class="controls inline-inputs">
                                    
                                        <input name="dob" type="text" style="width: 100%" required="" class="form-control datepicker" placeholder="YYYY-MM-DD">
                                    
                                </div>
                            </div>
                           
                                <div class="col-sm-6 form-group">
                                    <label class="control-label" for="email">Enter E-Mail Address</label>
                                    <div class="controls inline-inputs">
                                        <input name="emailInput" type="email" class="form-control" style="width: 100%" required="" placeholder="Enter email address to receive registration." maxlength="100">
                                    </div>
                                </div>
                            </div>



                          <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="control-label" for="pass">Password</label>
                                <div class="controls inline-inputs">
                                    
                                        <input name="password" type="password" style="width: 100%" required="" class="form-control" placeholder="Password">
                                    
                                </div>
                            </div>
                           
                                <div class="col-sm-6 form-group">
                                    <label class="control-label" for="cpass">Conform Password</label>
                                    <div class="controls inline-inputs">
                                        <input name="cpassword" type="password" class="form-control" style="width: 100%" required="" placeholder="Conform Password" maxlength="100">
                                    </div>
                                </div>
                            </div>




                        </div>
                        <input type="submit" name="sub" class="btn btn-primary nextBtn btn-sm pull-right" value="Submit">
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/particles.js?v=<?php echo $v_js_includes; ?>"></script>

     <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/app.js?v=<?php echo $v_js_includes; ?>"></script>
</body>
</html>
