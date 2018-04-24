<?php
include('./classes/DB.php');
include('./classes/Login.php');

$receiver = htmlspecialchars($_GET['receiver']);

if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id = :userid', array(':userid' => $userid))[0]['username'];
} else {
    die('Not logged in');
}

if (isset($_POST['send'])) {

    if (DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver' => $receiver))) {
//        echo $_GET['receiver'];

        DB::query("INSERT INTO messages VALUES ('', :body, :sender, :receiver, 0)", array(':body' => $_POST['body'], ':sender' => $userid, ':receiver' => $receiver));
        echo "Message Sent!";
    } else {
        die('Invalid ID!');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Message</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
<!--    <link rel="stylesheet" href="assets/css/message.css">-->
</head>
<body>

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
                        echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\" href=\"#\">User<span class=\"caret\"></span></a>";
                    }
                    ?>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
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
    <h1>Send a Message</h1>
    <form class="form-group" action="send-message.php?receiver=<?php echo($receiver); ?>"
          method="Post">
        <textarea class="form-control" name="body" rows="15" cols="80"></textarea>
        <input class="btn btn-success " type="submit" name="send" value="Send Message">
    </form>
</div>


<div class="footer-dark navbar-fixed-bottom" style="position: absolute">
    <footer>
        <div class="container">
            <p class="copyright">Social Network© 2018</p>
        </div>
    </footer>
</div>

</body>
</html>

