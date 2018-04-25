<?php
class Image {

        public static function uploadImage($formname, $query, $params) {
                $image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));

                $options = array('http'=>array(
                        'method'=>"POST",
                        'header'=>"Authorization: Bearer 4f93958651e3b07144c1c28ac8200f161a920e2b\n".
                        "Content-Type: application/x-www-form-urlencoded",
                        'content'=>$image
                ));

                $context = stream_context_create($options);

                $imgurURL = "https://api.imgur.com/3/image";

                if ($_FILES[$formname]['size'] > 10240000) {
                        die('Image too big, must be 10MB or less!');
                }

                $response = file_get_contents($imgurURL, false, $context);
                $response = json_decode($response);

                $preparams = array($formname=>$response->data->link);

                $params = $preparams + $params;

                DB::query($query, $params);

        }
        public static function uploadAvatar($formname, $query, $params) {
                // $image = base64_encode(file_get_contents('file:///Users/oopsRiceLee/Desktop/Profile.jpg'));
                // echo '<script type="text/javascript">alert('.$formname.');</script>';
                $options = array('http'=>array(
                        'method'=>"POST",
                        'header'=>"Authorization: Bearer 4f93958651e3b07144c1c28ac8200f161a920e2b\n".
                        "Content-Type: application/x-www-form-urlencoded",
                        'content'=>$formname
                ));

                $context = stream_context_create($options);

                $imgurURL = "https://api.imgur.com/3/image";

                // if ($formname['size'] > 10240000) {
                //         die('Image too big, must be 10MB or less!');
                // }

                $response = file_get_contents($imgurURL, false, $context);
                $response = json_decode($response);

                $preparams = array('profileimg'=>$response->data->link);

                $params = $preparams + $params;

                DB::query($query, $params);

        }

}
?>
