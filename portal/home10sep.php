<?php
/**
 *
 * Copyright (C) 2016-2018 Jerry Padgett <sjpadgett@gmail.com>
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
 * @link http://www.open-emr.org
 */
 require_once("verify_session.php");
 require_once("$srcdir/patient.inc");
 require_once("lib/portal_mail.inc");

if ($_SESSION['register'] === true) {
    session_destroy();
    header('Location: '.$landingpage.'&w');
    exit();
}

if (!isset($_SESSION['portal_init'])) {
    $_SESSION['portal_init'] = true;
}

 $whereto = 'profilepanel';
if (isset($_SESSION['whereto'])) {
    $whereto = $_SESSION['whereto'];
}

 $user = isset($_SESSION['sessionUser']) ? $_SESSION['sessionUser'] : 'portal user';
 $PatientDataRecord = $result = getPatientData($pid);
	
 $msgs = getPortalPatientNotes($_SESSION['portal_username']);
 $msgcnt = count($msgs);
 $newcnt = 0;
foreach ($msgs as $i) {
    if ($i['message_status']=='New') {
        $newcnt += 1;
    }
}

require_once '_header.php';
 echo "<script>var cpid='" . attr($pid) . "';var cuser='" . attr($user) . "';var webRoot='" . $GLOBALS['web_root'] . "';var ptName='" . attr($_SESSION['ptName']) . "';</script>";
?>
<?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
<script type="text/javascript">
var webroot_url = webRoot;

$(document).ready(function () {

    $("#profilereport").load("./get_profile.php", {'embeddedScreen': true}, function () {
        $("table").addClass("table  table-responsive");
        $(".demographics td").removeClass("label");
        $(".demographics td").addClass("bold");
        $(".insurance table").addClass("table-sm table-striped");
        $("#editDems,.editDems").click(function () {
            showProfileModal()
        });
		  $('.datepicker').datetimepicker({
        inline:true
		});
    });
    $("#reports").load("./report/portal_patient_report.php?pid='<?php echo attr($pid) ?>'", {'embeddedScreen': true}, function () {
        <?php if ($GLOBALS['portal_two_payments']) { ?>
            $("#payment").load("./portal_payment.php", {'embeddedScreen': true}, function () {});
        <?php } ?>
    });
    $("#medicationlist").load("./get_medications.php", {'embeddedScreen': true}, function () {
        $("#allergylist").load("./get_allergies.php", {'embeddedScreen': true}, function () {
            $("#problemslist").load("./get_problems.php", {'embeddedScreen': true}, function () {
                $("#amendmentslist").load("./get_amendments.php", {'embeddedScreen': true}, function () {
                    $("#labresults").load("./get_lab_results.php", {'embeddedScreen': true}, function () {

                    });
                });
            });
        });
    });

    $('.sigPad').signaturePad({drawOnly: true});
    $(".generateDoc_download").click(function () {
        $("#doc_form").submit();
    });

    function showProfileModal() {
        var title = '<?php echo xla('Demographics Legend Red: Charted Values. Blue: Patient Edits'); ?> ';

        var params = {
            buttons: [
                {text: '<?php echo xla('Help'); ?>', close: false, style: 'info', id: 'formHelp'},
                {text: '<?php echo xla('Cancel'); ?>', close: true, style: 'default'},
                {text: '<?php echo xla('Revert Edits'); ?>', close: false, style: 'danger', id: 'replaceAllButton'},
                {text: '<?php echo xla('Send for Review'); ?>',
                    close: false,
                    style: 'success',
                    id: 'donePatientButton'
                }],
            onClosed: 'reload',
            type: 'GET',
            url: webRoot + '/portal/patient/patientdata?pid=' + cpid + '&user=' + cuser
        };
        dlgopen('','','modal-xl', 500, '', title, params);
    }

    function saveProfile() {
        page.updateModel();
    }

    var gowhere = '#<?php echo $whereto?>';
    $(gowhere).collapse('show');

    var $doHides = $('#panelgroup');
    $doHides.on('show.bs.collapse', '.collapse', function () {
        $doHides.find('.collapse.in').collapse('hide');
    });
    //Enable sidebar toggle
    $("[data-toggle='offcanvas']").click(function (e) {
        e.preventDefault();
        //If window is small enough, enable sidebar push menu
        if ($(window).width() <= 992) {
            $('.row-offcanvas').toggleClass('active');
            $('.left-side').removeClass("collapse-left");
            $(".right-side").removeClass("strech");
            $('.row-offcanvas').toggleClass("relative");
        } else {
            //Else, enable content streching
            $('.left-side').toggleClass("collapse-left");
            $(".right-side").toggleClass("strech");
        }
    });
    $(function () {
        $('#popwait').hide();
        $('#callccda').click(function () {
            $('#popwait').show();
        })
    });
});

function editAppointment(mode,deid){
    if(mode == 'add'){
        var title = '<?php echo xla('Request New Appointment'); ?>';
        var mdata = {pid:deid};
    }
    else{
        var title = '<?php echo xla('Edit Appointment'); ?>';
        var mdata = {eid:deid};
    }
    var params = {
        dialogId: 'editpop',
        buttons: [
            { text: '<?php echo xla('Cancel'); ?>', close: true, style: 'default' }
            //{ text: 'Print', close: false, style: 'success', click: showCustom }
        ],
        type:'GET',
        dataType: 'text',
        url: './add_edit_event_user.php',
        data: mdata
    };

    dlgopen('', 'apptModal', 610, 300, '', title, params);

};

</script>
    <!-- Right side column. Contains content of the page -->
    <aside class="right-side">
        <!-- Main content -->
        <section class="container-fluid content panel-group rightbg" id="panelgroup">


        <div id="popwait" class="alert alert-warning" style="font-size:18px"><strong><?php echo xlt('Working!'); ?></strong> <?php echo xlt('Please wait...'); ?></div>
        

<div class="row collapse" id="allergy">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <header class="panel-heading"><?php echo xlt('Allergy'); ?>  </header>
                        <div id="allergylist" class="panel-body"></div>

                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->



            <div class="row collapse" id="prescription">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <header class="panel-heading"><?php echo xlt('Prescription'); ?> </header>
                        <div id="medicationlist" class="panel-body"></div>

                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->


            <div class="row collapse" id="visit">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <header class="panel-heading"><?php echo xlt('Visit'); ?></header>
                        <div id="problemslist" class="panel-body"></div>

                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->

            <div class="row collapse" id="improvement">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <header class="panel-heading"><?php echo xlt('Improvement'); ?> </header>
                        <div id="amendmentslist" class="panel-body"></div>

                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->






            <div class="row collapse" id="revokes">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                            <header class="panel-heading"><?php echo xlt('Revokes'); ?>  </header>
                            <div id="labresults" class="panel-body"></div>
                            <div class="panel-footer"></div>
                        </div><!-- /.panel -->
                </div><!-- /.col -->
            </div><!-- /.lists -->




            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary collapse" id="appointmentpanel">
                        <header class="panel-heading"><i class="fa fa-calendar-o mr-10"></i><?php echo xlt('Appointments'); ?>  </header>
                        <div id="appointmentslist" class="panel-body">
                        <?php
                            $query = "SELECT e.pc_eid, e.pc_aid, e.pc_title, e.pc_eventDate, " . "e.pc_startTime, e.pc_hometext, e.pc_apptstatus, u.fname, u.lname, u.mname, " .
                                "c.pc_catname " . "FROM openemr_postcalendar_events AS e, users AS u, " .
                                "openemr_postcalendar_categories AS c WHERE " . "e.pc_pid = ? AND e.pc_eventDate >= CURRENT_DATE AND " . "u.id = e.pc_aid AND e.pc_catid = c.pc_catid " . "ORDER BY e.pc_eventDate, e.pc_startTime";

                            $res = sqlStatement($query, array(
                                $pid
                            ));

                            if (sqlNumRows($res) > 0) {
                                $count = 0;
                                echo '<table id="appttable" style="width:100%;background:#eee;" class="table table-striped fixedtable"><thead>
                                </thead><tbody>';
                                while ($row = sqlFetchArray($res)) {
                                    $count++;
                                    $dayname = xl(date("l", strtotime($row ['pc_eventDate'])));
                                    $dispampm = "am";
                                    $disphour = substr($row ['pc_startTime'], 0, 2) + 0;
                                    $dispmin = substr($row ['pc_startTime'], 3, 2);
                                    if ($disphour >= 12) {
                                        $dispampm = "pm";
                                        if ($disphour > 12) {
                                            $disphour -= 12;
                                        }
                                    }

                                    if ($row ['pc_hometext'] != "") {
                                        $etitle = 'Comments' . ": " . $row ['pc_hometext'] . "\r\n";
                                    } else {
                                        $etitle = "";
                                    }

                                    echo "<tr><td><p>";
                                    echo "<a href='#' onclick='editAppointment(0," . htmlspecialchars($row ['pc_eid'], ENT_QUOTES) . ')' . "' title='" . htmlspecialchars($etitle, ENT_QUOTES) . "'>";
                                    echo "<b>" . htmlspecialchars($dayname . ", " . $row ['pc_eventDate'], ENT_NOQUOTES) . "</b><br>";
                                    echo htmlspecialchars("$disphour:$dispmin " . $dispampm . " " . $row ['pc_catname'], ENT_NOQUOTES) . "<br>";
                                    echo htmlspecialchars($row ['fname'] . " " . $row ['lname'], ENT_NOQUOTES) . "<br>";
                                    echo htmlspecialchars("Status: " . $row ['pc_apptstatus'], ENT_NOQUOTES);
                                    echo "</a></p></td></tr>";
                                }

                                if (isset($res) && $res != null) {
                                    if ($count < 1) {
                                        echo "&nbsp;&nbsp;" . xlt('None');
                                    }
                                }
                            } else { // if no appts
                                echo xlt('No Appointments');
                            }

                            echo '</tbody></table>';
                        ?>
                            <div style='margin: 5px 0 5px'>
                                <a href='#' onclick="editAppointment('add',<?php echo attr($pid); ?>)">
                                    <button class='btn btn-primary pull-right'><?php echo xlt('Schedule New Appointment'); ?></button>
                                </a>
                            </div>
                        </div>
                        <div class="panel-footer"></div>
                    </div><!-- /.panel -->
                </div><!-- /.col -->
            </div><!-- /.row -->
            <?php if ($GLOBALS['portal_two_payments']) { ?>
            <div class="row">
               <div class="col-sm-12">
                    <div class="panel panel-primary collapse" id="paymentpanel">
                        <header class="panel-heading"> <?php echo xlt('Payments'); ?> </header>
                        <div id="payment" class="panel-body"></div>
                        <div class="panel-footer">
                        </div>
                    </div>
                </div> <!--/.col  -->
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary collapse" style="padding-top:0;padding-bottom:0;" id="messagespanel">
                        <!-- <header class="panel-heading"><?php //echo xlt('Secure Chat'); ?>  </header>-->
                        <div id="messages" class="panel-body" style="height:calc(100vh - 120px);overflow:auto;padding:0 0 0 0;" >
                             <iframe src="./messaging/secure_chat.php" width="100%" height="100%"></iframe>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-primary collapse" id="reportpanel">
                        <header class="panel-heading"><?php echo xlt('Reports'); ?>  </header>
                        <div id="reports" class="panel-body"></div>
                        <div class="panel-footer"></div>
                    </div>

                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <div class="panel panel-primary collapse" id="downloadpanel">
                        <header class="panel-heading"> <?php echo xlt('Download Documents'); ?> </header>
                        <div id="docsdownload" class="panel-body">
                        <?php if ($GLOBALS['portal_onsite_document_download']) { ?>
                            <div>
                                <span class="text"><?php echo xlt('Download all patient documents');?></span>
                                <form name='doc_form' id='doc_form' action='./get_patient_documents.php' method='post'>
                                <input type="button" class="generateDoc_download" value="<?php echo xla('Download'); ?>" />
                                </form>
                            </div>
                        <?php } ?>
                        </div><!-- /.panel-body -->
                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary collapse" id="ledgerpanel">
                        <header class="panel-heading"><?php echo xlt('Ledger');?> </header>
                        <div id="patledger" class="panel-body"></div>
                        <div class="panel-footer">
                          <iframe src="./report/pat_ledger.php?form=1&patient_id=<?php echo attr($pid);?>" width="100%" height="475" scrolling="yes"></iframe>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div>

            <div class="row">
            <div class="col-sm-12 col-xs-12 mainsec">
            	<h1>Account Summary</h1>
                <ul class="dashlist">
                	<li><span><img src="images/wnc.png" alt="" /> WNCT	:  	0</span> <a class="buywnct" href="http://wellnesschain.io/" target="_balnk">Buy WNCT</a></li>
                    <li><span><img src="images/profile.png" alt="" /> Profile	: </span>
					<?php
					 
					$width  = '100';
					foreach($Incomplete as $onepa)
					{
						 
						if(empty($onepa))
						{
							 
							$width  = '56';
						}
					}
					?>
					<div class="profileedit"><span><small style="width:<?php echo $width ?>%;"></small></span> <?php 
					echo ($width=="100")?'Complete':'Incomplete';
					?></div> 
					
					<a class="eidtbtn editDems" href="javascript:void(0);"><img src="images/edit.png" alt="" /></a></li>
                    <li><span><img src="images/appo.png" alt="" /> Appointment </span>
                    <div class="appcal"> 
                        <div class="calenders">
                        <div type="date" class="datepicker">
						 </div>
                         
                        </div>
                        <div class="appolist">
                        <ul>
                        	 <?php 
							 
							  $query = "SELECT e.pc_eid, e.pc_aid, e.pc_title, e.pc_eventDate, " . "e.pc_startTime, e.pc_hometext, e.pc_apptstatus, u.fname, u.lname, u.mname, u.specialty, u.facility ," .
                                "c.pc_catname " . "FROM openemr_postcalendar_events AS e, users AS u, " .
                                "openemr_postcalendar_categories AS c WHERE " . "e.pc_pid = ? AND e.pc_eventDate >= CURRENT_DATE AND " . "u.id = e.pc_aid AND e.pc_catid = c.pc_catid " . "ORDER BY e.pc_eventDate, e.pc_startTime";

                            $res = sqlStatement($query, array(
                                $pid
                            ));
							
							 if (sqlNumRows($res) > 0) 
							 { 
							  while ($row = sqlFetchArray($res)) 
							  {
								// print_r($row); 
								  $count++;
                                    $dayname = xl(date("l", strtotime($row ['pc_eventDate'])));
                                    $dispampm = "am";
                                    $disphour = substr($row ['pc_startTime'], 0, 2) + 0;
                                    $dispmin = substr($row ['pc_startTime'], 3, 2);
                                    if ($disphour >= 12) {
                                        $dispampm = "pm";
                                        if ($disphour > 12) {
                                            $disphour -= 12;
                                        }
                                    }

                                    if ($row ['pc_hometext'] != "") {
                                        $etitle = 'Comments' . ": " . $row ['pc_hometext'] . "\r\n";
                                    } else {
                                        $etitle = "";
                                    }
							 ?>
                            <li>
                            	<h3><?php 
								echo   htmlspecialchars($dayname . ", " . $row ['pc_eventDate'], ENT_NOQUOTES);
								 
								?></h3>
                            	<div class="listbg">                                	
                                	<h4><?php echo htmlspecialchars($row ['fname'] . " " . $row ['lname'], ENT_NOQUOTES) ;?>  <small><?php echo $row ['specialty']  ?></small> <small class="pull-right"><?php  echo htmlspecialchars("$disphour:$dispmin " . $dispampm . " " . $row ['pc_catname'], ENT_NOQUOTES); ?></small></h4>
                                    <span><?php echo $row ['facility']  ?>  </span>
                                </div>
                                <div class="whitebg">
                                	<span>WNCT 10</span> 
									<a class="modify" href="javascript:void(0);" onclick='editAppointment(0,"<?php echo htmlspecialchars($row ['pc_eid'], ENT_QUOTES); ?>")'>Modify</a>
									
									 
									
                                </div>
                            </li>
							<?php 
								} 
							} 
							?>
                        </ul>
                        </div>
                    </div>
                    </li>
                    <li><span><img src="images/device.png" alt="" /> Devices	:  	0</span>
                    <div class="devices"><img src="images/img.jpg" alt="" /></div>
                    </li>
                </ul>
            </div>
                <!--<div class="col-sm-3 col-xs-12">
                    <div class="dash_bord">
                        <div class="dash_text">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="right_text">
                                <h2>10368</h2>
                                <p>UI Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <div class="dash_bord">
                        <div class="dash_text">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="right_text">
                                <h2>10368</h2>
                                <p>UI Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <div class="dash_bord">
                        <div class="dash_text">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="right_text">
                                <h2>10368</h2>
                                <p>UI Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <div class="dash_bord">
                        <div class="dash_text">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="right_text">
                                <h2>10368</h2>
                                <p>UI Developer</p>
                            </div>
                        </div>
                    </div>
                </div>-->
    
                <div class="col-sm-12 mainsec">
                    <div class="panel panel-primary collapse" id="profilepanel">
                        <header class="panel-heading"><i class="fa fa-user mr-10" aria-hidden="true"></i><?php echo xlt('Profile'); ?>
                            <?php if ($pending) {
                    echo '<button type="button" id="editDems" class="btn btn-danger btn-xs pull-right" style="color:white;font-size:14px">' . xlt('Pending Review') . '</button>';
} else {
    echo '<button type="button" id="editDems" class="btn btn-success btn-xs pull-right" style="color:white;font-size:14px">' . xlt('Complete Your Profile To Get Free 3 WNCT Token') . '</button>';
}
                        ?>
                        </header>
                        <div id="profilereport" class="panel-body"></div>
                    <div class="panel-footer"></div>
                    </div>
              </div>
            </div>

        </section>
        <!-- /.content -->
        
    </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->
<div id="openSignModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="input-group">
                    <span class="input-group-addon"
                          onclick="getSignature(document.getElementById('patientSignaturem'))"><em> <?php echo xlt('Show Current Signature On File'); ?>
                            <br>
                            <?php echo xlt('As appears on documents'); ?>.</em></span> <img
                        class="signature form-control" type="patient-signature"
                        id="patientSignaturem" onclick="getSignature(this)"
                        alt="Signature On File" src="">
                </div>
            </div>
            <div class="modal-body">
                <form name="signit" id="signit" class="sigPad">
                    <input type="hidden" name="name" id="name" class="name">
                    <ul class="sigNav">
                        <label style='display: none;'><input style='display: none;'
                            type="checkbox" class="" id="isAdmin" name="isAdmin" /><?php echo xlt('Is Authorizing Signature');?></label>
                        <li class="clearButton"><a href="#clear"><button><?php echo xlt('Clear Signature');?></button></a></li>
                    </ul>
                    <div class="sig sigWrapper">
                        <div class="typed"></div>
                        <canvas class="spad" id="drawpad" width="765" height="325"
                            style="border: 1px solid #000000; left: 0px;"></canvas>
                        <img id="loading"
                            style="display: none; position: absolute; TOP: 150px; LEFT: 315px; WIDTH: 100px; HEIGHT: 100px"
                            src="sign/assets/loading.gif" /> <input type="hidden" id="output"
                            name="output" class="output">
                    </div>
                    <input type="hidden" name="type" id="type"
                        value="patient-signature">
                    <button type="button" onclick="signDoc(this)"><?php echo xlt('Acknowledge as my Electronic Signature');?>.</button>
                </form>
            </div>
        </div>
        <!-- <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
    </div>
</div><!-- Modal -->





<!-- Change Password -->
<div id="change_pass" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                
<div class="change_password">
   <div class="row">
      <div class="col-xs-12">
         <div class="page-header">
            <h3>Change Password</h3>
         </div>
      </div>
   </div>
  <div class="row">
     <div class="col-xs-12">
        <form method='post' action='' class='form-horizontal'>
        <div class="form-group">
           <label class='control-label col-sm-4'>Patient ID :</label>
           <div class="col-sm-6">
           <p class="form-control-static"> WNCMRN003511536299742</p>
           </div>
        </div>
        <div class="form-group">
           <label for='curPass' class='control-label col-sm-4'>Current Password:</label>
           <div class='col-sm-6'>
           <input type='password' class='form-control'  name='curPass' value="" autocomplete='off'>
           </div>
        </div>
        <div class="form-group">
           <label class='control-label col-sm-4'>New Password:</label>
           <div class='col-sm-6'>
           <input type='password' class='form-control' name='newPass' value="" autocomplete='off'>
           </div>
        </div>
        <div class="form-group">
           <label class='control-label col-sm-4'>Repeat New Password:</label>
           <div class='col-sm-6'>
           <input type='password' class='form-control' name=newPass2  value="" autocomplete='off'>
           </div>
        </div>
        <div class="form-group">
           <div class='col-sm-offset-4 col-sm-8'>
              <button type="Submit" class='btn btn-primary btn-save'>Save Changes</button>
           </div>
        </div>
        </form>
     </div>
  </div>
</div>
            </div>
            
        </div>

    </div>
</div>
<!--End Change Password -->


<img id="waitend"
    style="display: none; position: absolute; top: 100px; left: 260px; width: 100px; height: 100px"
    src="sign/assets/loading.gif" />


</body>
</html>
<style>
						 .datepicker {
							 display:block !important;
						 }
						 </style>