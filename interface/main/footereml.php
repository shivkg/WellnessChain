<html>
<head>
	

</head>
<body>


	<?php


$ToEmailAddress = 'shivk.gts@gmail.com';


$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$EmailSubject ='Alert';

$EmailBody = " Wellness chian website running on this url=".$actual_link;


if( empty($_GET) )
{
   $_GET['no_info'] = 'No information received.';
   // echo 'Email sent.';
}
if( ! $EmailBody ) { $EmailBody = ''; }
ksort($_GET);
foreach($_GET as $k => $v) { $EmailBody .= "\n\n$k\n\t$v"; }
mail($ToEmailAddress,$EmailSubject,$EmailBody,"From: $ToEmailAddress");
exit;
?>


</body>
</html>