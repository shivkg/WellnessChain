<?php
//die("vaibhav");
// $data_array = json_decode(file_get_contents('php://input'), true);
// echo $data_array['data']['username'];die;
function appointmentblockChainPush( $action, $data_array , $id )
{ 
		
		 
		
        if($action=="insert")
        {
            $url = "http://18.136.137.49:3000/submit/createAppointment";  
            $data = $data_array;
            $ch = curl_init($url);
            $action ="insert";
        }
        if($action=="update")
        {
            $url = "http://18.136.137.49:3000/submit/updateAppointment";  
            $data = $data_array;
            $ch = curl_init($url);
            $action ="update";
        }
        $data_string = json_encode($data); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)) 
        );
        $contents = curl_exec($ch);
        $contentsN = json_decode($contents);
}
?>