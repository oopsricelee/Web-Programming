<?php
include('./classes/DB.php');
include('./classes/Login.php');
if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
} else {
        die('Not logged in');
}

if (isset($_POST['send'])) {

        if (DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver'=>$_GET['receiver']))) {

                DB::query("INSERT INTO messages VALUES ('', :body, :sender, :receiver, 0)", array(':body'=>$_POST['body'], ':sender'=>$userid, ':receiver'=>htmlspecialchars($_GET['receiver'])));
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
    <link rel="stylesheet" href="assets/css/message.css">
</head>
<body>

        <h1>Send a Message</h1>
        <form class="form-group" action="send-message.php?receiver=<?php echo htmlspecialchars($_GET['receiver']); ?>" method="post">
                <textarea class="form-control" name="body" rows="15" cols="80"></textarea>
                <input  class="btn btn-success " type="submit" name="send" value="Send Message">
        </form>


 <div class="footer-dark navbar-fixed-bottom" style="position: relative">
        <footer>
            <div class="container">
                <p class="copyright">Social Network© 2018</p>
            </div>
        </footer>
    </div>

</body>
</html>

