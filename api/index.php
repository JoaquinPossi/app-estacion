<?php 
$db = new mysqli("localhost","alumno","alumno","julio");
$sql  = "SELECT * FROM `estaciones` WHERE 1";
$response = $db->query($sql);
$array = $response->fetch_all(MYSQLI_ASSOC);
header("Content-Type: application/json");
echo json_encode($array);
 ?>