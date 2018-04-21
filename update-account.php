
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 	<title>Update User Account</title>
 </head>
 <body>

 	<form class="form-horizontal" action="update-account.php?userid=<?php echo($userid) ?>" method="post">
 		<div class="form-group">
      <label class="control-label col-sm-2" for="username">Username:</label>
      <div class="col-sm-10">
        <input type="username" class="form-control" id="username" placeholder="Enter username" name="username">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="email">Email:</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">Password:</label>
      <div class="col-sm-10">          
        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" class="btn btn-danger" name="updateaccount" value="Update Account"></input>
      </div>
    </div>
  </form>
</div>

 
 </body>
 </html>






<?php 
include('classes/DB.php');

$userid = $_GET['userid'];
if (isset($_POST['updateaccount'])){
	$userinfo = DB::query('SELECT * FROM users WHERE id = :userid', array(':userid'=>$userid))[0];
	$username = $userinfo['username'];
	$password = $userinfo['password'];
	$email = $userinfo['email'];


    if (isset($_POST['username']) && $_POST['username'] != $username) {
    	$username = $_POST['username'];
    }
    if (isset($_POST['password']) && !password_verify($_POST['password'],$password)) {
    	$password = $_POST['password'];
    }

    if (isset($_POST['email']) && $_POST['email'] != $email) {
    	$email = $_POST['email'];
    }




    if (DB::query('SELECT username FROM users WHERE id = :userid', array(':userid'=>$userid))) {

                if (strlen($username) >= 3 && strlen($username) <= 32) {

                        if (preg_match('/[a-zA-Z0-9_]+/', $username)) {

                                if (strlen($password) >= 6 && strlen($password) <= 60) {

                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                                if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {

                                        DB::query('UPDATE users SET username = :username, password = :password,email = :email WHERE users.id = :userid', array(':username'=>$username,':password'=>password_hash($password, PASSWORD_BCRYPT),':email'=>$email,':userid'=>$userid));
                                        echo "Update User Account Successful!";
                                } else {
                                        echo 'Email in use!';
                                }
                        } else {
                                        echo 'Invalid email!';
                                }
                        } else {
                                echo 'Invalid password!';
                        }
                        } else {
                                echo 'Invalid username';
                        }
                } else {
                        echo 'Invalid username';
                }

        } else {
                echo 'User dost not exist!';
        }






}




 ?>


