<?php
/**
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
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
//$ignoreAuth = true;

require_once("dbconfig.php");

require_once("../interface/globals.php");
$getdir = isset($_POST['sel_pt']) ? $_POST['sel_pt'] : 0;
if ($getdir > 0) {
    $tdir = $GLOBALS['OE_SITE_DIR'] .  '/documents/onsite_portal_documents/templates/' . $getdir . '/';
    if (!is_dir($tdir)) {
        if (!mkdir($tdir, 0755, true)) {
            die(xl('Failed to create folder'));
        }
    }
} else {
    $tdir = $GLOBALS['OE_SITE_DIR'] .  '/documents/onsite_portal_documents/templates/';
}

function getAuthUsers()
{
    $response = sqlStatement("SELECT patient_data.pid, Concat_Ws(' ', patient_data.fname, patient_data.lname) as ptname FROM patient_data WHERE allow_patient_portal = 'YES'");
    $resultpd = array ();
    while ($row = sqlFetchArray($response)) {
        $resultpd[] = $row;
    }

    return $resultpd;
}
function getTemplateList($dir)
{
    $retval = array();
    if (substr($dir, -1) != "/") {
        $dir .= "/";
    }

    $d = @dir($dir) or die("File List: Failed opening directory " . text($dir) . " for reading");
    while (false !== ($entry = $d->read())) {
        if ($entry[0] == "." || substr($entry, -3) != 'tpl') {
            continue;
        }

        if (is_dir("$dir$entry")) {
            $retval[] = array(
                    'pathname' => "$dir$entry",
                    'name' => "$entry",
                    'size' => 0,
                    'lastmod' => filemtime("$dir$entry")
            );
        } elseif (is_readable("$dir$entry")) {
            $retval[] = array(
                    'pathname' => "$dir$entry",
                    'name' => "$entry",
                    'size' => filesize("$dir$entry"),
                    'lastmod' => filemtime("$dir$entry")
            );
        }
    }

    $d->close();
    return $retval;
}
?>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo xlt('OpenEMR Portal'); ?> | <?php echo xlt('Import'); ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta name="description" content="Developed By sjpadgett@gmail.com">

<link href="<?php echo $GLOBALS['assets_static_relative']; ?>/font-awesome-4-6-3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<?php if ($_SESSION['language_direction'] == 'rtl') { ?>
    <link href="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-rtl-3-3-4/dist/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
<?php } ?>
<link href="assets/css/style.css?v=<?php echo $v_js_includes; ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-min-1-11-3/index.js" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js" type="text/javascript"></script>
<link  href="<?php echo $GLOBALS['assets_static_relative']; ?>/summernote-0-8-2/dist/summernote.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src="<?php echo $GLOBALS['assets_static_relative']; ?>/summernote-0-8-2/dist/summernote.js"></script>
<script type='text/javascript' src="<?php echo $GLOBALS['assets_static_relative']; ?>/summernote-0-8-2/dist/plugin/nugget/summernote-ext-nugget.js"></script>
</head>

<style>
.modal.modal-wide .modal-dialog {
  width: 75%;
}
.modal-wide .modal-body {
  overflow-y: auto;
}
</style>
<body class="skin-blue">
<div  class='container' style='display: block;'>
<hr>
<h3>Patient Document Upload</h3>





<?php 

if(isset($_REQUEST["document"]))

{  
    $patient=$_POST["patient"];
    $file=$_FILES["file"]["name"];
    $tmp_name=$_FILES["file"]["tmp_name"];
    $path="upload/".$file;
    
    if (file_exists($path)){ 
         echo "<script>alert('File Name already exists. Please Rename your file')</script>";
        
    }
    else
    {
          $file1=explode(".",$file);
    
    $ext=$file1[1];
    // print_r($ext);
    // die();
    $allowed=array("jpg","png","pdf","zip","docx","xlsx");
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
            Document Upload:<input class="btn btn-info" type="file" name="file" required="">

        </div>
    </div>
       


<input class="btn btn-success" type="submit" name="document" value="upload" style="margin-top: 20px;">

</form>



    </div>
    </body>
</html>
