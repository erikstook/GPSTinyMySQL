<html>
<head>
<meta http-equiv="refresh" content="30">
</head>
<body>
<?php
$servername = "localhost";
$username = "erik";
$password = "grodanboll";
$dbname = "weather_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM weatherlight ORDER by id DESC LIMIT 150"
; $result = $conn->query($sql);
if ($result->num_rows > 0) {
	// output data of each row
	echo "<table border='1'><th>ID</th><th>TEMP(deg C)</th><th>Humidity(%)</th><th>Altitude:(m)</th><th>Pressure:(hPa)</th><th>Temp min:(deg C)</th><th>Temp max:(deg C)</th><th>Humidity min:(%)</th><th>Humidity max:(%)</th><th>Pressure min:(hPa)</th><th>Pressure max:(hPa)</th><th>Light max:</th><th>Light min:</th><th>Light:</th><th>TempMax Date:</th><th>TempMin Date:</th><th>DATE TIME</th>";
	while($row = $result->fetch_assoc()) {
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['temp']."</td>";
		echo "<td>".$row['humidity']."</td>";
                echo "<td>".$row['altitude']."</td>";
                echo "<td>".$row['pressure']."</td>";
                echo "<td>".$row['tmin']."</td>";
                echo "<td>".$row['tmax']."</td>";
                echo "<td>".$row['hmin']."</td>";
                echo "<td>".$row['hmax']."</td>";
                echo "<td>".$row['pmin']."</td>";
                echo "<td>".$row['pmax']."</td>";
		echo "<td>".$row['lmin']."</td>";
                echo "<td>".$row['lmax']."</td>";
		echo "<td>".$row['light']."</td>";
    echo "<td>".$row['tmaxdate']."</td>";
    echo "<td>".$row['tmindate']."</td>";



		echo "<td>".$row['timestamp']."</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "0 results";
}
$conn->close();
?>
</body>
</html>
