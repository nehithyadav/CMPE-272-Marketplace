<?php
$host = 'localhost';
$username = 'root'; // Your database username
$password = 'Password@123'; // Your database password
$database = 'nehith'; // Your database name
$port = 3306; // Default MySQL port

// Connect to MySQL
$link = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($link->connect_error) {
  die("Connection failed: " . $link->connect_error);
}
?>