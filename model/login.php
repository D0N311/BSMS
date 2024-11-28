<?php
include "../server/server.php";

$recaptchaSecret = '6LfA0IwqAAAAAOl4drOOR63o7A1jQC5lPshY1rio';
$recaptchaResponse = $_POST['g-recaptcha-response'];

// Verify the reCAPTCHA response
$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$recaptchaData = [
    'secret' => $recaptchaSecret,
    'response' => $recaptchaResponse
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($recaptchaData)
    ]
];

$context = stream_context_create($options);
$verify = file_get_contents($recaptchaUrl, false, $context);
$captchaSuccess = json_decode($verify)->success;

if (!$captchaSuccess) {
    error_log("CAPTCHA verification failed: " . $verify);
    $_SESSION["message"] = "CAPTCHA verification failed!";
    $_SESSION["status"] = "danger";
    header("location: ../login.php");
    exit();
}

$username = $conn->real_escape_string($_POST["username"]);
$password = $conn->real_escape_string($_POST["password"]);

if (!$username || !$password) {
    $_SESSION["message"] = "Username or password is empty!";
    $_SESSION["status"] = "danger";
    header("location: ../login.php");
    return $conn->close();
}

$hash = sha1($password);
$result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$hash'");
$fetchedData = $result->fetch_assoc();

if (!$fetchedData) {
    $_SESSION["message"] = "Username or Password is incorrect!";
    $_SESSION["status"] = "danger";
    header("location: ../login.php");
    return $conn->close();
}

$_SESSION["id"] = $fetchedData["id"];
$_SESSION["username"] = $fetchedData["username"];
$_SESSION["role"] = $fetchedData["user_type"];
$_SESSION["avatar"] = $fetchedData["avatar"];

$_SESSION["message"] = "You have successfully logged in to Barangay Online System!";
$_SESSION["status"] = "success";

header("location: ../dashboard.php");
return $conn->close();