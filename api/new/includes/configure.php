<?php

//error_reporting(1);
set_time_limit(0);
 	
	ini_set("display_errors", '1');
    //ini_set("error_reporting",E_ALL);   

class myclass {
    function myclass()   {    
        $user = "root";
        $pass = "Jaipur@123";
        $server = "localhost";
        $dbase = "emr";
		
 

         $conn = @mysql_connect($server,$user,$pass);
           if(!$conn)
        {
            echo ($conn);
        }
        if(!@mysql_select_db($dbase,$conn))
        {
            echo ("Dbase Select failed");
        }
        $this->CONN = $conn;
        return true;
    }
    function close()   {   
        $conn = $this->CONN ;
        $close = mysql_close($conn);
        if(!$close)
        {
            echo ("Connection close failed");
        }
        return true;
    }   
	function sql_query($sql="")   {    
        if(empty($sql))
        {
            return false;
        }
        if(empty($this->CONN))
        {
            return false;
        }
        $conn = $this->CONN;
        $results = mysql_query($sql,$conn) or die("Query Failed..<hr>" . mysql_error());
        if(!$results)
        {   
            $message = "Bad Query !";
            echo ($message);
            return false;
        }
        if(!(eregi("^select",$sql) || eregi("^show",$sql)))
        {
            return true;
        }
        else
        {
            $count = 0;
            $data = array();
            while($row = mysql_fetch_object($results))
            {
                $data[$count] = $row;
                $count++;
            }
            mysql_free_result($results);
            return $data;
         }
    }      
} 
$obj = new myclass();
?>