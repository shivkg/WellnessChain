
<?php
$connect = new MongoClient("http://18.136.137.49:27017");


$db=$connect->openemr;// here use database name.
$collection->$db->blocks;// here use table name.


   
$data = $collection->find();
foreach ($data as $value) {
   print_r($value);
   die();
    
}


// search for produce that is sweet. Taste is a child of Details. 
$sweetQuery = array('Details.Taste' => 'Sweet');
echo "Sweet\n";
$cursor = $collection->find($sweetQuery);
foreach ($cursor as $doc) {
    var_dump($doc);
}

?>