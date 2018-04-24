<?php
include('./classes/DB.php');
include('./classes/Login.php');

$isAdmin = False;
if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id = :userid', array(':userid' => $userid))[0]['username'];
    if (DB::query('SELECT username FROM admins WHERE username=:username', array(':username' => $username))) $isAdmin = True;
} else {
    die('Not logged in');
}

if (isset($_GET['mid'])) {
    $message = DB::query('SELECT * FROM messages WHERE id=:mid AND receiver=:receiver OR sender=:sender', array(':mid' => $_GET['mid'], ':receiver' => $userid, ':sender' => $userid))[0];
    echo '<h1>View Message</h1>';
    echo htmlspecialchars($message['body']);
    echo '<hr />';

    if ($message['sender'] == $userid) {
        $id = $message['receiver'];
    } else {
        $id = $message['sender'];
    }
    DB::query('UPDATE messages SET `read`=1 WHERE id=:mid', array(':mid' => $_GET['mid']));
    ?>
    <form action="send-message.php?receiver=<?php echo $id; ?>" method="post">
        <textarea name="body" rows="8" cols="80"></textarea>
        <input type="submit" name="send" value="Send Message">
    </form>
    <?php
} else {

?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
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
    <h1>My Messages</h1>
    <hr/>
    <?php

    $senderid = DB::query('SELECT * FROM messages WHERE receiver = :receiver', array(':receiver' => $userid))[0]['sender'];
    $messages = DB::query('SELECT messages.*, users.username FROM messages, users WHERE receiver=:receiver AND sender=:sender AND users.id = :sender', array(':receiver' => $userid, ':sender' => $senderid));
    // $messages = DB::query('SELECT messages.*, users.username FROM messages, users WHERE receiver=:receiver OR sender=:sender AND users.id = messages.sender', array(':receiver'=>$userid, ':sender'=>$userid));
    foreach ($messages as $message) {

        if (strlen($message['body']) > 20) {
            $m = substr($message['body'], 0, 20) . " ...";
        } else {
            $m = $message['body'];
        }

        if ($message['read'] == 0) {
            echo "<p><a href='my-messages.php?mid=" . $message['id'] . "'><strong>" . $m . "</strong></a> sent by " . $message['username'] . '</p><hr />';
        } else {
            echo "<p><a href='my-messages.php?mid=" . $message['id'] . "'><strong>" . $m . "</strong></a> sent by " . $message['username'] . '</p><hr />';
        }

    }
    }
    ?>
</div>

<div class="footer-dark navbar-fixed-bottom" style="position: relative">
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

</body>
</html>

