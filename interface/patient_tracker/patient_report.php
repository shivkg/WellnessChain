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
     <title><?php echo xlt('Patient Reports'); ?></title>

    <?php Header::setupHeader(['datetime-picker', 'jquery-ui', 'jquery-ui-cupertino', 'opener', 'pure']); ?>

    <script type="text/javascript">
        <?php require_once "$srcdir/restoreSession.php"; ?>
    </script>
    <link rel="stylesheet" href="<?php echo $webroot; ?>/interface/main/messages/css/dash.css?v=<?php echo $v_js_includes; ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['web_root']; ?>/library/css/bootstrap_navbar.css?v=<?php echo $v_js_includes; ?>" type="text/css">

 <link rel="stylesheet" href="/public/assets/datatables.net-dt-1-10-13/css/jquery.dataTables.min.css" type="text/css">
 <script type="text/javascript" src="/public/assets/jquery-min-1-10-2/index.js"></script>
<script type="text/javascript" src="/public/assets/datatables.net-1-10-13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/public/assets/datatables.net-colreorder-1-3-2/js/dataTables.colReorder.min.js"></script>

    <script type="text/javascript" src="<?php echo $GLOBALS['web_root']; ?>/interface/main/messages/js/graph.js?v=<?php echo $v_js_includes; ?>">
    </script>
    <link rel="shortcut icon" href="<?php echo $webroot; ?>/sites/default/favicon.ico" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="OpenEMR: MedExBank">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    



<style>
input.form-control.search_field {
 /*   width: 30%;*/
    display: inline-block;
    padding: 0 15px;
}
.qr_img img {
    width: 50px;
}
.table {
    border: 1px solid #ccc;
}
.qr_code_image.text-center {
    padding: 30px;
}
.close {
    float: right;
    font-size: 21px;
    font-weight: 700;
    line-height: 1;
    color: #000 !important;
    text-shadow: 0 1px 0 #fff;
    filter: alpha(opacity=20);
    opacity: .5;
}
.title {
    font-family: Georgia, serif;
    font-weight: bold;
    padding: 3px 10px;
    text-transform: uppercase;
    line-height: 1.5em;
    color: #455832;
    border-bottom: 2px solid #455832;
   margin: 50px auto;
    width: 70%;
}
</style>
<script type="text/javascript">
            function generateBarCode(strval)
            { 
                //var nric = $('#access_code').val();
                var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + strval + '&amp;size=300x300';
                $('#barcode').attr('src', url);
            }


            $(document).ready(function() {
    $('#example').DataTable();
} );
        </script>

<body class="body_top">
  <section class="das_board">

<div class="title text-center">Access Patient Documents</div>
<table id="example" class="table table-striped table-bordered" style="width:100%">
    

    <?php
// session_start();

     $ProviderId =$_SESSION['authUserID'];
     if ($ProviderId=='1') {?>
   
      <thead>
       <tr>
      <th scope="col">S.N.</th>
      <th scope="col">Start Date</th>
      <th scope="col">End Date</th>
      <th scope="col">Provider Name</th>
      <th scope="col">Patient Id</th>
      <th scope="col">Patient Name</th>
      <th scope="col">Patient Issues</th>
      <th scope="col">Access Key</th>
      <th scope="col">Scan QR Code</th>
      <th scope="col"> Report</th>
    </tr>
    </thead>
   <tbody>
    <?php
    $sql = "SELECT u.fname,u.id,g.patient_id,g.patient_name,g.issues,g.start_date,
    g.end_date,g.randno,g.provider_id FROM users AS u INNER JOIN give_access AS g ON u.id= g.provider_id ";

    $res = sqlStatement($sql);
    $count=1;
     while ($row = sqlFetchArray($res)) {
          
             $Patient_id=$row['patient_id'];
             $patient_name=$row['patient_name'];
             $patient_issues=$row['issues'];
             $start_date=$row['start_date'];
             $end_date=$row['end_date'];
             $randno=$row['randno'];
              $Provider=$row['fname'];
             

        ?>

  
    <tr>
      <td><?php echo $count++;?></td>
      <td><?php echo $start_date;?></td>
      <td><?php echo $end_date;?></td>
      <td><?php echo $Provider;?></td>
      <td><?php echo $Patient_id;?></td>
      <td><?php echo $patient_name;?></td>
      <td style="word-break: break-all;"><?php echo $patient_issues;?></td>
      <td id="access_code"><?php echo $randno;?></td>
       <td><div  class="qr_img" data-toggle="modal" data-target="#qr_code"><a href="#" onclick="generateBarCode('<?php echo $randno;?>')"><img src="/public/qr.png" ></a></div></td>

       <!-- <td><a  href="patient_data_report.php?pid=<?php echo $Patient_id; ?>" download="Patient Report"> Download</a></td> -->
       <td><a  href="patient_data_report.php?pid=<?php echo $Patient_id; ?>"> View</a></td>
    </tr>
 <?php }}

  else{?>


    <thead>
       <tr>
      <th scope="col">S.N.</th>
      <th scope="col">Start Date</th>
      <th scope="col">End Date</th>
      
      <th scope="col">Patient Id</th>
      <th scope="col">Patient Name</th>
      <th scope="col">Patient Issues</th>
      <th scope="col">Access Key</th>
      <th scope="col">Scan QR Code</th>
      <th scope="col"> Report</th>
    </tr>
    </thead>
<?php 
 $sql = "SELECT * FROM  give_access where provider_id='$ProviderId'";
 
    $res = sqlStatement($sql);
    $count=1;
     while ($row = sqlFetchArray($res)) {
          
             $Patient_id=$row['patient_id'];
             $patient_name=$row['patient_name'];
             $patient_issues=$row['issues'];
             $start_date=$row['start_date'];
             $end_date=$row['end_date'];
             $randno=$row['randno'];
             
             

        ?>

  
    <tr>
      <td><?php echo $count++;?></td>
      <td><?php echo $start_date;?></td>
      <td><?php echo $end_date;?></td>
      <td><?php echo $Patient_id;?></td>
      <td><?php echo $patient_name;?></td>
      <td style="word-break: break-all;"><?php echo $patient_issues;?></td>
      <td id="access_code"><?php echo $randno;?></td>
       <td><div  class="qr_img" data-toggle="modal" data-target="#qr_code"><a href="#" onclick="generateBarCode('<?php echo $randno;?>')"><img src="/public/qr.png" ></a></div></td>

       <!-- <td><a  href="patient_data_report.php?pid=<?php echo $Patient_id; ?>" download="Patient Report"> Download</a></td> -->
       <td><a  href="patient_data_report.php?pid=<?php echo $Patient_id; ?>"> View</a></td>
    </tr>


 <?php }}?>




 
  
  </tbody>
</table>

</section>
</body>
</html>
     
     <div class="modal fade" id="qr_code" role="dialog">
    <div class="modal-dialog">
    
 
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">QR Code </h4>
        </div>
        <div class="qr_code_image text-center">
          <input id="text" type="hidden" 
            value="Enter text" style="Width:20%"
            onblur= />  
      
      <img id='barcode' 
            src="https://api.qrserver.com/v1/create-qr-code/?data=&amp;size=100x100" 
            alt="" 
            title="QR Code" 
            width="300" 
            height="300" />

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div> 
  </div>  


                   