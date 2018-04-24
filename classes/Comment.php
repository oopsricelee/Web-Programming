<?php

class Comment
{
    public static function createComment($commentBody, $postId, $userId)
    {

        if (strlen($commentBody) > 160 || strlen($commentBody) < 1) {
            die('Incorrect length!');
        }

        if (!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid' => $postId))) {
            echo 'Invalid post ID';
        } else {
            DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment' => $commentBody, ':userid' => $userId, ':postid' => $postId));
        }

    }

    public static function displayComments($postId)
    {

        $comments = DB::query('SELECT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid' => $postId));
        foreach ($comments as $comment) {
            echo "<div class=\"lead text-muted\">" . $comment['comment'] . " ~ " . $comment['username'] . "</div>" . "<hr />";
        }
    }

    public static function createImgComment($commentBody, $postId, $userId)
    {
        if (strlen($commentBody) > 160 || strlen($commentBody) < 1) {
            die('Incorrect length!');
        }
        if (!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid' => $postId))) {
            echo 'Invalid post ID';
        } else {
            DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid,NULL)', array(':comment' => $commentBody, ':userid' => $userId, ':postid' => $postId));
            $commentid = DB::query('SELECT id FROM comments WHERE user_id = :userid AND post_id = :postid ORDER BY ID DESC LIMIT 1', array(':userid' => $userId, ':postid' => $postId))[0]['id'];
            return $commentid;
        }

    }
}

?>
