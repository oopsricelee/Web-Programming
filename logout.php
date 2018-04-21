<?php
include('./classes/DB.php');
include('./classes/Login.php');

if (!Login::isLoggedIn()) {
        die("Not logged in.");
}

if (isset($_POST['confirm'])) {

        if (isset($_POST['alldevices'])) {

                DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>Login::isLoggedIn()));

        } else {
                if (isset($_COOKIE['SNID'])) {
                        DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
                }
                setcookie('SNID', '1', time()-3600);
                setcookie('SNID_', '1', time()-3600);
        }
        header("Location:index.php");

}

?>
<!DOCTYPE html>
<html>
<head>
        <title>Logout</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/message.css">
</head>
<body>

<h1>Logout of your Account ?</h1>

<hr><br>

<div class="container">
  <form action="logout.php" method="Post">
    <div class="checkbox">
      <label><input type="checkbox" name="alldevices" value="alldevices"> All Devices </label>
    </div>
    <input class="btn btn-danger" type="submit" name="confirm" value="Confirm">
  </form>
</div>




</body>
</html>

