<?php
$servername = "94.73.151.252";
$username = "kutup";
$password = "korhan";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn -> connect_error) {
	die("Connection failed: " . $conn -> connect_error);
} else {
	if (!mysqli_select_db($conn, 'korhantest')) {
		//$sql = "CREATE DATABASE IF NOT EXISTS hardestgame_accounts";
		//if (!mysqli_query($mysql, $sql)) {
		//	echo "Error creating database: " . mysqli_error($mysql);
		//}
		die("Connection failed: " . $conn -> connect_error);
	}
	//echo "Connection Succesful"; //happy end.

	//we will insert the following information to our database.
	$ibeacon_id = $_GET['beacon_id'];
	$date = $_GET['date_time'];
	$name = $_GET['name'];
	$distance = $_GET['distance'];
	$imei = $_GET['imei'];

	
	//..............................................adding bekci table...........................................
	$sql = "INSERT INTO bekci (name, beacon_id, date_time, distance,imei)
	  VALUES ('" . $name . "', '" . $ibeacon_id . "', '" . $date . "','" . $distance . "','" . $imei . "')";
	  
	  
	/*..............................................adding bekcinfo table........................................ 
		$dup = sqlsrv_query($conn, "SELECT name FROM bekcinfo WHERE (name='$name') ");
		if(sqlsrv_num_rows($dup) > 0)
		{
		    echo "Already Exists";
		}
		else
		{
 		   $query = "INSERT INTO bekcinfo (name, imei) VALUES ('$name', '$imei')";
 		   sqlsrv_query( $conn, $query );
		}
     */
	if ($conn -> query($sql) === TRUE) {
		echo "SUCCESS";
	} else {
		echo "ERROR: " . $sql . "<br>" . $conn -> error;
	}

	$conn -> close();
}
?>
