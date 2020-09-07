<?php
	
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'peerxp_tms';
$port = '3308';

$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}
?>