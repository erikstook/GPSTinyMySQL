
<?php 
$servername = "localhost";
$username = "erik"; 
$password = "grodanboll"; 
$dbname = "weather_db"; // Create connection 
$conn = new mysqli($servername, $username,$password, $dbname); // Checkconnection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "TRUNCATE TABLE weatherall;"; 
if ($conn->query($sql) === TRUE) {
    echo "Weatherall Deleted Successfully!";
} else {
    echo " ErrorQ:" . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
