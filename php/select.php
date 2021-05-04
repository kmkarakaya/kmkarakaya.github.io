<html>
<head>
    <title>Bekci Table</title>
</head>
<body>
<?php
	$conn = new mysqli("94.73.151.252", "kutup", "korhan", "korhantest");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT * FROM korhantest.bekci ORDER BY date_time DESC, beacon_id";
	$result = $conn->query($sql);
	print "<table border=\"1\">\n<tr>\n<th>beacon_id</th>\n<th>date_time</th>\n<th>distance</th>\n<th>DEVICE_ID</th>\n<th>POSITION</th>\n<th>dB</th></tr>\n";

	while($row = mysqli_fetch_assoc($result))
	{
		print "<tr><td>{$row["beacon_id"]}</td><td>{$row["date_time"]}</td>
		<td>{$row["distance"]}</td><td>{$row["DEVICE_ID"]}</td><td>{$row["POSITION"]}</td><td>{$row["dB"]}</td></tr>\n";
    }
	print "</table><br/>\n";
	$conn->close();
?>
</body>
</html>