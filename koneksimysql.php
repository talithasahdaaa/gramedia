<?php

define('host', 'localhost');
define('user', 'root');
define('password', '');
define('database', 'gramedia');

$conn = mysqli_connect(host, user, password, database);
if (!$conn) {
    echo "Koneksi Gagal : " . mysqli_connect_error();
    exit();
}
