<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    if(isset($_GET['id'])){
        $user_data = $user_obj->find_user_by_id($_GET['id']);
        if($user_data ===  false){
            header('Location: profile.php');
            exit;
        }
        else{
            if($user_data->id == $_SESSION['user_id']){
                header('Location: profile.php');
                exit;
            }
        }
    }
}
else{
    header('Location: logout.php');
    exit;
}
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user_id'], false);
$is_already_friends = $frnd_obj->is_already_friends($_SESSION['user_id'], $user_data->id);
$get_all_friends = $frnd_obj->get_all_friends($_SESSION['user_id'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo  $user_data->username;?></title>
    <link rel="stylesheet" href="./chat.css">
<!--    <link rel="stylesheet" href="./style.css">-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body style="background-color:powderblue;">
   <div class="main">
      <div class="sub">
       <?php
                if($get_frnd_num > 0){
                    foreach($get_all_friends as $row){
                    echo '<div class="usr_sec">
              <div class="usr_img"><img class="user_img" src="profile_images/'.$row->user_image.'" alt="Profile image"></div>
              <div class="usr_name">
                  <h4>'.$row->username.'</h4>
                  <p>Last Seen Yesterday</p>
              </div>
           </div>';
           }
                }
                else{
                    echo '<h4>You have no friends!</h4>';
                }
                ?>
                <textarea id=msgbox></textarea>
       </div>
   </div>
    </body>