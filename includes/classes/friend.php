<?php
class Friend{
    
    protected $db;

    public function __construct($db_connection){
        $this->db = $db_connection;
    }

    // CHECK IF ALREADY FRIENDS
    public function is_already_friends($my_id, $user_id){
        try{
            $sql = "SELECT * FROM `friends` WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        
    }

    //  IF I AM THE REQUEST SENDER
    public function am_i_the_req_sender($my_id, $user_id){
        try{
            $sql = "SELECT * FROM `friend_request` WHERE sender = ? AND receiver = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$my_id, $user_id]);

            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //  IF I AM THE RECEIVER 
    public function am_i_the_req_receiver($my_id, $user_id){
        
        try{
            $sql = "SELECT * FROM `friend_request` WHERE sender = ? AND receiver = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $my_id]);

            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CHECK IF REQUEST HAS ALREADY BEEN SENT
    public function is_request_already_sent($my_id, $user_id){
        
        try{
            $sql = "SELECT * FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // MAKE PENDING FRIENDS (SEND FRIEND REQUEST)
    public function make_pending_friends($my_id, $user_id){
        
        try{
            $sql = "INSERT INTO `friend_request`(sender, receiver) VALUES(?,?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$my_id, $user_id]);
            header('Location: user_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    //CHAT FUNCTION
    
    public function chat_with_friend($my_id, $user_id, $m){
        
        try{
            $m = addslashes($m);
            $sql = "INSERT INTO `chat`(sender, receiver, msg) VALUES(?,?,'$m')";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':m',$m, PDO::PARAM_STR);
            $stmt->execute([$my_id, $user_id]);
            header('Location: msg.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    //DATA 
    
    
//    public function history_of_sender($my_id,$user_id){
//    $return_data = [];
//    $all_users = $stmt->fetchAll(PDO::FETCH_OBJ);
//    $sql = "SELECT sender, receiver, msg FROM chat WHERE sender=$my_id AND receiver=$user_id";
//    $sql_stmt = $this->db->prepare($get_user);
//    $sql_stmt->execute([$row->user_two]);
//    array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
//    $result_of_sender = mysqli_query($sql);
//    return $result_of_sender;
//    }
    
    
    public function history_of_sender($my_id, $user_id, $send_data){
        try{    
            $get_msgs = "SELECT time , msg FROM chat WHERE sender=$my_id AND receiver=$user_id";
            $stmt = $this->db->prepare($get_msgs);
            $stmt->execute();
            if($send_data){
            $return_data_sender = [];
            $all_msgs = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $all_msgs;
        }
            else
     {
         return $stmt->rowCount();
     }
        }
        catch (PDOException $e) {
            die($e->getMessage());
    }
    }


    public function history_of_receiver($user_id, $my_id, $send_data){
 try{
            $get_msgs_rec = "SELECT time, msg FROM chat WHERE sender=$user_id AND receiver=$my_id";
            $stmt = $this->db->prepare($get_msgs_rec);
            $stmt->execute();
            if($send_data){
            $all_msgs_rec = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $all_msgs_rec;
            }
     else
     {
         return $stmt->rowCount();
     }
        }
        catch (PDOException $e) {
            die($e->getMessage());
    }
    }
    
    
    public function history_of_chat($user_id, $my_id, $send_data){
 try{
            $get_msgs_his = "SELECT time, msg, sender, receiver FROM chat WHERE (sender=$user_id AND receiver=$my_id) OR (sender=$my_id AND receiver=$user_id)";
            $stmt = $this->db->prepare($get_msgs_his);
            $stmt->execute();
            if($send_data){
            $all_msgs_his = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $all_msgs_his;
            }
     else
     {
         return $stmt->rowCount();
     }
        }
        catch (PDOException $e) {
            die($e->getMessage());
    }
    }
    

    // CANCLE FRIEND REQUEST
    public function cancel_or_ignore_friend_request($my_id, $user_id){
        
        try{
            $sql = "DELETE FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: user_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // MAKE FRIENDS
    public function make_friends($my_id, $user_id){
        
        try{

            $delete_pending_friends = "DELETE FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";
            $delete_stmt = $this->db->prepare($delete_pending_friends);
            $delete_stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_stmt->execute();
            if($delete_stmt->execute()){

                $sql = "INSERT INTO `friends`(user_one, user_two) VALUES(?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$my_id, $user_id]);
                header('Location: user_profile.php?id='.$user_id);
                exit;
                
            }            
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public function all_friends_data($id){
        $get_fusers = $this->db->prepare("SELECT id, user_one, user_two FROM `friends` WHERE user_one != :id OR user_two != :id");
        $get_fusers->bindValue(':id',$id, PDO::PARAM_INT);
        $get_fusers->execute();
        if($get_fusers->rowCount() > 0){
                return $get_fusers->fetchAll(PDO::FETCH_OBJ);
        }
        else{
            return false;
            }
    }
    public function all_requests_data($id){
        $get_rusers = $this->db->prepare("SELECT id, sender, receiver FROM `friend_request` WHERE sender != :id OR receiver != :id");
        $get_rusers->bindValue(':id',$id, PDO::PARAM_INT);
        $get_rusers->execute();
        if($get_rusers->rowCount() > 0){
                return $get_rusers->fetchAll(PDO::FETCH_OBJ);
        }
        else{
            return false;
            }
    }
    
    public function make_admin_req($user_email){
        try{
            $sql = "SELECT id FROM users WHERE user_email=?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_email]);
            $reqid = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach($reqid as $row){
            $sql = "INSERT INTO `friend_request`(sender, receiver) VALUES(1,?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$row->id]);
            return;
            exit;
        }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    // DELETE FRIENDS 
    public function delete_friends($my_id, $user_id){
        try{
            $delete_friends = "DELETE FROM `friends` WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";
            $delete_stmt = $this->db->prepare($delete_friends);
            $delete_stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_stmt->execute();
            header('Location: user_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // REQUEST NOTIFICATIONS
    public function request_notification($my_id, $send_data){
        try{
            $sql = "SELECT sender, username, user_image FROM `friend_request` JOIN users ON friend_request.sender = users.id WHERE receiver = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$my_id]);
            if($send_data){
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return $stmt->rowCount();
            }

        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }
    
    public function get_all_friends($my_id, $send_data){
        try{
            $sql = "SELECT * FROM `friends` WHERE user_one = :my_id OR user_two = :my_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->execute();

                if($send_data){

                    $return_data = [];
                    $all_users = $stmt->fetchAll(PDO::FETCH_OBJ);

                    foreach($all_users as $row){
                        if($row->user_one == $my_id){
                            $get_user = "SELECT id, username, user_image FROM `users` WHERE id = ?";
                            $get_user_stmt = $this->db->prepare($get_user);
                            $get_user_stmt->execute([$row->user_two]);
                            array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
                        }else{
                            $get_user = "SELECT id, username, user_image FROM `users` WHERE id = ?";
                            $get_user_stmt = $this->db->prepare($get_user);
                            $get_user_stmt->execute([$row->user_one]);
                            array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
                        }
                    }

                    return $return_data;

                }
                else{
                    return $stmt->rowCount();
                }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}