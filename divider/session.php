<?php
session_start();

function checkLoginAdmin(){
    if (!isset($_SESSION['email'])) {
        header("Location: ../login.php");
        exit();
    }
}

function checkLoginUser(){
    if (!isset($_SESSION['email'])) {
        header("Location: ../login.php");
        exit();
    }
}
function login($conn, $email, $password){
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM pengguna WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $email;
        $_SESSION['usertype'] = $user['usertype'];
        return true;
    } else {
        return false;
    }
}
function createAccount($conn, $name, $email, $password){
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $usertype = 'user';

    $sql = "INSERT INTO pengguna (name, email, password, usertype) VALUES ('$name', '$email', '$password', '$usertype')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

?>