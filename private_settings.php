<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="assets/css/Highlight-Clean.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
    <title>Private Settings</title>
</head>
<body>

<?php

include('./classes/DB.php');
include('./classes/Login.php');

$public = 0;
$private = 0;
$friends = 0;
$fofs = 0;


if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id = :userid', array(':userid' => $user_id))[0]['username'];
    if (isset($_POST['setting'])) {
        $setting = $_POST['setting'];
        switch ($setting) {
            case 'public':
                $public = 1;
                DB::query('UPDATE private_settings SET public = 1, private = 0, friends = 0,fofs = 0 WHERE user_id = :user_id', array(':user_id' => $user_id));
                break;

            case 'private':
                $private = 1;
                DB::query('UPDATE private_settings SET public = 0, private = 1, friends = 0,fofs = 0 WHERE user_id = :user_id', array(':user_id' => $user_id));
                break;

            case 'friends':
                DB::query('UPDATE private_settings SET public = 0, private = 0, friends = 1,fofs = 0 WHERE user_id = :user_id', array(':user_id' => $user_id));
                break;
            case 'fofs':
                DB::query('UPDATE private_settings SET public = 0, private = 0, friends = 0,fofs = 1 WHERE user_id = :user_id', array(':user_id' => $user_id));
                break;

            default:

                break;
        }

        echo "Change Private Setting Succefully !";
    }


} else {
    echo 'Not logged in';
}


?>

<div>
    <nav class="navbar navbar-default hidden-xs navigation-clean">
        <div class="container">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="#"><i
                            class="icon ion-ios-navigate"></i></a>
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
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userdrop1">
                        <li role="presentation"><a href="profile.php?username=<?php echo($username); ?>">My Profile</a>
                        </li>
                        <li class="divider" role="presentation"></li>
                        <?php if (Login::isLoggedIn()) {
                            echo "<li role=\"presentation\"><a href=\"logout.php\">Logout </a></li>";
                        } else {
                            echo "<li role=\"presentation\"><a href=\"login.php\">Login </a></li>";
                        }
                        if ($isAdmin) echo "<li role=\"presentation\"><a href=\"userlist.php\">UserList</a></li>";
                        ?>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container">
    <form action="private_settings.php" method="Post">
        <div class="radio">
            <label><input type="radio" name="setting" value="public" checked> Public</label>
        </div>
        <div class="radio">
            <label><input type="radio" name="setting" value="private"> Private</label>
        </div>
        <div class="radio ">
            <label><input type="radio" name="setting"><input type="radio" name="setting" value="friends"> Friends
                Only</label>
        </div>

        <div class="radio ">
            <label><input type="radio" name="setting" value="fofs"> Friends of friends </label>
        </div>

        <input type="submit" class="btn btn-success" value="Comfirm">

    </form>
</div>

<div class="footer-dark navbar-fixed-bottom" style="position: absolute">
    <footer>
        <div class="container">
            <p class="copyright">Social Network© 2018</p>
        </div>
    </footer>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-animation.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
<script type="text/javascript">

</body>
</html>