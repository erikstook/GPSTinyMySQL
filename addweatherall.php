
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
$pmax = substr($temp, $pos9 + 1, (strlen($temp) - $pos9) - 1);
echo $pmax;
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
$sql = "INSERT INTO weatherall(temp, humidity, altitude, pressure, tmin, tmax, hmin, hmax, pmin, pmax) VALUES ($temperature, $humidity, $altitude, $pressure, $tmin, $tmax, $hmin, $hmax, $pmin, $pmax);"; 
if ($conn->query($sql) === TRUE) {
    echo "Weather Saved Successfully!";
} else {
    echo " ErrorQ:" . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
