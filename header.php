

<div>
<header class="hidden-sm hidden-md hidden-lg">
    <div class="searchbox">
        <form>
            <h1 class="text-left">Social Network</h1>
            <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                <input class="form-control" type="text">
            </div>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">
                    MENU <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li role="presentation"><a href="#">My Profile</a></li>
                    <li class="divider" role="presentation"></li>
                    <li role="presentation"><a href="#">Timeline </a></li>
                    <li role="presentation"><a href="#">Messages </a></li>
                    <li role="presentation"><a href="#">Notifications </a></li>
                    <li role="presentation"><a href="#">My Account</a></li>
                    <li role="presentation"><a href="#">Logout </a></li>
                </ul>
            </div>
        </form>
    </div>
    <hr>
</header>
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
                <form class="navbar-form navbar-left" method="post"
                      action="profile.php?username=<?php echo($username); ?>">
                    <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                        <input class="form-control" name="searchbox" type="text">
                    </div>
                </form>
                <ul class="nav navbar-nav hidden-md hidden-lg navbar-right">
                    <li class="active" role="presentation"><a href="#">Timeline</a></li>
                    <li role="presentation"><a href="my-messages.php">Messages</a></li>
                    <li role="presentation"><a href="notify.php">Notifications</a></li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
                                            href="#">User <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li role="presentation"><a href="profile.php?username=<?php echo($followername); ?>">My
                                    Profile</a></li>
                            <li class="divider" role="presentation"></li>
                            <li role="presentation"><a href="#">Timeline </a></li>
                            <li role="presentation"><a href="send-message.php?reciever=<?php echo($userid); ?>">Send
                                    Messages </a></li>
                            <li role="presentation"><a href="notify.php">Notifications </a></li>
                            <?php

                            if ($isAdmin) {

                                echo "<li role=\"presentation\"><a href='delete-account.php?userid=$userid'>Delete User Account </a></li>";
                                echo "<li role=\"presentation\"><a href='update-account.php?userid=$userid'>Update User Account </a></li>";

                            }

                            if ($userid == $followerid)
                                echo "<li role=\"presentation\"><a href=\"private_settings.php\">Private Settings </a></li>";

                            if ($userid) {
                                echo "<li role=\"presentation\"><a href=\"logout.php\">Logout </a></li>";
                                // if ($isFollowing) echo "<li role=\"presentation\"><a href=\"send-message.php?reciever=$userid\">Send Messages </a></li>";;
                            } else {
                                echo "<li role=\"presentation\"><a href=\"login.php\">Login </a></li>";
                            }

                            ?>

                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">
                    <li class="active" role="presentation"><a href="#">Timeline</a></li>
                    <li role="presentation"><a href="my-messages.php">Messages</a></li>
                    <li role="presentation"><a href="notify.php">Notifications</a></li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
                                            href="#">User <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li role="presentation"><a href="profile.php?username=<?php echo($followername); ?>">My
                                    Profile</a></li>
                            <li class="divider" role="presentation"></li>
                            <li role="presentation"><a href="#">Timeline </a></li>
                            <li role="presentation"><a href="send-message.php?reciever=<?php echo($userid); ?>">Send
                                    Messages </a></li>
                            <li role="presentation"><a href="notify.php">Notifications </a></li>
                            <?php

                            if ($isAdmin) {

                                echo "<li role=\"presentation\"><a href='delete-account.php?userid=$userid'>Delete User Account </a></li>";
                                echo "<li role=\"presentation\"><a href='update-account.php?userid=$userid'>Update User Account </a></li>";

                            }

                            if ($userid == $followerid)
                                echo "<li role=\"presentation\"><a href=\"private_settings.php\">Private Settings </a></li>";


                            if ($userid) {
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
</div>