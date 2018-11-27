<?php
/**
 * Login screen.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @author  Brady Miller <brady.g.miller@gmail.com>
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @author  Scott Wakefield <scott.wakefield@gmail.com>
 * @author  ViCarePlus <visolve_emr@visolve.com>
 * @author  Julia Longtin <julialongtin@diasp.org>
 * @author  cfapress
 * @author  markleeds
 * @link    http://www.open-emr.org
 */



use OpenEMR\Core\Header;

$ignoreAuth=true;
require_once("../globals.php");

// mdsupport - Add 'App' functionality for user interfaces without standard menu and frames
// If this script is called with app parameter, validate it without showing other apps.
//
// Build a list of valid entries
$emr_app = array();
if ($GLOBALS['new_tabs_layout']) {
    $rs = sqlStatement(
        "SELECT option_id, title,is_default FROM list_options
			WHERE list_id=? and activity=1 ORDER BY seq, option_id",
        array ('apps')
    );
    if (sqlNumRows($rs)) {
        while ($app = sqlFetchArray($rs)) {
            $app_req = explode('?', trim($app['title']));
            if (! file_exists('../'.$app_req[0])) {
                continue;
            }

                $emr_app [trim($app ['option_id'])] = trim($app ['title']);
            if ($app ['is_default']) {
                $emr_app_def = $app ['option_id'];
            }
        }
    }
}

$div_app = '';
if (count($emr_app)) {
    // Standard app must exist
    $std_app = 'main/main_screen.php';
    if (!in_array($std_app, $emr_app)) {
        $emr_app['*OpenEMR'] = $std_app;
    }

    if (isset($_REQUEST['app']) && $emr_app[$_REQUEST['app']]) {
        $div_app = sprintf('<input type="hidden" name="appChoice" value="%s">', attr($_REQUEST['app']));
    } else {
        foreach ($emr_app as $opt_disp => $opt_value) {
            $opt_htm .= sprintf(
                '<option value="%s" %s>%s</option>\n',
                attr($opt_disp),
                ($opt_disp == $opt_default ? 'selected="selected"' : ''),
                text(xl_list_label($opt_disp))
            );
        }

        $div_app = sprintf(
            '
<div id="divApp" class="form-group">
	<label for="appChoice" class="control-label text-right">%s:</label>
    <div>
        <select class="form-control" id="selApp" name="appChoice" size="1">%s</select>
    </div>
</div>',
            xlt('App'),
            $opt_htm
        );
    }
}

?>
<html>
<head>
    <title>Wellness Chain Home</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <?php Header::setupHeader(['jquery-ui', 'jquery-ui-darkness']); ?>

    <link rel="stylesheet" href="../themes/home.css?v=<?php echo $v_js_includes; ?>" type="text/css">

    <link rel="shortcut icon" href="<?php echo $GLOBALS['images_static_relative']; ?>/favicon.ico" />

    

    <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/product_registration_service.js?v=<?php echo $v_js_includes; ?>"></script>
    <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/product_registration_controller.js?v=<?php echo $v_js_includes; ?>"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            init();

            var productRegistrationController = new ProductRegistrationController();
            productRegistrationController.getProductRegistrationStatus(function(err, data) {
                if (err) { return; }

                if (data.statusAsString === 'UNREGISTERED') {
                    productRegistrationController.showProductRegistrationModal();
                }
            });
        });

        function init() {
            $("#authUser").focus();
        }

        function transmit_form() {
            document.forms[0].submit();
        }

        function imsubmitted() {
            <?php if (!empty($GLOBALS['restore_sessions'])) { ?>
                // Delete the session cookie by setting its expiration date in the past.
                // This forces the server to create a new session ID.
                var olddate = new Date();
                olddate.setFullYear(olddate.getFullYear() - 1);
                document.cookie = '<?php echo session_name() . '=' . session_id() ?>; path=<?php echo($web_root ? $web_root : '/');?>; expires=' + olddate.toGMTString();
            <?php } ?>
            return false; //Currently the submit action is handled by the encrypt_form().
        }
    </script>

</head>


<body class="login" id="particles-js">
	<div class="home_main_page">


    <header class="main_page">
        <div class="logo">
           <img class="img-responsive center-block login_logo" src="/public/images/logo_home.png">
        </div>
        <div class="top_header_btn">
            <ul class="nav navbar-nav navbar-nav2">
                <li><a target="_blank" href="http://13.251.197.241/portal">Patient Login</a></li>
              <li><a target="_blank" href="http://13.251.197.241/interface/login/login.php?">Provider Login</a></li>             
            </ul>
        <a href="javascript:void(0)" class="css_button"><span>Buy WNCT </span></a>
        </div>
    </header>
<div class="main_cont">
    <h2 class="title_headding">Transforming CONVENTIONAL HEALTHCARE</h2>
    <div class="container main_page">

        <div class="main_btn">
            <div class="form-group">
                <div class="register_part_left">
                        <div class="main" >
                            <img src="/public/images/home_icon/patient.png">
                        Patient <br>Registration</div>
                        <a target="_blank" href="http://13.251.197.241/portal/account/register.php" class="css_button">
                            Join 
                        </a>
                        </div>
                        <div class="register_part_right">
                        <div class="main" >
                            <img src="/public/images/home_icon/hospital.png">
                        Healthcare Provider<br> Registration</div>
                        <a target="_blank" href="http://13.251.197.241/portal/register_2.php?" class="css_button">
                            Join 
                        </a>
                        </div>
                    </div>
        </div>
    </div></div>
  
    	<div class="footer_bottom">
              <p>Copyright © 2018 Wellness Chain, All Rights Reserved.</p>
    		
            <div class="footer_pric pull-right"><a href="/public/privacy/privacy_policy.pdf"> Privacy Policy</a>
             <ul class="social_icon">
                 <li><a href="https://0.plus/WNCJKL"><img src="/public/images/home_icon/chat_1.png"></a></li>
                 <li><a href="javascript:void(0)"><img src="/public/images/home_icon/chat_2.png"></a></li>
                 <li><a href="https://www.facebook.com/wnc888"><img src="/public/images/home_icon/chat_3.png"></a></li>
                 <li><a href="https://twitter.com/wellnesschain"><img src="/public/images/home_icon/chat_4.png"></a></li>
                 <li><a href="https://weibo.com/wellnesschain"><img src="/public/images/home_icon/chat_5.png"></a></li>
             </ul>
            </div>
   
    	</div>

</div>
    <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/particles.js?v=<?php echo $v_js_includes; ?>"></script>

     <script type="text/javascript" src="<?php echo $webroot ?>/interface/product_registration/app.js?v=<?php echo $v_js_includes; ?>"></script>
</body>
</html>
