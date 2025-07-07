<?php

// menggunakan xampp
// define('host', 'localhost');
// define('user', 'root');
// define('password', '');
// define('database', 'androidtalita');

 
 // menggunakan hosting
define('host', 'localhost');
define('user', 'androidtalita');
define('password', 'Talita123');
define('database', 'androidtalita');

$conn = mysqli_connect(host, user, password, database);
if (!$conn) {
    echo "Koneksi Gagal : " . mysqli_connect_error();
    exit();
}
