<?php

function giveAccess_api( $action, $data_array , $id=0)
{ 
        if($action=="insert")
        {
            $url = "http://18.136.137.49:3000/submit/giveAccess";  
            $data = array(
                        'id' => strval($id), 
                        'data'=> $data_array
            );
            // print_r($data);
            // die();
            $ch = curl_init($url);
            $action ="insert";
        }
        // if($action=="update")
        // {
        //     $url = "http://18.136.137.49:3000/api//user/".$id ;  
        //     $data = array(
        //                 'changes'=> array($data_array)
        //     );
        //     $ch = curl_init($url);
        //     $action ="update";
        // }
        $data_string = json_encode($data);        
         if($action == "insert"){                                                                                    
            //$ch = curl_init('http://api.local/rest/users');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)) 
            );
            $contents = curl_exec($ch);  
            // print_r($contents);
            // die();
        }
        //  if($action == "update"){                                                                                    
        //     //$ch = curl_init('http://api.local/rest/users');
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
        //         'Content-Type: application/json',
        //         'Content-Length: ' . strlen($data_string)) 
        //     );
        //     $contents = curl_exec($ch);  
        // }
        $contentsN = json_decode($contents); 
        $address = $contentsN->data->address ;

        $sets = " action_id = '{$id}' , action_table='give_access' , action ='{$action}' ,transaction_key_address='{$address}' , responce_data = '{$contents}' "; 
        
        $sqlPdataBlockChain = "INSERT INTO reference_data_block_chain set ".$sets;
        sqlInsert($sqlPdataBlockChain);
}
?>