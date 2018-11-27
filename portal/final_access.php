<?php

/**
 * Patient Portal Amendments
 *
 * Copyright (C) 2014 Ensoftek
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Hema Bandaru <hemab@drcloudemr.com>
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link    http://www.open-emr.org
 */
 require_once("verify_session.php");
  require_once("Giveaccess_api.php");
 include_once("otpfunctions.php");
  require_once("$srcdir/patient.inc");
 require_once("lib/portal_mail.inc");
 ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="/public/assets/bootstrap-3-3-4/dist/css/popup_font.min.css?v=41" type="text/css">
  <title></title>
 <style type="text/css">
  body {
    font-family: "Poppins", sans-serif !important;
}
.text-center{
  text-align: center;
}
.btn.btn-primary {
    background-color: #00a7c2;
    color: #fff;
    padding: 8px 20px;
    border: none;
    font-size: 14px;
    font-weight: 700;
    border-radius: 4px;
        cursor: pointer;
}
 </style>

</head>
<body>

    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 text-center">
            <img src="/public/otp.png" class="img-responsive" style="width:100px;margin:0 auto;">
            <h3 class="text-center">Verify your mobile number</h3>
            <p class="text-center"> Thanks for giving your details. An OTP has been sent to your Mobile Number. Please enter Your OTP Number</p>  

             <?php
             session_start();

            
                        if (isset($_POST['verify_otp'])) {
    

                          $phone = $_SESSION['user_phone'];
                         // echo  $phone;
                         //   die();

                            $otp = $_POST['otp'];

                            $response = json_decode(check_sent_otp($otp,$phone));

                              // print_r($response);
                              // die();

                            if ($response->type=='success') {
                                
                                
                           $rndnos=rand(10000000000, 99990000000);
                           $insertAccess =
                                    "insert into give_access set 
                                       
                            patient_id='".$_SESSION['patient_id']."',
                            patient_name='".$_SESSION['patient_name']."',
                             patient_otp = '"  .$_POST['otp'] ."',
                           provider_id = '".$_SESSION['proiders_name'] ."',
                            issues = '".implode(',', $_SESSION['issues'])."',
                            start_date = '".$_SESSION['start_date'] ."',
                            end_date = '"  .$_SESSION['end_date'] ."',
                            randno='".$rndnos."'
                              "; 
                      $user_id=sqlInsert($insertAccess);
                      // echo $user_id;
                      // die();
                    $data_array= array(

                             "patient_id"=>$_SESSION['patient_id'],
                             "patient_name"=>$_SESSION['patient_name'],
                             "patient_otp"=>$_SESSION['patient_otp'],
                             "provider_id"=>$_SESSION['provider_id'],
                             "issues"=>implode(',', $_SESSION['issues']),
                             "start_date"=>$_SESSION['start_date'],
                             "end_date"=>$_SESSION['end_date'],
                             "randno"=>$rndnos
                                        
                        );      


                   
                                  
                                  //calling function 
            giveAccess_api( "insert", $data_array , $user_id);
                                        




                       if ($user_id) {
                     //     $insertAccess1="insert into lists set
                     //                 patient_id='".$_SESSION['patient_id']."',
                     //                  access_issues_type = '".implode(',', $_SESSION['issues'])."',
                     //                access_start_date = '".$_SESSION['start_date'] ."',
                     //                access_end_date = '"  .$_SESSION['end_date'] ."'
                                     
                     //          ";
                              
                    // sqlInsert($insertAccess1);

                              echo "<script>alert('Access has been sent to the providers')</script>";
                echo "<script>parent.window.location.href='home.php';</script>";
                      }
                     
                       // data convert into array for api create

                      

                
          
          
               else{


               }    


          }
      else{


          echo "<script>alert('Authentication failed')</script>";
      }

}
   




             ?>




            <form class="text-center verify_form" method="post"  action="">
                <div class="row">                    
                <div class="form-group col-sm-6 col-sm-offset-3">
                   <span style="color:red;"></span>                    <input type="number" class="form-control" name="otp" placeholder="Enter your OTP number" required="" style="width: 100%;
    height: 24px;padding: 6px 0px;font-size: 14px;margin-bottom: 20px;">
                </div>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-primary">Verify</button>

            </form>
            <!-- <form class="text-center verify_form" method="post"  action="">
              <button type="submit" name="resendotp" class="btn btn-primary">Resend Otp</button>
                
            </form> -->
        </div>
       </div>


</body>
</html>