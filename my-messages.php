<?php
include('./classes/DB.php');
include('./classes/Login.php');
if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
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
    <link rel="stylesheet" href="assets/css/message.css">
</head>
<body>
<h1>My Messages</h1>
<hr/>
<?php
$messages = DB::query('SELECT messages.*, users.username FROM messages, users WHERE receiver=:receiver OR sender=:sender AND users.id = messages.sender', array(':receiver' => $userid, ':sender' => $userid));
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

<div class="footer-dark navbar-fixed-bottom" style="position: relative">
    <footer>
        <div class="container">
            <p class="copyright">Social Network© 2018</p>
        </div>
    </footer>
</div>

</body>
</html>

