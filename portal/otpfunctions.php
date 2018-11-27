<?php

function send_message($otp_code,$number) {

     

      $auth_key = '83102A2LiBiwetd5bb1ca6a';
      $url = 'https://control.msg91.com/api/sendotp.php?authkey='.$auth_key.'&mobile='.$number.'&message=Your%20otp%20is%20'.$otp_code.'&sender=GRNCOF&otp='.$otp_code;
       if(!function_exists("curl_init")) return "cURL extension is not installed";
          if (trim($url) == "") die("@ERROR@");
          $curl = curl_init($url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
          curl_setopt($curl, CURLOPT_POST, 1);                        
          // curl_setopt($curl, CURLOPT_USERPWD, 'username:password');
          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                    
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);                          
          curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                       
          $response = curl_exec($curl);                                          
          $resultStatus = curl_getinfo($curl);                                   
          
          if($resultStatus['http_code'] == 200) {
             
              // All Ok
          
          } else {

              return json_encode($resultStatus);                            
        }

          $curl = null;
          return utf8_encode($response);

  }

  function check_sent_otp($otp,$mobile) {

    
      $auth_key ='83102A2LiBiwetd5bb1ca6a';
      $url = 'https://control.msg91.com/api/verifyRequestOTP.php?authkey='.$auth_key.'&mobile='.$mobile.'&otp='.$otp;
       if(!function_exists("curl_init")) return "cURL extension is not installed";
          if (trim($url) == "") die("@ERROR@");
          $curl = curl_init($url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
          curl_setopt($curl, CURLOPT_POST, 1);                        
          // curl_setopt($curl, CURLOPT_USERPWD, 'username:password');
          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                    
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);                          
          curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                       
          $response = curl_exec($curl);                                          
          $resultStatus = curl_getinfo($curl);                                   
          
          if($resultStatus['http_code'] == 200) {
             
              // All Ok
          
          } else {

              return json_encode($resultStatus);                            
        }

          $curl = null;
          return utf8_encode($response);

  }

  


function redirect_to($url) {

    echo '<script>window.location = "'.$url.'"</script>';
  }


?>