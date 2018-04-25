<?php
include('./classes/DB.php');
include('./classes/Login.php');

$isAdmin = False;
if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id = :userid', array(':userid' => $userid))[0]['username'];
    if (DB::query('SELECT username FROM admins WHERE username=:username', array(':username' => $username))) $isAdmin = True;
} else {
    die("Not logged in.");
}

if (isset($_POST['confirm'])) {

    if (isset($_POST['alldevices'])) {

        DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid' => Login::isLoggedIn()));

    } else {
        if (isset($_COOKIE['SNID'])) {
            DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token' => sha1($_COOKIE['SNID'])));
        }
        setcookie('SNID', '1', time() - 3600);
        setcookie('SNID_', '1', time() - 3600);
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

<div>
    <nav class="navbar navbar-default hidden-xs navigation-clean">
        <div class="container">
            <div class="navbar-header"><a class="navbar-brand navbar-link"
                                          href="profile.php?username=<?php echo $username ?>"><i
                            class="icon ion-ios-people"></i></a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span
                            class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                            class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <form class="navbar-form navbar-left" action="index.php" method="post">
                    <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                        <input class="form-control sbox" name="searchbox" type="text">
                        <ul class="list-group autocomplete" style="position:absolute;width:100%; z-index:100">
                        </ul>
                    </div>
                </form>
                <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
                    <li role="presentation"><a href="index.php">Timeline</a></li>
                    <?php
                    if (Login::isLoggedIn()) {
                        echo "<li role=\"presentation\"><a href=\"my-messages.php\">Messages</a></li>";
                        echo "<li role=\"presentation\"><a href=\"notify.php\">Notifications</a></li>";
                    } else {
                        echo "<li role=\"presentation\"><a href=\"create-account.php\">Register</a></li>";
                        echo "<li role=\"presentation\"><a href=\"login.php\">Login </a></li>";
                    }
                    if ($isAdmin) {
                        echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\" href=\"#\">Admin<span class=\"caret\"></span></a>";
                    } else {
                        echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\" href=\"javascript:void(0);\" id=\"userdrop1\" aria-haspopup=\"true\">User<span class=\"caret\"></span></a>";
                    }
                    ?>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <?php if (Login::isLoggedIn()) {
                            if ($isAdmin) echo "<li role=\"presentation\"><a href=\"userlist.php\">UserList</a></li>";
                            echo "<li role=\"presentation\"><a href=\"logout.php\">Logout </a></li>";
                        } else {
                            echo "<li role=\"presentation\"><a href=\"login.php\">Login </a></li>";
                        }
                        ?>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container">
    <h1>Logout of your Account ?</h1>
    <hr>
    <br>
    <form action="logout.php" method="Post">
        <input class="btn btn-danger" type="submit" name="confirm" value="Confirm">
    </form>
</div>

<div class="footer-dark navbar-fixed-bottom" style="position: absolute">
    <footer>
        <div class="container">
            <p class="copyright">Social Network</p>
        </div>
    </footer>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-animation.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
</body>
</html>


