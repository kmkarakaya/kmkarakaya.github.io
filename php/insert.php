<?php
$servername = "94.73.151.252";
$username = "kutup";
$password = "korhan";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn -> connect_error) 
{
	die("Connection failed: " . $conn -> connect_error);
} 
else
{
	if (!mysqli_select_db($conn, 'korhantest'))
	{
		die("Connection failed: " . $conn -> connect_error);
	}
}
$bid = $_GET['beacon_id'];
$dt = $_GET['date_time'];
$device_id = $_GET['device_id'];
$distance = $_GET['distance'];
$pos = $_GET['pos'];
$dB = $_GET['dB'];

$sql = "INSERT INTO bekci VALUES($bid, '$dt', $distance, '$device_id', '$pos', $dB)";

if ($conn -> query($sql) === TRUE) 
{
	echo "SUCCESS";
}
else
{
	echo "ERROR: " . $sql . "<br>" . $conn -> error;
}

	$conn -> close();
?>
