<?php

function CallAPI($method, $api, $data,$type) {
    $url = "http://18.136.137.49:3000/api/hospital/" . $api;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    switch ($method) {
        case "GET":
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            break;
        case "POST":
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE"); 
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
    }
    $response = curl_exec($curl);
    $contentsN = json_decode($response); 
        $address = $contentsN->data->address ;
        if($action=="create"){
            die("Create");
        $sets = " action_id = '{$id}' , action_table='users' , action ='{$action}' ,transaction_key_address='{$address}' , responce_data = '{$contents}' "; 
        
        $sqlPdataBlockChain = "INSERT INTO reference_data_block_chain set ".$sets;
        sqlInsert($sqlPdataBlockChain); 
        }


    ?>