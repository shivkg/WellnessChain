<?php

require_once 'includes/configure.php'; 
if(!empty($_GET['tabelName']))
{
	$tbl=$_GET['tabelName'];
	$strQuery = "SELECT * FROM {$tbl}";
	$db = new myclass;
	echo  json_encode($db->sql_query($strQuery));
}else
{
	echo "<strong>Please send parameter tabelName  all table name listed Below:"."</br></br></strong>";
	
	$strQuery = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE  TABLE_SCHEMA='emr'";
	$db = new myclass;
	foreach  ($db->sql_query($strQuery) as $tblname)
	{
		print_r($tblname->TABLE_NAME."<br>");
	}
	
	
}
 ?>