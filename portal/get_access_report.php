<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>


    <?php



    include_once("dbconfig.php");
   

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

if(isset($_POST['delet'])){

   $tables=$_POST['dynamictable'];
   

    $sql = 'DROP TABLE '.$tables.'';
   // print_r($sql);
   //  die();

    $sql1=mysqli_query($connection, $sql);

    if ($sql1) {

        
        echo "<script>alert('Table Deleted successfully!!')</script>";

    } else {

        echo 'Error dropping database: ' . mysql_error() . "\n";


    }

    $connection->close();
}
?>

    <form method="post" action="">
        <input type="text" name="dynamictable" ><br><br>
    <input type="submit" class="button" name="delet" value="Delete"/>
</form>
<br></br>




</body>
</html>
