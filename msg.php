<?php
require 'includes/init.php';

if(isset($_POST['submit'])&&!empty($_POST['fullmsg']))
{		
    $user_id = $_GET['id'];
    $my_id = $_SESSION['user_id'];
    $msg = $_POST['fullmsg'];
    $frnd_obj->chat_with_friend($my_id, $user_id, $msg);
}
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    if(isset($_GET['id'])){
        $user_data = $user_obj->find_user_by_id($_GET['id']);
        if($user_data ===  false){
            header('Location: msg.php');
            exit;
        }
        else{
            if($user_data->id == $_SESSION['user_id']){
                header('Location: msg.php');
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
$get_req_num = $frnd_obj->request_notification($_SESSION['user_id'], false);
//$get_all_msg = $frnd_obj->chat($_SESSION['user_id'],$_GET['id']);
$result_of_sender=$frnd_obj->history_of_sender($_SESSION['user_id'],$_GET['id'],true);
$result_of_receiver=$frnd_obj->history_of_receiver($_GET['id'],$_SESSION['user_id'],true);

$num_of_sender=$frnd_obj->history_of_sender($_SESSION['user_id'],$_GET['id'], false);
$num_of_receiver=$frnd_obj->history_of_receiver($_GET['id'],$_SESSION['user_id'], false);

$result_of_his=$frnd_obj->history_of_chat($_GET['id'],$_SESSION['user_id'],true);
$num_of_his=$frnd_obj->history_of_chat($_GET['id'],$_SESSION['user_id'], false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta HTTP-EQUIV="refresh" CONTENT="8">
    <link rel="stylesheet" href="./style1.css">
    </head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<body>
<div id="chat-container">
   <div id="search-container">
       <input id="filter" type="text" placeholder="Search"/>
   </div>
   <div id="conversation-list">
       <?php
                if($get_frnd_num > 0){
                    foreach($get_all_friends as $row){
                    echo'<a href="msg.php?id='.$row->id.'"><div id="conversation" class="conversation">
           <img src="profile_images/'.$row->user_image.'" alt="profile img"/>
       <div id="title-text" class="title-text">
           '.$row->username.'
       </div>
       <div class="created-date">
           Apr 16
       </div>
       <div class="conversation-message">
           this is a message
       </div>
   </div></a>';
           }
                }
                else{
                    echo '<h4>You have no friends!</h4>';
                }
                ?>
    </div>
   <div id="new-message-container">
       <a href="./notifications.php">+</a><span class="badge <?php
                if($get_req_num > 0){
                    echo 'redBadge';
                }
                ?>"><?php echo $get_req_num;?></span>
   </div>
   <div id="chat-title">
       <span><?php echo  $user_data->username;?></span>
       <a href="#"><img class="img_delete" src="./bin.svg" alt="Delete Conversation"/></a>
       <a href="./logout.php"><img class="img_logout" src="./logout.svg" alt="logout"/></a>
   </div>
   <div id="chat-message-list">
    <?php
     if ( $num_of_his> 0) {
          foreach($result_of_his as $row) { 
              if ($row->sender==$_SESSION['user_id'] or $row->receiver==$_GET['id']){
              echo' <div class="message-row you-message">
          <div class="message-content">
           <div class="message-text">'.$row->msg.'</div>
           <div class="message-time">'.$row->time.'</div>
           </div>
       </div>';}
       elseif ($row->receiver==$_SESSION['user_id'] or $row->sender==$_GET['id']){
           echo'
       <div class="message-row other-message">
          <div class="message-content">
          <img class="img_usr" src="profile_images/'.$user_data->user_image.'" alt="userimg"/>
           <div class="message-text">'.$row->msg.'</div>
           <div class="message-time">'.$row->time.'</div>
           </div>
       </div>';
          }
          }
     }
    ?>
   </div>
   <form method="POST" id="chat-form">
<img class="img_attach" src="./paper-clip.svg" alt="Add Attachment"/>
<input type="text" id="msgbox" name="fullmsg" placeholder="Enter Message" >
<input class="sendbtn" type="submit" name="submit" value="">
</form>
</div>
</body>
<script>
//$(document).ready(function(){
//  $("#filter").on("keyup", function() {
//    var value = $(this).val().toLowerCase();
//    $("#conversation-list *").filter(function() {
//      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
//    });
//  });
//});
 $(document).ready(function(){
 $('#filter').keyup(function(){
 
  // Search text
  var text = $(this).val();
 
  // Hide all content class element
  $('.conversation').hide();

  // Search and show
  $('.conversation:contains("'+text+'")').show();
 
 });
});
</script>
<script>
    var container = document.getElementById('chat-message-list');
    container.scrollTop = container.scrollHeight;
</script>
<script> document.getElementById('msgbox').focus(); </script>
</html>