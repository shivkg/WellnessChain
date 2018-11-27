

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
     <title><?php echo xlt('upload document'); ?></title>

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

<div class="title text-center">Patient Documents Upload</div>



<?php 

if(isset($_REQUEST["submit"]))

{  
    $patient=$_POST["patient"];
	$file=$_FILES["file"]["name"];
	$tmp_name=$_FILES["file"]["tmp_name"];
	$path="DocumentUpload/".$file;
	$file1=explode(".",$file);
	$ext=$file1[1];
	$allowed=array("jpg","png","gif","pdf","zip","docx");
	if(in_array($ext,$allowed))
	{
 move_uploaded_file($tmp_name,$path);
 $InsertData="INSERT INTO documet_data(file,patient_id)values('$file','$patient')";
 $run=$con->query($InsertData);
 if ($run) {
     echo "<script>alert('Document Upload successfully')</script>";

 }
 else{
        echo "<script>alert('Error Please Try Again')</script>";

 }
	
	
}
}

?>


<form action="" method="post" enctype="multipart/form-data">


    <div class="row">
        <div class="col-md-4">
       <span class="bold">Patient </span>
<select class="form-control" name="patient" id="patient" required="">
    <option value="">Select Patient</option>


    <?php
    
    $sql = "SELECT * FROM  patient_data";

    $res = sqlStatement($sql);

if (sqlNumRows($res)>0) {
    ?>
    
        
    <?php
    $even=false;
    while ($row = sqlFetchArray($res)) {
        
       
        echo "<option value='".$row['patient_id']."'>".$row['fname']." ". $row['lname']." " ; 
           
        
        echo "</option>";


     
}

}


?>
</select>
        </div>
         <div class="col-md-4">
            File Upload:<input type="file" name="file" required="">
        </div>
    </div>
       


<input type="submit" name="submit" value="upload">

</form>