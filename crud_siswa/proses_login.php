<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

if ($username == "admin" && $password == "admin123") {
    $_SESSION['login'] = true; // <--- TAMBAHKAN BARIS INI
    $_SESSION['username'] = $username;
    
    header("Location: index.php");
    exit();
} else {
    echo "<script>
        alert('Username atau password salah!');
        window.location.href = 'login.php';
    </script>";
}
?>