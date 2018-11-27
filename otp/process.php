<?php
session_start();
include_once("otpfunctions.php");
//Your authentication key
$authKey = "83102A2LiBiwetd5bb1ca6a";
//Multiple mobiles numbers separated by comma
$mobileNumber = $_POST["phone"];
//Sender ID,While using route4 sender id should be 6 characters long.
$senderId = "WNCOTP";
//Your message to send, Add URL encoding here.
$rndno=rand(1000, 9999);
$message = urlencode("otp number.".$rndno);
//Define route 
// $route = "route=4";
//Prepare you post parameters
$postData = array(
'authkey' => $authKey,
'mobiles' => $mobileNumber,
'message' => $message,
'sender' => $senderId
// 'route' => $route
);
//API URL

useotp("sendotp",$postData);

if(isset($_POST['btn-save']))
{
$_SESSION['name']=$_POST['name'];
$_SESSION['email']=$_POST['email'];
$_SESSION['phone']=$_POST['phone'];
$_SESSION['otp']=$rndno;
header( "Location: otp.php" ); } ?>