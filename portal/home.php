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
 * @package Wellness Chain
 * @author 
 * @link
 */
 require_once("verify_session.php");
 require_once("$srcdir/patient.inc");
 require_once("lib/portal_mail.inc");
  include_once("otpfunctions.php");

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
<!-- <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?> -->
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
          
    });
    $("#reports").load("./report/portal_patient_report.php?pid='<?php echo attr($pid) ?>'", {'embeddedScreen': true}, function () {
        <?php if ($GLOBALS['portal_two_payments']) { ?>
            $("#payment").load("./portal_payment.php", {'embeddedScreen': true}, function () {});
        <?php } ?>
    });

    $("#medicationlist").load("./get_medications.php", {'embeddedScreen': true}, function () {
        $("#allergylist").load("./get_allergies.php", {'embeddedScreen': true}, function () {
            $("#final_access").load("./final_access.php", {'embeddedScreen': true}, function () {
            $("#problemslist").load("./get_problems.php", {'embeddedScreen': true}, function () {
                $("#amendmentslist").load("./get_amendments.php", {'embeddedScreen': true}, function () {
                    $("#labresults").load("./get_lab_results.php", {'embeddedScreen': true}, function () {

                    });
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
<style type="text/css">
    .ins_btn{
        margin-right: 20px;
    }
    .well {
    background: no-repeat;
    border: none;
    padding: 40px;
}
span.bold {
    padding: 0px 0;
    margin: 8px 0; 
    display: block;
    font-size: 14px;
    font-weight: 600;
}
input.btn.btn-primary.access{
    margin-top: 36px;
}

.das_board {
    padding: 10px;
}

table.collection.table.table-hover {
    width: 95%;
    margin: auto;
    border-bottom: none;
}
</style>

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
                        <header class="panel-heading"><?php echo xlt('Curent Prescription'); ?> </header>
                        <div id="medicationlist" class="panel-body"></div>

                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->


            <div class="row collapse" id="visit">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <header class="panel-heading"><?php echo xlt('Visit Report'); ?></header>
                        <div id="problemslist" class="panel-body"></div>

                        <div class="panel-footer"></div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->

<script type="text/javascript">
    function CoPayment()
{
var a = document.getElementById("copaym").value
if(a.length>8 )
{
alert("Please enter less than 10 lakh Amount");
      
          return false;


}
else
{
    
}
}


</script>

<?php

            


      if(isset($_POST['sub'])){
       $pids=$result['patient_id'];

           $dateCheck = "SELECT date FROM insurance_data where date = 
           '".$_POST['date']."'";
           $dateCheckResult = sqlStatement($dateCheck);
            while ($frow = sqlFetchArray($dateCheckResult)) {
                $date = $frow['date'];
            }
            if (empty($date)) {
                $insertInsurance =
            "insert into insurance_data set 
                
                type    ='".'primary'."',
                patient_id= '".$pids."',
                provider = '".$_POST['provider'] ."',
                plan_name = '".$_POST['plan_name']."',
                policy_number = '".$_POST['policy_number'] ."',
                group_number = '"  .$_POST['group_number'] ."',
                date = '". $_POST['date'] ."',
                copay = '". $_POST['copay'] ."' ";   
             

                
            $lastid=sqlInsert($insertInsurance);
             echo '<script>alert("Record inserted Successfully");</script>';
            }
     

            
            else{

                echo '<script>alert(" Date already register ");</script>';
            }



}
?>



            <div class="row collapse" id="Insurance">
                <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <header class="panel-heading">
                            <?php echo xlt('Insurance'); ?></header>
                        <form id="insuranceForm" role="form" action="" method="post">
            <div class="row setup-content">
                <div class="col-md-12">
                    <fieldset>
                        
                        <div class="well">
                            <div class="form-group ">
                                <label class="control-label" for="provider"><?php echo xlt('Insurance Company')?></label>
                                <div class="controls -inputs">
                                    <input type="text" class="form-control" name="provider" id="inscompany" required placeholder="<?php echo xla('Enter Self if None'); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for=""><?php echo xlt('Plan Name')?></label>
                                <div class="controls -inputs">
                                    <input type="text" class="form-control" name="plan_name" required placeholder="<?php echo xla('Required'); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for=""><?php echo xlt('Policy Number')?></label>
                                <div class="controls -inputs">
                                    <input type="text" class="form-control" name="policy_number" required placeholder="<?php echo xla('Required'); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for=""><?php echo xlt('Group Number')?></label>
                                <div class="controls -inputs">
                                    <input type="text" class="form-control" name="group_number" required placeholder="<?php echo xla('Required'); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for=""><?php echo xlt('Policy Begin Date')?></label>
                                <div class="controls -inputs">
                                    <input type="text" class="form-control datepicker" name="date" placeholder="<?php echo xla('Policy effective date'); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for=""><?php echo xlt('Co-Payment')?></label>
                                <div class="controls -inputs">
                                    <input type="number" class="form-control" id="copaym" name="copay" placeholder="<?php echo xla('Plan copay if known'); ?>">
                                </div>
                            </div>
                           <input type="submit"  class="btn btn-primary nextBtn btn-sm pull-right" name="sub" value="Submit" onclick=" return CoPayment()">
                        </div>
                    </fieldset>
                </div>
            </div>
        </form>

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

<div class="row collapse " id="profilepanel">
                <div class="col-sm-12 mainsec">
                    <div class="panel panel-primary">
                        
<div class="col-sm-12 col-xs-12 ">
                <h1>Account Summary</h1>
                <ul class="dashlist">
                    <li><span><img src="images/wnc.png" alt="" /> WNCT  :   0</span> <a class="buywnct" href="http://wellnesschain.io/" target="_balnk">Buy WNCT</a></li>
                    <li><span><img src="images/profile.png" alt="" /> Profile   : </span>
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
                    
                  

                    <button type="button" class="" data-toggle="modal" data-target="#edit_profile_model"><img src="images/edit.png" alt=""> </button></li>
                    <li><span><img src="images/appo.png" alt="" /> Appointment </span>
                    <div class="appcal"> 
                        <div class="calenders">
                        <div type="date" class="datepickerN">
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
                                    $atearray[] = $row ['pc_eventDate']; 
                             ?>
                            <li>
                                <h3><?php 
                                echo   htmlspecialchars($dayname . ", " . $row ['pc_eventDate'], ENT_NOQUOTES);
                                 
                                ?></h3>
                                <div class="listbg">                                    
                                    <h4><?php echo htmlspecialchars($row ['fname'] . " " . $row ['lname'], ENT_NOQUOTES) ;?>   <small><?php echo $row ['specialty']; ?></small> <small class="pull-right"><?php  echo htmlspecialchars("$disphour:$dispmin " . $dispampm . " " . $row ['pc_catname'], ENT_NOQUOTES); ?> </small></h4>
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
                    <li><span><img src="images/device.png" alt="" /> Devices    :   0</span>
                    <div class="devices">
                    <link type="text/css" rel="stylesheet" href="assets/css/graph.css" />
       <script type="text/javascript" src="assets/js/multi.js"></script>
    <script src="assets/js/graph.js"></script>
    <link rel="stylesheet" href="assets/css/jquery.circliful.css">
    <link rel="stylesheet" href="assets/css/multi.css">
    <script src="assets/js/jquery.circliful.js"></script>
<script>
    $( document ).ready(function() { // 6,32 5,38 2,34
        $("#test-circle").circliful({
            animation: 1,
            animationStep: 5,
            animateInView: true,
            foregroundBorderWidth: 15,
            backgroundBorderWidth: 15,
            percent: 68,
            textSize: 28,
            textStyle: 'font-size: 12px;',
            textColor: '#666',
        });
       
    });

</script>

<div class="row">
    <div class="col-lg-4 col-sm-4">
        <div class="circlebg">
        <h3>Be more active</h3>
            <div id="test-circle"></div>
            <div class="cirlsec">
                <img src="images/running.png" alt="" />
                <span>35 <small>mins</small> <cite>Working</cite></span>
            </div>
            <div class="cirlsec wid2">
                <img src="images/running.png" alt="" />
                <span>67 <small>mins</small> <cite>Running and others</cite></span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-4">
    <div class="mintsec">
        <ul>
            <li><div>
                <img src="images/running.png" alt="" />
                <span>43 <small>mins</small></span>
                <small>Running <small>08:30</small></small>
                <a class="btn btn-primary" href="#">START</a>
                </div>
            </li>
            <li><div>
                <img src="images/running.png" alt="" />
                <span>1080 <small>kcal</small></span>
                <small>Food <small>Today</small></small>
                <a class="btn btn-primary" href="#">ADD</a>
                </div>
            </li> 
            <li><div>
                <img src="images/running.png" alt="" />
                <span>66.0 <small>kg</small></span>
                <small>Weight <small>08:22</small></small>
                <a class="btn btn-primary" href="#">RECORD</a>
                </div>
            </li>
            <li><div>
                <img src="images/running.png" alt="" />
                <span>89 <small>bpm</small></span>
                <small>Heart rate <small>08:17</small></small>
                <a class="btn btn-primary" href="#">MEASURE</a>
                </div>
            </li>            
        </ul>
    </div>
    </div>
    <div class="col-lg-4 col-sm-4">
    <div id="chart">
      <ul id="numbers">
        <li><span>100%</span></li>
        <li><span>90%</span></li>
        <li><span>80%</span></li>
        <li><span>70%</span></li>
        <li><span>60%</span></li>
        <li><span>50%</span></li>
        <li><span>40%</span></li>
        <li><span>30%</span></li>
        <li><span>20%</span></li>
        <li><span>10%</span></li>
        <li><span>0%</span></li>
      </ul>
      <ul id="bars">
        <li><div data-percentage="56" class="bar"></div><span>1</span></li>
        <li><div data-percentage="33" class="bar"></div><span>2</span></li>
        <li><div data-percentage="54" class="bar"></div><span>3</span></li>
        <li><div data-percentage="94" class="bar"></div><span>4</span></li>
        <li><div data-percentage="44" class="bar"></div><span>5</span></li>
        <li><div data-percentage="23" class="bar"></div><span>6</span></li>
      </ul>
    </div>
     </div> 
     
     </div> 
          </div>
                    </li>
                </ul>
            </div>   
                    </div>
                </div><!-- /.col -->
            </div><!-- /.lists -->
<!-----here give access code by shiv------->

<?php
            $Patientisd =$result['patient_id'];
        $MobileGet="SELECT * FROM patient_data where patient_id= '$Patientisd'";
        $runqry= sqlStatement($MobileGet);
         while ($results = sqlFetchArray($runqry))
         {
              $mobileNo=$results['phone_cell'];
              $_SESSION['user_phone']=$mobileNo;
            


         }

          // echo $_SESSION['user_phone'];
          // die();




      if(isset($_POST['GiveAccess'])){
          


          $provider=$_POST['providers'];
           $issues=$_POST['issue'];
           $start_date=$_POST['s_date'];
           $end_date=$_POST['e_date'];
           $patient_name =$result['fname'];
           $Patientid =$result['patient_id'];

           $_SESSION['proiders_name']=$provider;
           $_SESSION['issues']= $issues;
           $_SESSION['start_date']= $start_date;
           $_SESSION['end_date']= $end_date;
            $_SESSION['patient_name']=$patient_name;
             $_SESSION['patient_id']=$Patientid;


 
           
           
              $mobileNumber= $_SESSION['user_phone'];
             // echo $mobileNumber;
             // die();


           
            $rndno=rand(1000, 9999);
            $response = json_decode(send_message($rndno,$mobileNumber));
               // print_r($response);
               // die();


            if ($response->type=='success') {
             // echo "<script> $('#myModal').modal('show');</script>";

            } else {

              $error  = 'Could not send OTP, please try later';
            }

?>


<div class="modal fade" role="dialog" id="myModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">OTP Verification</h4>
        </div>
        <div class="modal-body">
          <div id="otppageload"> 
            <iframe scrolling="no" src="final_access.php" frameborder="0" width="550" height="350" ></iframe>
             </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



<?php

     echo "<script> $('#myModal').modal('show');</script>";
    
                                     
                
                
        
}
// here otp function start
        
// end otp function


        ?>

<!--here start popup section-->

<script type="text/javascript">

function giveAccess()
{
    var error=0;
    if($("#Starts").val()=="")
    {
        alert("Please select start date");
        return false;
        error=1;
    }

   if($("#Ends").val()=="")
    {
        alert("Please select end date");
        return false;
        error=1;
    }
   if($("#providers").val()=="")
    {
        alert("Please Choose Providers");
        return false;
        error=1;
    }
   if($("#dates-field2").val()=="")
    {
        alert("Select Issue type");
        return false;
        error=1;
    }



}   

</script>
<!-- date validation-->
<script type="text/javascript">
$(function () {
    $("#Starts").datepicker({
        numberOfMonths: 2,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#Ends").datepicker("option", "minDate", dt);
        }
    });
    $("#Ends").datepicker({
        numberOfMonths: 2,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#Starts").datepicker("option", "maxDate", dt);
        }
    });
});
</script>





<div class="row collapse" id="give_access">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                            <header class="panel-heading"><?php echo xlt('Give Access'); ?>  </header>
   <form method="post" id="myform" action="" >
  <table border="0" cellpadding="0" cellspacing="0" class="access_table">
    <tbody><tr>

      <td>
        <span class="bold">Start Date: </span>
        <input type="text" size="10" name="s_date" required="" id="Starts" class=" form-control" title="yyyy-mm-dd" >
      </td>

      <td>
        <span class="bold">End Date: </span>
        <input type="text" size="10" name="e_date" id="Ends" class=" form-control" title="yyyy-mm-dd" >
      </td>

      <td>
                <span class="bold">Providers </span>
<select class="form-control" name="providers" id="providers">
    <option value="">Choose Providers</option>


    <?php
    
    $sql = "SELECT * FROM  users";

    $res = sqlStatement($sql);

if (sqlNumRows($res)>0) {
    ?>
    
        
    <?php
    $even=false;
    while ($row = sqlFetchArray($res)) {
        
       
        echo "<option value='".$row['id']."'>".$row['fname']." ". $row['lname']." " ; 
           
        
        echo "</option>";


     
}

}


?>
</select> 
      </td>
     
      


                  <td>
                <span class="bold">Issues type </span>



<div class="form-group">

        <select id="dates-field2" class="multiselect-ui form-control" multiple="multiple" name="issue[]" required="">
            <option value="Allergy">Allergy</option>
            <option value="Dental">Dental</option>
            <option value="Medical Problem">Medical Problem</option>
            <option value="Medication">Medication</option>
            <option value="Surgery">Surgery</option>
            
        </select>

</div>
<div>
        
   </div>


<script type="text/javascript">
$(function() {
    $('.multiselect-ui').multiselect({
        includeSelectAllOption: true
    });
});
</script>



      </td>

       <td>
       
        <input type="submit" name="GiveAccess" value="Submit" class="btn btn-primary access" onclick="return giveAccess()"> 
 
       
      </td>

    </tr>
  </tbody></table>
</form>


  
                            <div class="panel-footer"></div>
                        </div>
              

 
 <link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.min.css">
              <script type="text/javascript">
                  $(document).ready(function() {
    $('#example').DataTable();
} );
              </script>
    <script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="assets/js/dataTables.bootstrap.min.js"></script>





                <div class="panel panel-primary table_border">
                            <header class="panel-heading"><?php echo xlt('Access Given'); ?>  </header>
<div class="das_board">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
       <tr>
         <th scope="col">S.N.</th>
      <th scope="col">Start Date</th>
      <th scope="col">End Date</th>
      <th scope="col">Access Key</th>
      <th scope="col">Providers</th>
      <th scope="col">Issues type</th>
      <th scope="col">Action</th>
    </tr>
    </thead>
   <tbody>

    <?php

       if (isset($_GET['DeleteId'])) {
            $Did=$_GET['DeleteId'];
            $qry="Delete from give_access where id='$Did'";
                sqlStatement($qry);
               
           
       }
           $AccessList =$result['patient_id'];
         $sql="SELECT users.fname, users.id,give_access.start_date,give_access.end_date,give_access.issues,give_access.randno,give_access.id,give_access.provider_id FROM users INNER join give_access
            ON users.id=give_access.provider_id WHERE patient_id='$AccessList'";
               

            $res = sqlStatement($sql);
            $count=1;
             while ($row = sqlFetchArray($res)) {

           $start_date=$row['start_date'];
           $end_date=$row['end_date'];
           $provider= $row['fname'];
           $publickey=$row['randno'];
           $issues=$row['issues'];
           
      ?>


    

    <tr>
        <td><?php echo $count++;?></td>
      <td><?php echo $start_date;?></td>
      <td><?php echo $end_date;?></td>
      <td><?php echo $publickey;?> </td>
      <td><?php echo $provider;?> </td>
      <td style="word-break: break-all;"><?php echo $issues;?></td>
      <td><a href="home.php?DeleteId=<?php echo $row['id'];?>" name="delete_access" class="btn btn-primary " onclick = "return confirm('Are you sure want to delete!!')">Delete</a>
    </tr>
    
    <?php }?>
    

  
  </tbody>
</table>

</div>
</div>

</div>
            </div>
            


            <div class="row collapse" id="revokes">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                            <header class="panel-heading"><?php echo xlt('Lab Reports'); ?>  </header>
                            <div id="labresults" class="panel-body"></div>
                            <div class="panel-footer"></div>
                        </div><!-- /.panel -->
                </div><!-- /.col -->
            </div><!-- /.lists -->


            <div class="row collapse" id="patient_final_access">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                            <header class="panel-heading"><?php echo xlt('Give Accesss'); ?>  </header>
                            <div id="final_access" class="panel-body"></div>
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
                <div class="col-sm-10">
                    <div class="panel panel-primary collapse" id="downloadpanel">
                        <header class="panel-heading"> <?php echo xlt('Download Documents'); ?> </header>
                        <!-- <div id="docsdownload" class="panel-body">
                        <?php if ($GLOBALS['portal_onsite_document_download']) { ?>
                            <div>
                                <span class="text"><?php echo xlt('Download all patient documents');?></span>
                                <form name='doc_form' id='doc_form' action='./get_patient_documents.php' method='post'>
                                <input type="button" class="generateDoc_download" value="<?php echo xla('Download'); ?>" />
                                </form>
                            </div>
                        <?php } ?>
                        </div> -->
            

<!-- here download document code -->






     <table class="collection table table-hover"style="margin-top: 10px;">
        <thead>
            <tr class="bg-primary" style="cursor:pointer">
                <th id="header_Id">S.N</th>
                <th id="header_DocType">Document</th>
                <th id="header_CreateDate">Date / Time</th>
                <th id="header_Action">Action</th>
                <th id="header_Download">Download</th>
                
            </tr>
        </thead>
        <tbody>
        <?php
include_once("dbconfig.php");
  if (isset($_GET['did'])) {
        $DocumentDeleteId=$_GET['did'];
         // here delete file from folder and database
        $query1="SELECT * FROM documet_data WHERE id ='$DocumentDeleteId'";
        $RunFile=$con->query($query1);
        $row1=mysqli_fetch_array($RunFile);
        $FileDelete=$row1["file"];     
        unlink("upload/".$FileDelete);
        $Drun="DELETE FROM documet_data where id= '$DocumentDeleteId'";
        $SuccessD=$con->query($Drun);

  }

$sn=1;
$querys="SELECT * FROM documet_data WHERE patient_id='$AccessList'";

$DocumentRun=$con->query($querys);

$rowcount=mysqli_num_rows($DocumentRun);


for($i=1;$i<=$rowcount;$i++)
 {
    $row=mysqli_fetch_array($DocumentRun);
     $DateAndtime=$row["date"];
     $id=$row['id'];
        $document=$row["file"];
    $without_extension_file = substr($document, 0, strrpos($document, "."));



?>
            <tr style="background:white" id="25">
                <td><?php echo $sn++;?></td>
                <td><?php echo $without_extension_file;  ?></td>
                <td><?php echo $DateAndtime; ?></td>
                
                <td><a href="upload/<?php echo $row["file"]; ?>">Download</a></td>
                <td><a class="btn btn-danger"href="home.php?did=<?php echo $id;?>" onclick="return confirm('Are You Sure Want To delete!!')">Delete</td>
                
            </tr>
        
            <?php }?>
        
        </tbody>
        </table>




                        <!-- /.panel-body -->
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
            
                
    
                <div class="col-sm-12 mainsec">
                <div class="panel panel-primary collapse" id="account">
                        <header class="panel-heading"><i class="fa fa-user mr-10" aria-hidden="true"></i><?php echo xlt('Profile'); ?>
                            <?php if ($pending) {
                    echo '<button type="button" id="editDems" class="btn btn-danger btn-xs pull-right" style="color:white;font-size:14px">' . xlt('Pending Review') . '</button>';
} else {
    echo '<button type="button" class="btn btn-success btn-xs pull-right" data-toggle="modal" data-target="#edit_profile_model" style="color:white;font-size:14px">' . xlt('Complete Your Profile To Get Free 3 WNCT Token') . '</button>';
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
<script type="text/javascript">
 
$(document).ready(function () {
 // $( function() {
    $( ".datepickerN" ).datepicker({
  minDate: new Date('<?php echo $atearray[0];?>')
});
  //} );
     
          
    });
</script> 

  
<!--here start popup section-->





           <?php
                 
                     
          $PatientId =$result['patient_id'];
          $ProfileGet="SELECT * FROM patient_data where patient_id= 
          '$PatientId'";
          $ProfileRun= sqlStatement($ProfileGet);
          while ($ProfileRow = sqlFetchArray($ProfileRun))
     {
            $pfname=$ProfileRow['fname'];
            $pmname=$ProfileRow['mname'];
            $plname=$ProfileRow['lname'];
            $Patinetids=$ProfileRow['patient_id'];
            $pdob=$ProfileRow['DOB'];
            $pss=$ProfileRow['ss'];
            $psex=$ProfileRow['sex']; 
            $pstreet=$ProfileRow['street']; 
            $pcity=$ProfileRow['city'];
            $pstate=$ProfileRow['state'];
            $postalcode=$ProfileRow['postal_code'];
            $country=$ProfileRow['country_code'];
            $home_phone=$ProfileRow['phone_home'];
            $phone_biz=$ProfileRow['phone_biz'];
            $phone_cell=$ProfileRow['phone_cell'];
            $phone_contact=$ProfileRow['phone_contact'];
            $contact_relationship=$ProfileRow['contact_relationship'];
            $date=$ProfileRow['date'];
            $email=$ProfileRow['email'];
            $email_direct=$ProfileRow['email_direct'];
            $language=$ProfileRow['language'];
            $race=$ProfileRow['race'];
            $ethnicity=$ProfileRow['ethnicity'];
            $religion=$ProfileRow['religion'];
            $family_size=$ProfileRow['family_size'];
            $regdate=$ProfileRow['regdate'];
            $mothersname=$ProfileRow['mothersname'];
            $guardiansname=$ProfileRow['guardiansname'];
            $hipaa_message=$ProfileRow['hipaa_message'];
            $hipaa_notice=$ProfileRow['hipaa_notice'];
            $hipaa_allowsms=$ProfileRow['hipaa_allowsms'];
            $hipaa_allowemail=$ProfileRow['hipaa_allowemail'];
            $allow_patient_portal=$ProfileRow['allow_patient_portal'];
            $allow_health_info_ex=$ProfileRow['allow_health_info_ex'];
            $allow_imm_info_share=$ProfileRow['allow_imm_info_share'];
            $allow_imm_reg_use=$ProfileRow['allow_imm_reg_use'];


         }

           ?>

 <?php

                   if ($_POST['profile_update']) {


         $ProfileUpdate="UPDATE patient_data SET 
          fname = '".$_POST['fname'] ."',
          mname = '".$_POST['mname'] ."',
          lname = '".$_POST['lname'] ."',
          DOB   = '".$_POST['dob']."',
          ss    = '".$_POST['ss']."',
          sex   = '".$_POST['sex']."',
          street= '".$_POST['street']."',
          city  = '".$_POST['city']."',
          state = '".$_POST['state']."',
          postal_code    = '".$_POST['postal_code']."',
          country_code = '".$_POST['country_code']."',
          phone_home    = '".$_POST['home_phone']."',
          phone_biz    = '".$_POST['phone_biz']."',
          phone_cell = '".$_POST['phone_cell']."',
          phone_contact    = '".$_POST['phone_contact']."',
          contact_relationship    = '".$_POST['contact_relationship']."',
          date    = '".$_POST['date']."',
          email    = '".$_POST['email']."',
          email_direct    = '".$_POST['email_direct']."',
          language    = '".$_POST['language']."',
          race    = '".$_POST['race']."',
          ethnicity    = '".$_POST['ethnicity']."',
          family_size    = '".$_POST['family_size']."',
          mothersname    = '".$_POST['mothersname']."',
          guardiansname    = '".$_POST['guardiansname']."',
          hipaa_message    ='".$_POST['hipaa_message']."',
          hipaa_notice    = '".$_POST['hipaaNotice']."',
          hipaa_allowsms    ='".$_POST['hipaaAllowsms']."',
          hipaa_allowemail    ='".$_POST['hipaaAllowemail']."',
          allow_patient_portal    = '".$_POST['allowPatientPortal']."',
          allow_health_info_ex    ='".$_POST['allowHealthInfoEx']."',
          allow_imm_info_share    = '".$_POST['allowImmInfoShare']."',
          allow_imm_reg_use    ='".$_POST['allowImmRegUse']."'
      




          WHERE patient_id='$PatientId'";


          $pupdate=sqlStatement($ProfileUpdate);

          if ($pupdate) {
              echo "<script>alert('success')</script>";
          }
         else{

            echo "<script>alert('error')</script>";
         }





 }




       ?>




                     
                       
                  








<div id="edit_profile_model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Your Profile</h4>
      </div>
      <div class="modal-body">
        <form class="form-inline" action="" method="post">
            <fieldset>
                <!-- <div class="form-group inline" id="idInputContainer">
                    <label class="control-label" for="id">Id</label>
                    <div class="controls inline-inputs">
                        <span class="form-control uneditable-input" id="id">42</span>
                        <span class="help-inline"></span>
                    </div>
                </div> -->
            
            <div class="form-group inline" id="titleInputContainer">
                <label class="control-label" for="title">Title</label><br>
                <div class="controls inline-inputs">
                    <select name="title" id="title" class="form-control form-control" title="Title"><option value="">Unassigned</option><option value="Mr." selected="">Mr.</option>
<option value="Mrs.">Mrs.</option>
<option value="Ms.">Ms.</option>
<option value="Dr.">Dr.</option>
</select>                 <span class="help-inline"></span>
                </div>
            </div>

            <!-- <div class="form-group inline" id="financialInputContainer">
                    <label class="control-label" for="financial">Financial</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="financial" placeholder="Financial" value="">
                        <span class="help-inline"></span>
                    </div>
                </div> -->
                <div class="form-group inline" id="fnameInputContainer">
                    <label class="control-label" for="fname">First</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="fname" required="" placeholder="First Name" name="fname" value="<?php echo $pfname;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="mnameInputContainer">
                    <label class="control-label" for="mname">Middle</label>
                    <div class="controls inline-inputs">
                        <input type="text" name="mname" class="form-control" id="mname" placeholder="Middle Name" value="<?php echo $pmname;?>" style="color: blue; font-weight: normal;">
                        
                    </div>
                </div>
                <div class="form-group inline" id="lnameInputContainer">
                    <label class="control-label" for="lname">Last</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="lname" required="" name="lname" placeholder="Last Name" value="<?php echo $plname;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                
                <div class="form-group inline" id="pubpidInputContainer">
                    <label class="control-label" for="pubpid">Public Patient Id</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="pubpid" disabled="" value="<?php echo $Patinetids;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="dobInputContainer">
                    <label class="control-label" for="dob">Birth Date</label>
                    <div class="controls inline-inputs">
                        <div class="input-group">
                            <input id="dob" type="text" name="dob" required="" class="form-control jquery-date-picker" placeholder="I know but we need it!" value="<?php echo $pdob;?>">
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="ssInputContainer">
                    <label class="control-label" for="ss">SSN</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" name="ss" id="ss" title="###-##-####" placeholder="Social Security(Optional)" value="<?php echo $pss;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="sexInputContainer">
                    <label class="control-label" for="sex">Gender</label><br>
                    <div class="controls inline-inputs">

                        <div class="input-group">
                            <input id="sex" name="sex" type="text" required="" class="form-control jquery-date-picker" placeholder="I know but we need it!" value="<?php echo $psex;?>">
                        </div>
                                          <span class="help-inline"></span>
                    </div>
                </div>
                <!--<div class="form-group inline" id="pharmacyIdInputContainer">
                    <label class="control-label" for="pharmacyId">Pharmacy Id</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="pharmacyId" placeholder="Pharmacy Id" value="0">
                        <span class="help-inline"></span>
                    </div>
                </div>-->
                
                <div class="form-group inline" id="streetInputContainer">
                    <label class="control-label" for="street">Street</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="street" required="" name="street" placeholder="Street" value="<?php echo $pstreet;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
            <div class="form-group inline" id="cityInputContainer">
                    <label class="control-label" for="city">City</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="city" required="" placeholder="City" name="city" value="<?php echo $pcity;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
            <div class="form-group inline" id="stateInputContainer">
                <label class="control-label" for="state">State</label><br>
                
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="state" placeholder="State" name="state" value="<?php echo $pstate;?>">
                        <span class="help-inline"></span>
                    </div>
            </div>
                <div class="form-group inline" id="postalCodeInputContainer">
                    <label class="control-label" for="postalCode">Postal Code</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="postalCode" placeholder="Postal Code" name="postal_code" value="<?php echo $postalcode;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="countyInputContainer">
                    <label class="control-label" for="county">County</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="county" placeholder="County" name="county" value="<?php 
                        echo  $country;?>">
                       
                        <span class="help-inline"></span>
                    </div>
                </div>
             
                <div class="form-group inline" id="phoneHomeInputContainer">
                    <label class="control-label" for="phoneHome">Home Phone</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="phoneHome" name="home_phone" placeholder="Phone Home" value="<?php echo $home_phone;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="phoneBizInputContainer">
                    <label class="control-label" for="phoneBiz">Business Phone</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="phoneBiz" name="phone_biz" placeholder="Phone Biz" value="<?php echo $phone_biz;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="phoneCellInputContainer">
                    <label class="control-label" for="phoneCell">Cell Phone</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="phoneCell" name="phone_cell" placeholder="Phone Cell" value="<?php echo $phone_cell;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="phoneContactInputContainer">
                    <label class="control-label" for="phoneContact">Contact or Notify Phone</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="phoneContact" name="phone_contact" placeholder="Phone Contact" value="<?php echo $phone_contact;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="contactRelationshipInputContainer">
                    <label class="control-label" for="contactRelationship">Contact Relationship</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="contactRelationship" placeholder="Contact Relationship" name="contact_relationship" value="<?php echo $contact_relationship;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="dateInputContainer">
                    <label class="control-label" for="date">Date</label>
                    <div class="controls inline-inputs">
                        <div class="input-group">
                            <input disabled="" id="date" name="date" type="text" class="form-control jquery-date-time-picker" value="<?php echo $date; ?>">
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div><!-- -->
               
                <div class="form-group inline" id="emailInputContainer">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls inline-inputs">
                        <input type="email" name="email" class="form-control" id="email" required="" placeholder="Email" value="<?php echo $email;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline dynhide" id="emailDirectInputContainer">
                    <label class="control-label" for="emailDirect">Email Direct</label>
                    <div class="controls inline-inputs">
                        <input type="text" name="email_direct" class="form-control" id="emailDirect" placeholder="Direct Email" value="<?php echo $email_direct;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="languageInputContainer">
                    <label class="control-label" for="language">Preferred Language</label>
                    <div class="controls inline-inputs">
                        <input type="text" name="language" class="form-control" id="language" placeholder="Language" value="<?php echo $language;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
            <div class="form-group inline" id="raceInputContainer">
                <label class="control-label" for="race">Race</label><br>
                <div class="controls inline-inputs">
                    <input type="text" name="race" class="form-control" id="race" placeholder="race" value="<?php echo $race;?>">

                                   <span class="help-inline"></span>
                </div>
           </div>
           <div class="form-group inline" id="ethnicityInputContainer">
                    <label class="control-label" for="ethnicity">Ethnicity</label><br>
                    <div class="controls inline-inputs">
                        <input type="text" name="ethnicity" class="form-control" id="ethnicity" placeholder="ethnicity" value="<?php echo $ethnicity;?>">

                     <span class="help-inline"></span>
                    </div>
            </div>
            <div class="form-group inline" id="religionInputContainer">
                <label class="control-label" for="religion">Religion</label><br>
                <div class="controls inline-inputs">
                    <input type="text" class="form-control" name="religion" id="religion" placeholder="religion" value="<?php echo $religion;?>">
                  
                                    <span class="help-inline"></span>
                </div>
            </div>
            <div class="form-group inline dynhide" id="familySizeInputContainer">
                    <label class="control-label" for="familySize">Family Size</label>
                    <div class="controls inline-inputs">
                        <input type="text" name="family_size" class="form-control" id="familySize" placeholder="Family Size" value="<?php echo $family_size;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
               
                <div class="form-group inline dynhide" id="regdateInputContainer">
                    <label class="control-label" for="regdate">Registration Date</label>
                    <div class="controls inline-inputs">
                        <div class="input-group">
                            <input disabled="" id="regdate" type="text" class="form-control jquery-date-picker" value="<?php echo $regdate; ?>">
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="mothersnameInputContainer">
                    <label class="control-label" for="mothersname">Mothers Name</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="mothersname" name="mothersname" placeholder="Mothers Name" value="<?php echo $mothersname;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline dynhide" id="guardiansnameInputContainer">
                    <label class="control-label" for="guardiansname">Guardians Name</label>
                    <div class="controls inline-inputs">
                        <input type="text" name="guardiansname" class="form-control" id="guardiansname" placeholder="Guardians Name" value="<?php echo $guardiansname; ?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
               
                
                <div class="form-group inline" id="hipaaNoticeInputContainer">
                    <label class="control-label" for="hipaaNotice">Allow Notice</label>
                   
                    <div class="controls inline-inputs">
                            <label class="btn btn-default btn-gradient btn-sm"><input id="hipaaNotice0" name="hipaaNotice" type="radio" value="NO"<?php 
                            if($hipaa_notice=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input id="hipaaNotice1" name="hipaaNotice" type="radio" value="YES"<?php 
                            if($hipaa_notice=="YES"){echo "checked";}?>>YES</label>
                           
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline dynhide" id="hipaaMessageInputContainer">
                    <label class="control-label" for="hipaaMessage">Hipaa Message</label>
                    <div class="controls inline-inputs">
                        <input type="text" class="form-control" id="hipaaMessage" placeholder="Hipaa Message" name="hipaa_message" value="<?php echo $hipaa_message;?>">
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="hipaaAllowsmsInputContainer">
                    <label class="control-label" for="hipaaAllowsms">Allow SMS</label>
                  
                    <div class="controls inline-inputs">
                            <label class="btn btn-default btn-gradient btn-sm"><input id="hipaaAllowsms0" name="hipaaAllowsms" type="radio" value="NO"<?php 
                            if($hipaa_allowsms=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input id="hipaaAllowsms1" name="hipaaAllowsms" type="radio" value="YES"<?php 
                            if($hipaa_allowsms=="YES"){echo "checked";}?>>YES</label>
                           
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="hipaaAllowemailInputContainer">
                    <label class="control-label" for="hipaaAllowemail">Allow Email</label>
                   
                    <div class="controls inline-inputs">
                            <label class="btn btn-default btn-gradient btn-sm"><input id="hipaaAllowemail0" name="hipaaAllowemail" type="radio" value="NO"<?php 
                            if($hipaa_allowemail=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input id="hipaaAllowemail1" name="hipaaAllowemail" type="radio" value="YES"<?php 
                            if($hipaa_allowemail=="YES"){echo "checked";}?>>YES</label>
                           
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="allowImmRegUseInputContainer">
                    <label class="control-label" for="allowImmRegUse">Allow  Registry Use</label>
               
                    <div class="controls inline-inputs">
                            <label class="btn btn-default btn-gradient btn-sm"><input id="allowImmRegUse0" name="allowImmRegUse" type="radio" value="NO"<?php 
                            if($allow_imm_reg_use=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input id="allowImmRegUse1" name="allowImmRegUse" type="radio" value="YES"<?php 
                            if($allow_imm_reg_use=="YES"){echo "checked";}?>>YES</label>
                            
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="allowImmInfoShareInputContainer">
                    <label class="control-label" for="allowImmInfoShare">Allow  Info Share</label>
                   
                    <div class="controls inline-inputs">
                            <label class="btn btn-default btn-gradient btn-sm"><input id="allowImmInfoShare0" name="allowImmInfoShare" type="radio" value="NO"<?php 
                            if($allow_imm_info_share=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input id="allowImmInfoShare1" name="allowImmInfoShare" type="radio" value="YES"<?php 
                            if($allow_imm_info_share=="YES"){echo "checked";}?>>YES</label>
                            
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="allowHealthInfoExInputContainer">
                    <label class="control-label" for="allowHealthInfoEx">Allow  Info Exchange</label>
                    <div class="controls inline-inputs">
                      
                            <label class="btn btn-default btn-gradient btn-sm"><input id="allowHealthInfoEx0" name="allowHealthInfoEx" type="radio" value="NO"<?php 
                            if($allow_health_info_ex=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input id="allowHealthInfoEx1" name="allowHealthInfoEx" type="radio" value="YES"<?php 
                            if($allow_health_info_ex=="YES"){echo "checked";}?>>YES</label>
                           
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group inline" id="allowPatientPortalInputContainer">
                    <label class="control-label" for="allowPatientPortal">Allow Patient Portal</label>
                    <div class="controls inline-inputs">
                            <label class="btn btn-default btn-gradient btn-sm"><input ="" id="allowPatientPortal0" name="allowPatientPortal" type="radio" value="NO"<?php 
                            if($allow_patient_portal=="NO"){echo "checked";}?>>NO</label>
                            <label class="btn btn-default btn-gradient btn-sm"><input ="" id="allowPatientPortal1" name="allowPatientPortal" type="radio" value="YES"<?php 
                            if($allow_patient_portal=="YES"){echo "checked";}?>>YES</label>
                            
                        <span class="help-inline"></span>
                    </div>
                </div>
                
               
            </fieldset>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       <!--  <button type="button" id="savePatientButton" class="btn btn-default" data-dismiss="modal" name="profile_update">Save</button> -->
       <input type="submit" name="profile_update" value="save" class="btn btn-primary" class="btn btn-primary">
      </div>
      </form>
    </div>

  </div>
</div>