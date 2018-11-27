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

 
    <script type="text/javascript" src="<?php echo $GLOBALS['web_root']; ?>/interface/main/messages/js/graph.js?v=<?php echo $v_js_includes; ?>">
    </script>
    <link rel="shortcut icon" href="<?php echo $webroot; ?>/sites/default/favicon.ico" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="OpenEMR: MedExBank">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

<script type="text/javascript" src="textformat.js?v=41"></script>
<!-- <script type="text/javascript">

function myFunction() {
    window.print();
}

</script> -->
<style>
input.form-control.search_field {
    width: 30%;
    display: inline-block;
    padding: 0 15px;
}
.print_btn{
  margin-top: 20px;
}

</style>

<body class="body_top">

<div class="container">
  <a href="" class="pull-right btn btn-default print_btn" onclick="window.print()">Print</a>
  <h1 class="text-center">Patient Report</h1>
  
<?php



if (isset($_GET['pid'] )) {
   $ids=$_GET['pid'];
 
 

  



   
   

   $PatientGet="SELECT * from patient_data where patient_id='$ids'";
    $PatientRun=$con->query($PatientGet);
   while ($PatientRow=mysqli_fetch_object($PatientRun)) {
           $id=$PatientRow->id;
           $PFname=$PatientRow->fname;
           $PLname=$PatientRow->lname;
           $Pdob=$PatientRow->DOB;
           $Pgender=$PatientRow->sex;
           $PIdentification =$PatientRow->patient_id;
           $Psaddress =$PatientRow->street;
           $Pcaddress =$PatientRow->city;
           $Pstataddress =$PatientRow->state;
           $Ppostaladdress =$PatientRow->postal_code;
           $Pphone=$PatientRow->phone_contact;

     
   }
                
  
 
}





?>


<h3>Demographics:</h3>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Date of birth</th>
        <th>Gender</th>
        <th>Identification Numbers</th>
        <th>Address / Phone</th>
      </tr>
    </thead>
    <tbody>
      
      <tr>
        <td><?php echo $PFname.$PLname;?></td>
        <td><?php echo $Pdob;?></td>
        <td><?php echo $Pgender;?></td>
        <td><?php echo $PIdentification;?></td>
  <td><?php echo $Psaddress.$Pcaddress.$Ppostaladdress.$Pstataddress.$Pphone;?></td>
  
       
      </tr>
      
    </tbody>
  </table>
  <h3>Medical Problem:</h3>            
  <table class="table table-bordered">
    <thead>
        <tr>
        <th>Drugs</th>
        <th>Diagnosis</th>
        <th>Recation</th>
        <th>ReferBy</th>
        <th>Comments</th>
      </tr>
    </thead>
    <tbody>
      <?php
   $listdata="SELECT * FROM lists WHERE pid='$id'";
  // echo $listdata;
  // die();
   $ListRun=$con->query($listdata);
   while ($ListRow=mysqli_fetch_object($ListRun))
   {
      $type=$ListRow->type;
   
      if ($type=='medical_problem') {
        $mtitle=$ListRow->title;
        $dignose=$ListRow->diagnosis;
        $recation=$ListRow->reaction;
        $referby=$ListRow->referredby;
        $comments=$ListRow->comments;
     
      


?>
      
      <tr>
        <td><?php echo $mtitle;?></td>
        <td><?php echo $dignose;?></td>
        <td><?php echo $recation;?></td>
        <td><?php echo $referby;?></td>
        <td><?php echo $comments;?></td>
      </tr>
      <?php  }}?>
      
    </tbody>
  </table>

  <h3>Medications:</h3>            
  <table class="table table-bordered">
    <thead>

      <tr>
        <th>Drugs</th>
        <th>Diagnosis</th>
        <th>Recation</th>
        <th>ReferBy</th>
        <th>Comments</th>
      </tr>
    </thead>

      <?php
   $listdata="SELECT * FROM lists WHERE pid='$id'";
  // echo $listdata;
  // die();
   $ListRun=$con->query($listdata);
   while ($ListRow=mysqli_fetch_object($ListRun))
   {
      $type=$ListRow->type;
   
      if ($type=='medication') {
        $mtitle=$ListRow->title;
        $dignose=$ListRow->diagnosis;
        $recation=$ListRow->reaction;
        $referby=$ListRow->referredby;
        $comments=$ListRow->comments;
     
      


?>
    <tbody>
      
      <tr>
        <td><?php echo $mtitle;?></td>
        <td><?php echo $dignose;?></td>
        <td><?php echo $recation;?></td>
        <td><?php echo $referby;?></td>
        <td><?php echo $comments;?></td>
      </tr>
      <?php  }}?>
    </tbody>
  </table>

  

  <h3>allergy:</h3>                   
  <table class="table table-bordered">
    <thead>
      
      <tr>
        <th>Drugs</th>
        <th>Diagnosis</th>
        <th>Recation</th>
        <th>ReferBy</th>
        <th>Comments</th>
      </tr>
    </thead>
    <tbody>

      <?php
   $listdata="SELECT * FROM lists WHERE pid='$id'";
  // echo $listdata;
  // die();
   $ListRun=$con->query($listdata);
   while ($ListRow=mysqli_fetch_object($ListRun))
   {
      $type=$ListRow->type;
   
      if ($type=='allergy') {
        $mtitle=$ListRow->title;
        $dignose=$ListRow->diagnosis;
        $recation=$ListRow->reaction;
        $referby=$ListRow->referredby;
        $comments=$ListRow->comments;
     
      


?>
      
      
      
   <tr>
        <td><?php echo $mtitle;?></td>
        <td><?php echo $dignose;?></td>
        <td><?php echo $recation;?></td>
        <td><?php echo $referby;?></td>
        <td><?php echo $comments;?></td>
      </tr>
          <?php }}?>
    </tbody>
  </table>

  <h3>surgery:</h3>            
  <table class="table table-bordered">
    <thead>
       <tr>
        <th>Drugs</th>
        <th>Diagnosis</th>
        <th>Recation</th>
        <th>ReferBy</th>
        <th>Comments</th>
      </tr>
    </thead>
    <tbody>
       <?php
   $listdata="SELECT * FROM lists WHERE pid='$id'";
  // echo $listdata;
  // die();
   $ListRun=$con->query($listdata);
   while ($ListRow=mysqli_fetch_object($ListRun))
   {
      $type=$ListRow->type;
   
      if ($type=='surgery') {
        $mtitle=$ListRow->title;
        $dignose=$ListRow->diagnosis;
        $recation=$ListRow->reaction;
        $referby=$ListRow->referredby;
        $comments=$ListRow->comments;
     
      


?>
      <tr>
        <td><?php echo $mtitle;?></td>
        <td><?php echo $dignose;?></td>
        <td><?php echo $recation;?></td>
        <td><?php echo $referby;?></td>
        <td><?php echo $comments;?></td>
      </tr>
      <?php }}?>
    </tbody>

  </table>

  <h3>Dental:</h3>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Drugs</th>
        <th>Diagnosis</th>
        <th>Recation</th>
        <th>ReferBy</th>
        <th>Comments</th>
      </tr>
    </thead>
    <tbody>
       <?php
   $listdata="SELECT * FROM lists WHERE pid='$id'";
  // echo $listdata;
  // die();
   $ListRun=$con->query($listdata);
   while ($ListRow=mysqli_fetch_object($ListRun))
   {
      $type=$ListRow->type;
   
      if ($type=='dental') {
        $mtitle=$ListRow->title;
        $dignose=$ListRow->diagnosis;
        $recation=$ListRow->reaction;
        $referby=$ListRow->referredby;
        $comments=$ListRow->comments;
     
      


?>
       <tr>
        <td><?php echo $mtitle;?></td>
        <td><?php echo $dignose;?></td>
        <td><?php echo $recation;?></td>
        <td><?php echo $referby;?></td>
        <td><?php echo $comments;?></td>
      </tr>
      <?php }}?>
     
    </tbody>
    
  </table>
 
</div>

</html>
 