<?php
$host ='proxysql'; // Menghubungkan lewat ProxySQL/container DB
$user  ='root';
$pass ='rootpassword';
$db ='kedaikopi';

$conn =new mysqli($host,$use.$$pass,$db);

if($conn->connect_error) {
    die("koneksi gagal: ".$conn->connect_error);
}
?>
