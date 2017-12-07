
<?php 
$temp = $_GET['temp'];
$pos1 = strpos($temp, ":", 0);
echo " Pos1:";
echo $pos1;
echo "\r\n";
$pos2 = strpos($temp, ":", $pos1 + 1);
echo " Pos2:";
echo $pos2;
echo "\r\n";
$pos3 = strpos($temp, ":", $pos2 + 1);
echo " Pos3:";
echo $pos3;
echo "\r\n";
$pos4 = strpos($temp, ":", $pos3 + 1);
echo " Pos4:";
echo $pos4;
echo "\r\n";
$pos5 = strpos($temp, ":", $pos4 + 1);
echo " Pos5:";
echo $pos5;
echo "\r\n";
$pos6 = strpos($temp, ":", $pos5 + 1);
echo " Pos6:";
echo $pos6;
echo "\r\n";
$pos7 = strpos($temp, ":", $pos6 + 1);
echo " Pos7:";
echo $pos7;
echo "\r\n";
$pos8 = strpos($temp, ":", $pos7 + 1);
echo " Pos8:";
echo $pos8;
echo "\r\n";
$pos9 = strpos($temp, ":", $pos8 + 1);
echo " Pos9:";
echo $pos9;
echo "\r\n";

$pos10 = strpos($temp, ":", $pos9 + 1);
echo " Pos10:";
echo $pos10;
echo "\r\n";

$pos11 = strpos($temp, ":", $pos10 + 1);
echo " Pos11:";
echo $pos11;
echo "\r\n";

$pos12 = strpos($temp, ":", $pos11 + 1);
echo " Pos12:";
echo $pos12;
echo "\r\n";


echo "Temp:";
$temperature = substr($temp, 0, $pos1 - 1 );
echo $temperature;
echo "\r\n";
echo "Humidity:";
$humidity = substr($temp, $pos1 + 1 , ($pos2 - $pos1) - 1);
echo $humidity;
echo "\r\n";
echo "Altitude:";
$altitude = substr($temp, $pos2 + 1, ($pos3 - $pos2) - 1);
echo $altitude;
echo "\r\n";
echo "Pressure:";
$pressure = substr($temp, $pos3 + 1, ($pos4 - $pos3) - 1);
echo $pressure;
echo "\r\n";
echo "Temp min:";
$tmin = substr($temp, $pos4 + 1, ($pos5 - $pos4) - 1);
echo $tmin;
echo "\r\n";
echo "Temp max:";
$tmax = substr($temp, $pos5 + 1, ($pos6 - $pos5) - 1);
echo $tmax;
echo "\r\n";
echo "Humidity min:";
$hmin = substr($temp,  $pos6 + 1, ($pos7 - $pos6) - 1);
echo $hmin;
echo "\r\n";
echo "Humidity max:";
$hmax = substr($temp, $pos7 + 1, ($pos8 - $pos7) - 1);
echo $hmax;
echo "\r\n";
echo "Pressure min:";
$pmin = substr($temp, $pos8 + 1, ($pos9 - $pos8) - 1);
echo $pmin;
echo "\r\n";
echo "Pressure max:";
$pmax = substr($temp, $pos9 + 1, ($pos10 - $pos9) - 1);
echo $pmax;
echo "\r\n";

echo "Light min:";
$lmin = substr($temp, $pos10 + 1, ($pos11 - $pos10) - 1);
echo $lmin;
echo "\r\n";

echo "Light max:";
$lmax = substr($temp, $pos11 + 1, ($pos12 - $pos11) - 1);
echo $lmax;
echo "\r\n";

echo "Light:";
$light = substr($temp, $pos12 + 1, (strlen($temp) - $pos12) - 1);
echo $light;
echo "\r\n";


$servername = "localhost";
$username = "erik"; 
$password = "grodanboll"; 
$dbname = "weather_db"; // Create connection 
$conn = new mysqli($servername, $username,$password, $dbname); // Checkconnection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$val = $_GET['temp']; 
$val1 =  substr($temp, 6, 5);
//$tmin = 90;
//$tmax = 10;
$sql = "INSERT INTO weatherlight(temp, humidity, altitude, pressure, tmin, tmax, hmin, hmax, pmin, pmax, lmin, lmax, light) VALUES ($temperature, $humidity, $altitude, $pressure, $tmin, $tmax, $hmin, $hmax, $pmin, $pmax, $lmin, $lmax, $light);"; 
if ($conn->query($sql) === TRUE) {
    echo "Weather Saved Successfully!";
} else {
    echo " ErrorQ:" . $sql . "<br>" . $conn->error;
}


//$conn->close(); 
$sqlread = "SELECT * FROM weatherlight;"; 
$result = $conn->query($sqlread); 
$counter = 0; 
$tmin_x = 100; 
$tmax_x = 0; 
$pmin_x = 2000; 
$pmax_x = 0; 
$hmin_x = 500; 
$hmax_x = 0; 
$lmin_x = 5000;
$lmax_x = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) { 
	//echo "id:" .$row["id"]. " tmax:". $row["temp"]. " tmin:". $row["tmin"]. " pmax:". $row["pmax"]."<br>";
	$tmax = $row["tmax"];
	$tmin = $row["tmin"];
	$pmax = $row["pmax"];
	$pmin = $row["pmin"];
	$hmax = $row["hmax"];
	$hmin = $row["hmin"];
	$lmax = $row["lmax"];
	$lmin = $row["lmin"];
	if ($tmax > $tmax_x) $tmax_x = $tmax;
	if ($tmin < $tmin_x) $tmin_x = $tmin;
	if ($pmax > $pmax_x) $pmax_x = $pmax;
	if ($pmin < $pmin_x) $pmin_x = $pmin;
	if ($hmax > $hmax_x) $hmax_x = $hmax;
	if ($hmin < $hmin_x) $hmin_x = $hmin;
	if ($lmax > $lmax_x) $lmax_x = $lmax;
	if ($lmin < $lmin_x) $lmin_x = $lmin;
	$counter++;
    }
} else 
{
    echo "inga results";
}
echo " ID:"; 
echo $counter; echo "\r\n"; 
echo " TMAX:". $tmax_x. " TMIN:". $tmin_x. " PMAX:". $pmax_x. " PMIN:". $pmin_x. " HMAX:". $hmax_x. " HMIN:". $hmin_x. " LMIN:". $lmin_x. " LMAX:". $lmax_x. "\r\n";
$sql = "UPDATE weatherlight SET tmin = $tmin_x, tmax = $tmax_x, pmin = $pmin_x, pmax = $pmax_x, hmin = $hmin_x, hmax = $hmax_x, lmax = $lmax_x, lmin = $lmin_x WHERE id = $counter ;";
	if ($conn->query($sql) === TRUE) {
	    echo "Record updated successfully";
	    echo "\r\n";
	} else 
	{
	    echo "Error updating record: " . $conn->error;
	}
$conn->close();
echo " Connection closed";
?>
