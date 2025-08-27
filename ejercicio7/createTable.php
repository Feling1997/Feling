 <?php
$servername = "localhost";
$username = "root";
$password = "aestudiar";
$dbname = "ejercicio7Alumnos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE ejercicio7Alumnos(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
edad INT(2) NOT NULL,
carrera VARCHAR(30) NOT NULL,
notas DECIMAL(4,2),
promedio DECIMAL(4,2)
)";

if ($conn->query($sql) === TRUE) {
  echo "Table ejercicio7Alumnos created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();
?>