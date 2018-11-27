<?php

function useotp( $action, $postData)
{ 

	       
			
        if($action=="sendotp")
        {
            $url = "https://control.msg91.com/api/sendhttp.php" ;  
            
			$Data= $postData;
            $ch = curl_init($url);
            $action ="sendotp";
        }
        if($action=="resendotp")
        {
            $url = "http://18.136.137.49:3000/submit/updateHospital" ;  
            $data = $data_array;
            $ch = curl_init($url);
            $action ="resendotp";
        }
         if($action=="verifyotp")
        {
            $url = "http://18.136.137.49:3000/submit/updateHospital" ;  
            $data = $data_array;
            $ch = curl_init($url);
            $action ="verifyotp";
        }
        curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $Data
        //,CURLOPT_FOLLOWLOCATION => true
        ));
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //get response
        $output = curl_exec($ch);
        
        // $address = $contentsN->data->address ;

        
        // $sqlPdataBlockChain = "INSERT INTO reference_data_block_chain set ".$sets;
        // sqlInsert($sqlPdataBlockChain);
}

?>