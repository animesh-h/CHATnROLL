<?php
require 'includes/init.php';
if(isset($_POST['submit4'])){
      $user_obj->send_msgs_to_admin($_POST['user1'],$_POST['user2']);
}
if(isset($_POST['submit'])){
      $user_obj->send_detail('1');
}
if(isset($_POST['del'])){
    $idu = $_POST['my_id'];
    $res = $user_obj->deletebuser($idu);
}
if(isset($_POST['chat'])){
    $adid = $_SESSION['user_id'];
    $idu = $_POST['my_id'];
    header("Location: msg.php?id=$idu");
}
if(isset($_POST['block'])){
    $idu = $_POST['my_id'];
    $res = $user_obj->blockuser($idu);
}
if(isset($_POST['unblock'])){
    $idu = $_POST['my_id'];
    $res = $user_obj->unblockuser($idu);
}
if(isset($_POST['user1']) && isset($_POST['user2']) && isset($_POST['fetch'])){
    $result_of_his=$frnd_obj->history_of_chat($_POST['user1'],$_POST['user2'],true);
    $num_of_his=$frnd_obj->history_of_chat($_POST['user1'],$_POST['user2'], false);
}
$result1 = $user_obj->all_users_detail('1');
$result2 = $user_obj->all_block_users('BLOCK');
$result3 = $user_obj->all_active_users('BLOCK');
$result5 = $frnd_obj->all_friends_data('1');
$result6 = $frnd_obj->all_requests_data('1');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<input type='button' value='show all users' id="all">
<input type='button' value='show all block users' id="all_blk">
<input type='button' value='show all active users' id="all_act">
<input type='button' value='show all msgs' id="all_msgs">
<input type='button' value='show all friend database' id="all_friend">
<input type='button' value='show all requests' id="all_requests_b">
<a href="logout.php" rel="noopener noreferrer">Logout</a>
<div id="all_users" style="display:none;">
    <h2>All User's Table:</h2>
    <form action="" method="POST" novalidate>
<input name="submit" type="submit" class="form_link_sub" value="SEND">
<table style="border: 1px solid black;" id='table' >
  <tr>
    <th style="border: 1px solid black;">ID</th>
    <th style="border: 1px solid black;">Firstname</th>
    <th style="border: 1px solid black;">Email</th>
    <th style="border: 1px solid black;">Status</th>
    <th style="border: 1px solid black;">Action</th>
  </tr>
  <?php
  if($result1 > 0){
   foreach($result1 as $row1){
        echo '
  <tr>
    <td style="border: 1px solid black;">'.$row1->id.'</td>
    <td style="border: 1px solid black;">'.$row1->username.'</td>
    <td style="border: 1px solid black;">'.$row1->user_email.'</td>
    <td style="border: 1px solid black;">'.$row1->status.'</td>
    <td style="border: 1px solid black;"><input name="my_id" type="hidden" value='.$row1->id.' /><input name="chat" type="submit" value="CHAT"> <input name="block" type="submit" value="BLOCK"> <input name="unblock" type="submit" value="UNBLOCK"> <input name="del" type="submit" value="DELETE"></td>
  </tr>';
  }
  }
        ?>
</table> 
</form>
</div>
<div id="all_blk_users" style="display:none;">
    <h2>All Block User's Table:</h2>
    <form action="" method="POST" novalidate>
<input name="submit" type="submit" class="form_link_sub" value="SEND">
<table style="border: 1px solid black;" id='table' >
  <tr>
    <th style="border: 1px solid black;">ID</th>
    <th style="border: 1px solid black;">Firstname</th>
    <th style="border: 1px solid black;">Email</th>
    <th style="border: 1px solid black;">Status</th>
    <th style="border: 1px solid black;">Action</th>
  </tr>
  <?php
  if($result2 > 0){
   foreach($result2 as $row2){
        echo '
  <tr>
    <td style="border: 1px solid black;">'.$row2->id.'</td>
    <td style="border: 1px solid black;">'.$row2->username.'</td>
    <td style="border: 1px solid black;">'.$row2->user_email.'</td>
    <td style="border: 1px solid black;">'.$row2->status.'</td>
    <td style="border: 1px solid black;"><input name="my_id" type="hidden" value='.$row2->id.' /> <input name="unblock" type="submit" value="UNBLOCK"> <input name="del" type="submit" value="DELETE"></td>
  </tr>';
  }
  }
        ?>
</table> 
</form>
</div>
<div id="all_act_users" style="display:none;">
    <h2>All Active User's Table:</h2>
    <form action="" method="POST" novalidate>
<input name="submit" type="submit" class="form_link_sub" value="SEND">
<table style="border: 1px solid black;" id='table' >
  <tr>
    <th style="border: 1px solid black;">ID</th>
    <th style="border: 1px solid black;">Firstname</th>
    <th style="border: 1px solid black;">Email</th>
    <th style="border: 1px solid black;">Status</th>
    <th style="border: 1px solid black;">Action</th>
  </tr>
  <?php
  if($result3 > 0){
   foreach($result3 as $row3){
        echo '
  <tr>
    <td style="border: 1px solid black;">'.$row3->id.'</td>
    <td style="border: 1px solid black;">'.$row3->username.'</td>
    <td style="border: 1px solid black;">'.$row3->user_email.'</td>
    <td style="border: 1px solid black;">'.$row3->status.'</td>
    <td style="border: 1px solid black;"><input name="my_id" type="hidden" value='.$row3->id.' /><input name="block" type="submit" value="BLOCK"> <input name="del" type="submit" value="DELETE"></td>
  </tr>';
  }
  }
        ?>
</table> 
</form>
</div>
<div id="all_msgs_users" style="display:none;">
 <h2>All Messages between two users:</h2>
    <form action="" method="POST" novalidate>
  <label for="user1">USER ID-1</label>
      <input type="text" id="user1" name="user1" placeholder="Enter userid 1" required>
  <label for="user2">USER ID-2</label>
      <input type="text" id="user2" name="user2" placeholder="Enter userid 2" required>
            <input type="submit" value="Fetch" id="fetch" name="fetch">
<!--   <div id="msgsofusers" style="display:none;">-->
<input name="submit4" type="submit" class="form_link_sub" value="SEND">
<table style="border: 1px solid black;" id='table' >
  <tr>
    <th style="border: 1px solid black;">Sender-ID</th>
    <th style="border: 1px solid black;">Receiver-ID</th>
    <th style="border: 1px solid black;">Message</th>
    <th style="border: 1px solid black;">Time</th>
  </tr>
  <?php
  if ($num_of_his> 0) {
          foreach($result_of_his as $row4){
        echo '
  <tr>
    <td style="border: 1px solid black;">'.$row4->sender.'</td>
    <td style="border: 1px solid black;">'.$row4->receiver.'</td>
    <td style="border: 1px solid black;">'.$row4->msg.'</td>
    <td style="border: 1px solid black;">'.$row4->time.'</td>
  </tr>';
  }
  }
        ?>
</table> 
<!--</div>-->
</form>
    </div>
    <div id="all_friends" style="display:none;">
    <h2>ALL Friends Database's Table:</h2>
    <form action="" method="POST" novalidate>
<input name="submit" type="submit" class="form_link_sub" value="SEND">
<table style="border: 1px solid black;" id='table' >
  <tr>
    <th style="border: 1px solid black;">User 1 ID</th>
    <th style="border: 1px solid black;">User 2 ID</th>
  </tr>
  <?php
  if($result5 > 0){
   foreach($result5 as $row5){
        echo '
  <tr>
    <td style="border: 1px solid black;">'.$row5->user_one.'</td>
    <td style="border: 1px solid black;">'.$row5->user_two.'</td>
  </tr>';
  }
  }
        ?>
</table> 
</form>
</div>
    <div id="all_requests" style="display:none;">
    <h2>All Requests Database's Table:</h2>
    <form action="" method="POST" novalidate>
<input name="submit" type="submit" class="form_link_sub" value="SEND">
<table style="border: 1px solid black;" id='table' >
  <tr>
    <th style="border: 1px solid black;">Sender ID</th>
    <th style="border: 1px solid black;">Reciver ID</th>
  </tr>
  <?php
  if($result6 > 0){
   foreach($result6 as $row6){
        echo '
  <tr>
    <td style="border: 1px solid black;">'.$row6->sender.'</td>
    <td style="border: 1px solid black;">'.$row6->receiver.'</td>
  </tr>';
  }
  }
        ?>
</table> 
</form>
</div>
</body>
<script>
$(document).ready(function(){
  $("#all").click(function(){
    $("#all_users").toggle();
  });
});
$(document).ready(function(){
  $("#all_blk").click(function(){
    $("#all_blk_users").toggle();
  });
});
$(document).ready(function(){
  $("#all_act").click(function(){
    $("#all_act_users").toggle();
  });
});
$(document).ready(function(){
  $("#all_msgs").click(function(){
    $("#all_msgs_users").toggle();
  });
});
$(document).ready(function(){
  $("#all_friend").click(function(){
    $("#all_friends").toggle();
  });
});
$(document).ready(function(){
  $("#all_requests_b").click(function(){
    $("#all_requests").toggle();
  });
});
</script>
</html>