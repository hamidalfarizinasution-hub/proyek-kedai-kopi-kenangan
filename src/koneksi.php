<?php
$host = 'proxysql';
$user = 'root';
$password = 'rootpassword';
$database = 'kedaikopi';
$port = 6033;
$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
     die("Koneksi ke Database Gagal: " . $conn->connect_error);
}
echo "<h3>Koneksi ke Database MySQL via ProxySQL berhasil!</h3>"
?>
