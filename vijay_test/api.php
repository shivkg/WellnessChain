<?php

function userblockChainPush( $action, $data_array , $id=0, $user_type=NULL )
{ 
        if($action=="create")
        {
            $url = "http://18.136.137.49:3000/api/user/register/".$user_type ;  
            $data = array(
                        'id' => $id, 
                        'data'=> $data_array
            );
            $ch = curl_init($url);
            $action ="insert";
        }

        $data_string = json_encode($data);          
        
        echo "<b>URL</b>:".$url."</br></br>";
        
        echo "<b>Request</b>:". $data_string ."</br></br>";                                                                                                   
        //$ch = curl_init('http://api.local/rest/users');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)) 
        );
        $contents = curl_exec($ch);   

        echo "<b>Response</b>:". $contents ."</br></br>";

        curl_close($ch);     
}

//dummy array 
$data_array = array (
                    "username" =>   'vijayyuva183' ,
                    "password" =>   'NoLongerUsed' ,
                    "fname" =>   'vijay' ,
                    "mname" =>   'kumar' ,
                    "lname" =>   'verma' ,
                    "federaltaxid" =>   '' ,
                    "state_license_number" =>   '' ,
                    "newcrop_user_role" =>   'erxdoctor' ,
                    "physician_type" =>   'physician' ,
                    "main_menu_role" =>   'answering_service' ,
                    "patient_menu_role" =>   'standard' ,
                    "weno_prov_id" =>   '' ,
                    "authorized" =>   '1' ,
                    "info" =>   '' ,
                    "federaldrugid" =>   '' ,
                    "upin" =>   '' ,
                    "npi" =>   '' ,
                    "taxonomy" =>   '207Q00000X' ,
                    "facility_id" =>   '4' ,
                    "specialty" =>   '' ,
                    "see_auth" =>   '3' ,
                    "default_warehouse" =>   '' ,
                    "irnpool" =>   'main' ,
                    "calendar" =>   '1' ,
                    "pwd_expiration_date" =>   '0000-00-00' ,

              );       

//calling function
userblockChainPush( "create", $data_array , 'd8', 'doctor' )
?>