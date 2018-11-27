<?php
//die("vaibhav");
// $data_array = json_decode(file_get_contents('php://input'), true);
// echo $data_array['data']['username'];die;
function facilityblockChainPush( $action, $data_array , $id=0, $user_type=NULL )
{ 
        if($action=="insert")
        {
            $url = "http://18.136.137.49:3000/submit/createHospital" ;  
            $data =  $data_array;
            $ch = curl_init($url);
            $action ="insert";
        }
        if($action=="update")
        {
            $url = "http://18.136.137.49:3000/submit/updateHospital" ;  
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
        //echo  $contents;die("vijay");
        $address = $contentsN->data->address ;

        $sets = " action_id = '{$id}' , action_table='hospital' , action ='{$action}' ,transaction_key_address='{$address}' , responce_data = '{$contents}' "; 
        
        $sqlPdataBlockChain = "INSERT INTO reference_data_block_chain set ".$sets;
        sqlInsert($sqlPdataBlockChain);
}
?>