<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>Users</title>
</head>
<body>
	<ul class="list-group">
	<?php 
	include('./classes/DB.php');

	$result = DB::query('SELECT * FROM Users');

	foreach ($result as $row ) {
		$username = $row['username'];
		echo "<li><a href=\"profile.php?username=$username\" class=\"list-group-item\">$username</a></li>";
	}
 ?>
		

	</ul>

</body>
</html>






