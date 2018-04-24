<?php
include_once('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Post.php');
include_once('./classes/Comment.php');
include_once('./classes/Image.php');

$showTimeline = False;
$followingposts = null;
$search = False;
$isAdmin = False;
if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id = :userid', array(':userid' => $userid))[0]['username'];
    if (DB::query('SELECT username FROM admins WHERE username=:username', array(':username' => $username))) $isAdmin = True;
    $followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.username
            FROM users JOIN posts ON users.id = posts.user_id
            JOIN followers
            WHERE posts.privacy = 2
            AND followers.follower_id = posts.user_id
            AND followers.user_id = :userid
            UNION
            SELECT posts.id, posts.body, posts.likes, users.username
            FROM users JOIN posts ON users.id = posts.user_id
            WHERE (posts.privacy = 1 AND posts.user_id = :userid)
            OR posts.privacy = 0', array(':userid' => $userid));
    $showTimeline = True;
} else {
    $followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.username
            FROM users JOIN posts ON users.id = posts.user_id
            WHERE posts.privacy = 0');
    $showTimeline = True;
}




if (isset($_GET['postid'])) {
    Post::likePost($_GET['postid'], $userid);
}
if (isset($_POST['comment'])) {
    if ($_FILES['commentimg']['size'] == 0) {
        Comment::createImgComment($_POST['commentbody'], $_GET['postid'], $userid);
    } else {
        $name = $_FILES['commentimg']['name'];
        $temp = $_FILES['commentimg']['tmp_name'];
        $tp = $_FILES['commentimg']['type'];
        if (($tp == "image/gif") || ($tp == "image/jpeg")
            || ($tp == "image/pjpeg") || ($tp == "image/png")) {
            $commentid = Comment::createImgComment($_POST['commentbody'], $_GET['postid'], $userid);
            Image::uploadImage('commentimg', "UPDATE comments SET commentimg=:commentimg WHERE id=:commentid", array(':commentid' => $commentid));
        } else {
            echo " Video";
            $newloc = 'uploaded/';
            if ($_FILES["file"]["error"] > 0) echo "Error: " . $_FILES["file"]["error"] . "<br />";
            $newloc .= $name;
            move_uploaded_file($temp, $newloc);
            $commentid = Comment::createImgComment($_POST['commentbody'], $_GET['postid'], $userid);
            DB::query("UPDATE comments SET commentimg=:commentimg WHERE id=:commentid", array(':commentid' => $commentid, ':commentimg' => $newloc));

        }

    }
    // Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
}

if (isset($_POST['searchbox'])) {
    $search = True;
    $tosearch = explode(" ", $_POST['searchbox']);
    if (count($tosearch) == 1) {
        $tosearch = str_split($tosearch[0], 2);
    }
    $whereclause = "";
    $paramsarray = array(':username' => '%' . $_POST['searchbox'] . '%');
    for ($i = 0; $i < count($tosearch); $i++) {
        $whereclause .= " OR username LIKE :u$i ";
        $paramsarray[":u$i"] = $tosearch[$i];
    }
    $users = DB::query('SELECT posts.id, posts.body, posts.likes, users.username
         FROM posts,users
         WHERE posts.user_id = users.id AND
         users.username LIKE :username ' . $whereclause . 'ORDER BY id DESC', $paramsarray);

    $whereclause = "";
    $paramsarray = array(':body' => '%' . $_POST['searchbox'] . '%');
    for ($i = 0; $i < count($tosearch); $i++) {
        if ($i % 2) {
            $whereclause .= " OR body LIKE :p$i ";
            $paramsarray[":p$i"] = $tosearch[$i];
        }
    }
    $posts = DB::query('SELECT posts.id, posts.body, posts.likes, users.username 
            FROM posts,users 
            WHERE posts.user_id = users.id AND
            posts.body LIKE :body ' . $whereclause . 'ORDER BY id DESC', $paramsarray);

    $posts = array_merge($users, $posts);

//        $posts = array_intersect($followingposts, $posts);

}

?>


<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
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
                        if ($isAdmin) {
                            echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\" href=\"#\">Admin<span class=\"caret\"></span></a>";
                        } else {
                            echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\" href=\"#\">User<span class=\"caret\"></span></a>";
                        }
                    } else {
                        echo "<li role=\"presentation\"><a href=\"create-account.php\">Register</a></li>";
                        echo "<li role=\"presentation\"><a href=\"login.php\">Login </a></li>";
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
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>


<div class="container">
    <h1>Timeline </h1>
    <hr/>
    </br />
    <div class="timelineposts">
        <?php
        if (Login::isLoggedIn() && !$search) {


            foreach ($followingposts as $post) {

                $profileLink = 'profile.php?username=' . $post['username'] . '';
                echo "<div class=\"lead text-primary\">" . $post['body'] . " 
                          <p>Posted BY <a href=" . $profileLink . ">" . $post['username'] . "</a></p></div>";
                echo "<form action='index.php?postid=" . $post['id'] . "' class=\"form-group\" method='post'>";

                if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid' => $post['id'], ':userid' => $userid))) {

                    echo "<input type='submit' class=\"btn btn-danger\" name='like' value='Like'>";
                } else {
                    echo "<input type='submit' class=\"btn btn-danger\" name='unlike' value='Unlike'>";
                }
                echo "<span class=\"text-danger\">" . $post['likes'] . " likes</span>";
                echo "<hr/>";
                echo Comment::displayComments($post['id']);
                echo "</form>
                    <form action='index.php?postid=" . $post['id'] . "' class=\"form-group\"  method='post'  enctype=\"multipart/form-data\">
                    <textarea  class=\"form-control\" name='commentbody' rows='3' cols='50'></textarea>
                    
                     <div class=\"form-group\">
                               <br />Upload image or video:
                        </div>
                     <div class=\"form-group\">
                               <input type=\"file\" class=\"btn btn-info\" name=\"commentimg\"> 
                        </div>
                        <div class=\"form-group\" >  
                    <input type='submit' name='comment' class=\"btn btn-success\" value='Comment'>
                     </div>


                    </form>
                    ";

                echo "
                    <hr /></br />";


            }
        } else if (!Login::isLoggedIn() && !$search) {
            $publicposts = DB::query('SELECT posts.id, posts.body, posts.likes, posts.privacy, users.username
            FROM users JOIN posts ON users.id = posts.user_id
            WHERE posts.privacy = 0');
            displayposts($publicposts);
        } else {

            displayposts($posts);
        }


        function displayposts($posts)
        {
            foreach ($posts as $post) {

                echo "<div class=\"lead text-primary\">" . $post['body'] . " 
                          <p>Posted By " . $post['username'] . "</p></div>";
                echo "<form action='index.php?postid=" . $post['id'] . "' class=\"form-group\" method='post'>";

                if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid', array(':postid' => $post['id']))) {

                    echo "<input type='submit' class=\"btn btn-danger\" name='like' value='Like'>";
                } else {
                    echo "<input type='submit' class=\"btn btn-danger\" name='unlike' value='Unlike'>";
                }
                echo "<span class=\"text-danger\">" . $post['likes'] . " likes</span>
                    </form>
                    <form action='index.php?postid=" . $post['id'] . "' class=\"form-group\"  method='post'>
                    <textarea  class=\"form-control\" name='commentbody' rows='3' cols='50'></textarea>
                    <div >  
                    <input type='submit' name='comment' class=\"btn btn-success\" value='Comment'>
                     </div>
                   
                    </form>
                    ";
                Comment::displayComments($post['id']);
                echo "
                    <hr /></br />";


            }
        }

        ?>

    </div>
</div>

<div class="footer-dark navbar-fixed-bottom" style="position: relative">
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
<script type="text/javascript">
</body>
</html>

