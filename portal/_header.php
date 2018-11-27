
<?php
/**
 *
 * Copyright (C) 2016-2018 Jerry Padgett <abc@gmail.com>
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
 * @package Wellness Chain
 * @author 
 // * 
 */
 session_start();
use OpenEMR\Core\Header;
require_once("verify_session.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo xlt('Wellness Chain'); ?> | <?php echo xlt('Home'); ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta name="description" content="Developed By ">

    <?php Header::setupHeader(['no_main-theme', 'datetime-picker', 'jquery-ui', 'jquery-ui-sunny', 'emodal']); ?>

<script type="text/javascript" src="../interface/main/tabs/js/dialog_utils.js?v=<?php echo $v_js_includes; ?>"></script>
<link href="assets/css/style.css?v=<?php echo $v_js_includes; ?>" rel="stylesheet" type="text/css" />
<link href="sign/css/signer.css?v=<?php echo $v_js_includes; ?>" rel="stylesheet" type="text/css" />
<link href="sign/assets/signpad.css?v=<?php echo $v_js_includes; ?>" rel="stylesheet">
<link rel="stylesheet" href="/public/assets/datatables.net-dt-1-10-13/css/jquery.dataTables.min.css" type="text/css">
<script type="text/javascript" src="/public/assets/datatables.net-1-10-13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/public/assets/datatables.net-colreorder-1-3-2/js/dataTables.colReorder.min.js"></script>
<script src="sign/assets/signpad.js?v=<?php echo $v_js_includes; ?>" type="text/javascript"></script>
<script src="sign/assets/signer.js?v=<?php echo $v_js_includes; ?>" type="text/javascript"></script>
    <script type="text/javascript">
        var tab_mode = true; // for dialogs
        <?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
    </script>
</head>
<body class="skin-blue fixed">

<style>
.popupbg{ background:rgba(0,0,0,0.8) url(images/blur.jpg) 100% 100%; width:100%; text-align: center;
 height:100%; float:left; position:fixed; left:0; top:0; z-index:99999;}
.popupmain{ width:350px; margin:0 auto; height:300px; margin-top:18%;}
.bgmain{ background:#fff; padding:15px; border-radius:5px;}
.closebtn{ cursor:pointer;}
.bgmain h2{
    text-align: center;
        margin-top: 10px;
}
.buy_btn{
    display: inline-block;
}
.bgmain img {
    width: 80px;
}
</style>
<!-- <div class="popupbg">
    <div class="popupmain">
        <div class="bgmain">
        <span class="closebtn pull-right">X</span>
        <img src="images/wc_coin.png">
            <h2>Activation</h2>
            <p>For activation please deposit 10 WNCT</p>
            <p class="buy_btn"><a class="btn btn-primary" href="http://wellnesschain.io/">Buy WNCT</a></p>
            <p class="buy_btn"><a class="btn btn-primary" href="http://wellnesschain.io/">Deposit WNCT</a></p>

        </div>
    </div>
</div> -->

<script>
$(".closebtn").click(function(){
    $(".popupbg").hide();
}); 
</script>
    <header class="header">
        <a href="home.php" class="logo"><img src='<?php echo $GLOBALS['images_static_relative']; ?>/logo-full-con.png'/></a>
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <ul class="nav navbar-nav">
            	 <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas"
                role="button"> <span class="sr-only"><?php echo xlt('Toggle navigation'); ?></span> <span
                class="icon-bar"></span> <span class="icon-bar"></span> <span
                class="icon-bar"></span>
            </a>
                	<li class="patient_id titleid">
                		Patient ID :
                	</li>
                	<li class="patient_id">
                		<!-- WNCMRN003721536484037 -->
                       <?php echo text($result['patient_id']); ?> 
                        
                              
                        
                        
                	</li>
                </ul>
                         
                     
                     

                  
                    

           
            <div class="navbar-right m_view">
            	<ul class="nav navbar-nav tokens">
                	<li class="wnc_token">WNCT :</li>
                	<li class="wnc_token">0</li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-envelope"></i> <span class="label label-success"> <?php echo text($newcnt); ?></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?php echo xlt('You have'); ?> <?php echo text($newcnt); ?> <?php echo xlt('new messages'); ?></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                <?php
                                foreach ($msgs as $i) {
                                    if ($i['message_status']=='New') {
                                        echo "<li><a href='" . $GLOBALS['web_root'] . "/portal/messaging/messages.php'><h4>" . text($i['title']) . "</h4></a></li>";
                                    }
                                }
                                ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="<?php echo $GLOBALS['web_root']; ?>/portal/messaging/messages.php"><?php echo xlt('See All Messages'); ?></a></li>
                        </ul></li>

                    <li class="dropdown user user-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-user"></i> <span><?php echo text($result['fname']." ".$result['lname']); ?>
                                <i class="caret"></i></span>
                    </a>
                        <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                            <li class="dropdown-header text-center"><?php echo xlt('Account'); ?></li>
                            <li><a href="<?php echo $GLOBALS['web_root']; ?>/portal/messaging/messages.php"> <i class="fa fa-envelope-o fa-fw pull-right"></i>
                                    <span class="badge badge-danger pull-right"> <?php echo text($msgcnt); ?></span> <?php echo xlt('Messages'); ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $GLOBALS['web_root']; ?>/portal/messaging/secure_chat.php?fullscreen=true"> <i class="fa fa-user fa-fw pull-right"></i><?php echo xlt('Chat'); ?></a>
                                <a href="#change_pass" data-toggle="modal" data-backdrop="true" data-target="#change_pass"> <i
                                    class="fa fa-cog fa-fw pull-right"></i> <?php echo xlt('Change Password'); ?></a></li>

                            <li class="divider"></li>

                            <li><a href="logout.php"><i class="fa fa-ban fa-fw pull-right"></i>
                                    <?php echo xlt('Logout'); ?></a></li>
                        </ul></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image userimg">
                    <span><img src="images/user.png" alt="" /></span>
                        <!--<i class="fa fa-user"></i>-->
                    </div>
                    <div class="pull-left info">
                        <p><?php echo xlt('Welcome') . ' ' . text($result['fname']." ".$result['lname']); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> <?php echo xlt('Online'); ?></a>
                    </div>
                </div>
                <ul class="nav  nav-pills nav-stacked" style='font-color:#fff;'><!-- css class was sidebar-menu -->
                	<li data-toggle="pill" class="active"><a href="#profilepanel" data-toggle="collapse"
                        data-parent="#panelgroup" aria-expanded="true"> <i class="fa fa-tachometer"></i><span><?php echo xlt('Dashboard'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#account" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-user"></i> <span><?php echo xlt('Profile'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#Insurance" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-industry"></i> <span><?php echo xlt("Insurance"); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#appointmentpanel" data-toggle="collapse"
                        data-parent="#panelgroup">
                         <i class="fa fa-calendar-o"></i> <span><?php echo xlt("Appointment"); ?></span>
                    </a></li>
                     <li data-toggle="pill"><a href="#give_access" data-toggle="collapse" data-parent="#panelgroup"><i class="fa fa-universal-access"></i><span><?php echo xlt("Give Access"); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#prescription" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-file-text"></i> <span><?php echo xlt('Curent Prescription'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#visit" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-street-view"></i> <span><?php echo xlt('Visit Report'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#allergy" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-asterisk"></i> <span><?php echo xlt('Allergy'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#revokes" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-flask"></i> <span><?php echo xlt('Lab Reports'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#improvement" data-toggle="collapse"data-parent="#panelgroup"> <i class="fa fa-line-chart"></i> <span><?php echo xlt('Improvement'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#downloadpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-download"></i> <span><?php echo xlt('Patient Documents'); ?></span></a></li>
                    
                    <li class="dropdown accounting-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-book"></i> <span><?php echo xlt('Accountings'); ?></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li data-toggle="pill"><a href="#ledgerpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-folder-open"></i> <span><?php echo xlt('Ledger'); ?></span>
                            </a></li>
                            <?php if ($GLOBALS['portal_two_payments']) { ?>
                                <li data-toggle="pill"><a href="#paymentpanel" data-toggle="collapse"
                                    data-parent="#panelgroup"> <i class="fa fa-credit-card"></i> <span><?php echo xlt('Make Payment'); ?></span>
                                </a></li>
                            <?php } ?>
                        </ul></li>
                        <li data-toggle="pill"><a href="#reportpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-folder-open"></i> <span><?php echo xlt('Report Content'); ?></span></a></li>
                    <!-- <li class="dropdown reporting-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-calendar"></i> <span><?php echo xlt('Reports'); ?></span>
                    </a>
                        <ul class="dropdown-menu">
                            <?php if ($GLOBALS['ccda_alt_service_enable'] > 1) { ?>
                                <li><a id="callccda" href="<?php echo $GLOBALS['web_root']; ?>/ccdaservice/ccda_gateway.php?action=startandrun">
                                        <i class="fa fa-envelope" aria-hidden="true"></i><span><?php echo xlt('View CCD'); ?></span></a></li>
                            <?php } ?>
                            <li data-toggle="pill"><a href="#reportpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-folder-open"></i> <span><?php echo xlt('Report Content'); ?></span></a></li>
                            
                        </ul></li> -->

                    <li><a href="<?php echo $GLOBALS['web_root']; ?>/portal/messaging/messages.php"><i class="fa fa-envelope" aria-hidden="true"></i>
                            <span><?php echo xlt('Secure Messaging'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#messagespanel" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-envelope"></i> <span><?php echo xlt("Secure Chat"); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#openSignModal" data-toggle="modal" > <i
                            class="fa fa-sign-in"></i><span><?php echo xlt('Signature on File'); ?></span>
                    </a></li>
                    <li><a href="logout.php"><i class="fa fa-ban fa-fw"></i> <span><?php echo xlt('Logout'); ?></span></a></li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
