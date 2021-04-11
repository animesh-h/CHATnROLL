<?php
// user.php
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
//
//require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
//require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
//require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';
class User{
    protected $db;
    protected $user_name;
    protected $user_email;
    protected $user_pass;
    protected $hash_pass;
    
    function __construct($db_connection){
        $this->db = $db_connection;
    }
    function thankyou_mail($email){
        $get_otp= "SELECT username FROM `users` WHERE user_email = ?";
        $stmt = $this->db->prepare($get_otp);
        $stmt->execute([$email]);
        $name = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($name as $row){
        $tymail = new PHPMailer;
                        $tymail->isSMTP();
                        $tymail->Host = 'smtp.gmail.com';
                        $tymail->Port = 587;
                        $tymail->SMTPSecure = 'tls';
                        $tymail->SMTPAuth = true;
                        // $tymail->Username = "chatnroll2020@gmail.com";
                        // $tymail->Password = "wzoxzzmwnyhvaelp";
                        // $tymail->setFrom('chatnroll2020@gmail.com', 'CHAT-N-ROLL');
                        $tymail->Username = "sinhaanimesh82@gmail.com";
                        $tymail->Password = "trufazqjochudsee";
                        $tymail->setFrom('sinhaanimesh82@gmail.com', 'CHAT-N-ROLL');
                        $tymail->addAddress($email,$row->username);
                        $tymail->isHTML(true); 
//                        $mail->addAttachment('./ChatNRoll.apk', 'ChatNRoll.apk');
                        $tymail->Subject = 'Thanks For Singup In Chat-N-Roll';
                        $tymail->Body ='<h1 style="text-align:center;">CHAT-N-ROLL</h1><br><br><br>
                                    <h2>Dear '.$row->username.',</h2>
                                    <h3 style="text-align:center;">Thanks for signing up in chatnroll have fun :)<br>
                                    Adding the app link of the website download and install it from there if not done yet!!!<br>
                                    Keep Sharing our website and connect with your friends!!!<br>
                                    Download our app by below link.</h3><br>
                                     <h4 style="text-align:center;">link:- https://drive.google.com/file/d/1307gTQ8Ua0OWKHiBJ2VhZX-pV7oAYD56/view</h4>
                                    ';
                        if(!$tymail->send()){
                            echo "Mailer Error: " . $tymail->ErrorInfo;
                        }else{
                         }
                        return;  
    }
    }
    function deleteuser($email)
    {
        $delete_user= "DELETE FROM `users` WHERE user_email = ?";
        $stmt = $this->db->prepare($delete_user);
        $stmt->execute([$email]);
        header('Location:signup.php');
    }
    function blockuser($id){
            $sstat = "BLOCK";
            $stat_update= "UPDATE `users` SET status=? WHERE id = ?";
            $stmt = $this->db->prepare($stat_update);
            $stmt->execute([$sstat,$id]);
    }
    function unblockuser($id){
        $sstat = "ACTIVE";
            $stat_update= "UPDATE `users` SET status=? WHERE id = ?";
            $stmt = $this->db->prepare($stat_update);
            $stmt->execute([$sstat,$id]);
    }
    function deletebuser($id)
    {
        $delete_user= "DELETE FROM `users` WHERE id = ?";
        $stmt = $this->db->prepare($delete_user);
        $stmt->execute([$id]);
        header('Location:admin.php');
    }
    function resend_otp($email){
        $get_data= "SELECT username FROM `users` WHERE user_email = ?";
        $stmt = $this->db->prepare($get_data);
        $stmt->execute([$email]);
        $name = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($name as $row){
        $rn=rand(100000, 999999);
        $otp_update= "UPDATE `users` SET otp=? WHERE user_email = ?";
        $stmt = $this->db->prepare($otp_update);
        $stmt->execute([$rn,$email]);
        $remail = new PHPMailer;
        $remail->isSMTP();
                        $remail->Host = 'smtp.gmail.com';
                        $remail->Port = 587;
                        $remail->SMTPSecure = 'tls';
                        $remail->SMTPAuth = true;
                        $remail->Username = "chatnroll2020@gmail.com";
                        $remail->Password = "wzoxzzmwnyhvaelp";
                        $mail->setFrom('chatnroll2020@gmail.com', 'CHAT-N-ROLL');
                        // $remail->Username = "sinhaanimesh82@gmail.com";
                        // $remail->Password = "trufazqjochudsee";
                        // $remail->setFrom('sinhaanimesh82@gmail.com', 'CHAT-N-ROLL');
                        $remail->addAddress($email, $row->username);
                        $remail->isHTML(true);                                
                        $remail->Subject = 'OTP FOR SIGNUP IN CHAT-N-ROLL';
                        $remail->Body = '<h1 style="text-align:center;">CHAT-N-ROLL</h1><br><br><br>
                                    <h2>Dear '.$row->username.',</h2><br>
                                    <h4 style="text-align:center;">thanks for signing up in chatnroll.<br><br>
                                    Your OTP: '.$rn.'</h4>
                                    ';
                        if(!$remail->send()){
                            echo "Mailer Error: " . $remail->ErrorInfo;
                        }else{
                         }
                        return ['successMessage' => 'OTP sent successfully.'];
    }
    }
    function singUpUser($username, $email, $password){
        try{
            $this->user_name = trim($username);
            $this->user_email = trim($email);
            $this->user_pass = trim($password);
            $rn=rand(100000, 999999);
            $ustat="BLOCK";
            $this->user_otp = trim($rn);
            if(!empty($this->user_name) && !empty($this->user_email) && !empty($this->user_pass)){

                if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) { 
                    $check_email = $this->db->prepare("SELECT * FROM `users` WHERE user_email = ?");
                    $check_email->execute([$this->user_email]);

                    if($check_email->rowCount() > 0){
                        return ['errorMessage' => 'This Email Address is already registered. Please Try another.'];
                    }
                    else{
                        
                        $user_image = rand(1,12);
                        $this->hash_pass = password_hash($this->user_pass, PASSWORD_DEFAULT);
                        $sql = "INSERT INTO `users` (username, user_email, user_password, user_image, otp, status) VALUES(:username, :user_email, :user_pass, :user_image, :user_otp, :user_stat)";
            
                        $sign_up_stmt = $this->db->prepare($sql);
                        //BIND VALUES
                        $sign_up_stmt->bindValue(':username',htmlspecialchars($this->user_name), PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_email',$this->user_email, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_pass',$this->hash_pass, PDO::PARAM_STR);
                        // INSERTING RANDOM IMAGE NAME
                        $sign_up_stmt->bindValue(':user_image',$user_image.'.png', PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_otp',$rn, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_stat',$ustat,PDO::PARAM_STR);
                        $sign_up_stmt->execute();
                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->Port = 587;
                        $mail->SMTPSecure = 'tls';
                        $mail->SMTPAuth = true;
                        $mail->Username = "chatnroll2020@gmail.com";
                        $mail->Password = "wzoxzzmwnyhvaelp";
                        $mail->setFrom('chatnroll2020@gmail.com', 'CHAT-N-ROLL');
                        // $mail->Username = "sinhaanimesh82@gmail.com";
                        // $mail->Password = "trufazqjochudsee";
                        // $mail->setFrom('sinhaanimesh82@gmail.com', 'CHAT-N-ROLL');
                        $mail->addAddress($email, $username);
                        $mail->isHTML(true);                                
                        $mail->Subject = 'OTP FOR SIGNUP IN CHAT-N-ROLL';
                        $mail->Body = '<h1 style="text-align:center;">CHAT-N-ROLL</h1><br><br><br>
                                    <h2>Dear '.$username.',</h2><br>
                                    <h4 style="text-align:center;">thanks for signing up in chatnroll.<br><br>
                                    Your OTP: '.$rn.'</h4>
                                    ';
                        if(!$mail->send()){
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        }else{
                         }
                        return ['successMessage' => 'OTP sent successfully.'];                   
                    }
                }
                else{
                    return ['errorMessage' => 'Invalid email address!'];
                }    
            }
            else{
                return ['errorMessage' => 'Please fill in all the required fields.'];
            } 
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // LOGIN USER
    function loginUser($email, $password){
        
        try{
            $this->user_email = trim($email);
            $this->user_pass = trim($password);

            $find_email = $this->db->prepare("SELECT * FROM `users` WHERE user_email = ?");
            $find_email->execute([$this->user_email]);
            
            if($find_email->rowCount() === 1){
                $row = $find_email->fetch(PDO::FETCH_ASSOC);

                $match_pass = password_verify($this->user_pass, $row['user_password']);
                if($match_pass && $row['status']=="ACTIVE" && $this->user_email=="chatnroll2020@gmail.com"){
                    $_SESSION = [
                        'user_id' => $row['id'],
                        'email' => $row['user_email']
                    ];
                        header('Location:admin.php');
                }
                elseif($match_pass && $row['status']=="ACTIVE"){
                    $_SESSION = [
                        'user_id' => $row['id'],
                        'email' => $row['user_email']
                    ];
                        header("Location:friends.php");
                }
                else{
                    return ['errorMessage' => 'Invalid password OR your account is not active'];
                }
                
            }
            else{
                return ['errorMessage' => 'Invalid email address!'];
            }

        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // FIND USER BY ID
    function find_user_by_id($id){
        try{
            $find_user = $this->db->prepare("SELECT * FROM `users` WHERE id = ?");
            $find_user->execute([$id]);
            if($find_user->rowCount() === 1){
                return $find_user->fetch(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    function all_users($id){
        try{
            $get_users = $this->db->prepare("SELECT id, username, user_image FROM `users` WHERE id != ?");
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
         function all_users_detail($id){
        try{
            $get_users = $this->db->prepare("SELECT id, user_email, username, user_image, status FROM `users` WHERE id != ?");
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    function all_block_users($stat){
        try{
            $get_users = $this->db->prepare("SELECT id, user_email, username, user_image, status FROM `users` WHERE status = ?");
            $get_users->execute([$stat]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    function all_active_users($stat){
        try{
            $get_users = $this->db->prepare("SELECT id, user_email, username, user_image, status FROM `users` WHERE id != 1 && status != ?");
            $get_users->execute([$stat]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    function send_detail($id){
        try{
            $get_users = $this->db->prepare("SELECT id, user_email, username, user_image, status FROM `users` WHERE id != ?");
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                $result=$get_users->fetchAll(PDO::FETCH_OBJ);
                $amail = new PHPMailer;
                        $amail->isSMTP();
                        $amail->Host = 'smtp.gmail.com';
                        $amail->Port = 587;
                        $amail->SMTPSecure = 'tls';
                        $amail->SMTPAuth = true;
//                        $tymail->Username = "chatnroll2020@gmail.com";
//                        $tymail->Password = "wzoxzzmwnyhvaelp";
//                        $tymail->setFrom('chatnroll2020@gmail.com', 'CHAT-N-ROLL');
                        $amail->Username = "sinhaanimesh82@gmail.com";
                        $amail->Password = "trufazqjochudsee";
                        $amail->setFrom('sinhaanimesh82@gmail.com', 'ADMIN');
                        $amail->addAddress('chatnroll2020@gmail.com','chatnroll');
                        $amail->isHTML(true); 
//                        $mail->addAttachment('./ChatNRoll.apk', 'ChatNRoll.apk');
                        $amail->Subject = 'Details of all users to admin';
                        $body ='<table>
                                          <tr>
                                            <th style="border: 1px solid black;">ID</th>
                                            <th style="border: 1px solid black;">Firstname</th>
                                            <th style="border: 1px solid black;">Email</th>
                                            <th style="border: 1px solid black;">Status</th>
                                          </tr>';
                        foreach($result as $row){
                                          $body .= '<tr>
                                            <td style="border: 1px solid black;">'.$row->id.'</td>
                                            <td style="border: 1px solid black;">'.$row->username.'</td>
                                            <td style="border: 1px solid black;">'.$row->user_email.'</td>
                                            <td style="border: 1px solid black;">'.$row->status.'</td>
                                          </tr>';}
                $amail->Body = $body.'</table>';
                        if(!$amail->send()){
                            echo "Mailer Error: " . $amail->ErrorInfo;
                        }else{
                         }
                        return;  
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    function send_msgs_to_admin($user_id, $my_id){
        try{
            $stmt = $this->db->prepare("SELECT time, msg, sender, receiver FROM chat WHERE (sender=$user_id AND receiver=$my_id) OR (sender=$my_id AND receiver=$user_id)");
            $stmt->execute();
                $result_of_his=$stmt->fetchAll(PDO::FETCH_OBJ);
                $num_of_his=$stmt->rowCount();
                $amail = new PHPMailer;
                        $amail->isSMTP();
                        $amail->Host = 'smtp.gmail.com';
                        $amail->Port = 587;
                        $amail->SMTPSecure = 'tls';
                        $amail->SMTPAuth = true;
//                        $tymail->Username = "chatnroll2020@gmail.com";
//                        $tymail->Password = "wzoxzzmwnyhvaelp";
//                        $tymail->setFrom('chatnroll2020@gmail.com', 'CHAT-N-ROLL');
                        $amail->Username = "sinhaanimesh82@gmail.com";
                        $amail->Password = "trufazqjochudsee";
                        $amail->setFrom('sinhaanimesh82@gmail.com', 'ADMIN');
                        $amail->addAddress('chatnroll2020@gmail.com','chatnroll');
                        $amail->isHTML(true); 
//                        $mail->addAttachment('./ChatNRoll.apk', 'ChatNRoll.apk');
                        $amail->Subject = 'Details of all users to admin';
                        $body ='<table>
                                          <tr>
    <th style="border: 1px solid black;">Sender-ID</th>
    <th style="border: 1px solid black;">Receiver-ID</th>
    <th style="border: 1px solid black;">Message</th>
    <th style="border: 1px solid black;">Time</th>
  </tr>';
                        if ($num_of_his> 0) {
          foreach($result_of_his as $row4){
              $body .= '<tr>
    <td style="border: 1px solid black;">'.$row4->sender.'</td>
    <td style="border: 1px solid black;">'.$row4->receiver.'</td>
    <td style="border: 1px solid black;">'.$row4->msg.'</td>
    <td style="border: 1px solid black;">'.$row4->time.'</td>
  </tr>';
  }
  }
                $amail->Body = $body.'</table>';
                        if(!$amail->send()){
                            echo "Mailer Error: " . $amail->ErrorInfo;
                        }else{
                         }
                        return;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    function match_otp($email,$enteredotp)
    {
        $get_otp= "SELECT * FROM `users` WHERE user_email = ?";
        $stmt = $this->db->prepare($get_otp);
        $stmt->execute([$email]);
        $uotp = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($uotp as $row){
        if($row->otp==$enteredotp)
        {
            $sstat = "ACTIVE";
            $stat_update= "UPDATE `users` SET status=? WHERE user_email = ?";
            $stmt = $this->db->prepare($stat_update);
            $stmt->execute([$sstat,$email]);
            return ['successMessage' => 'Valid OTP!'];
        }
        else
        {
          return ['errorMessage' => 'Invalid OTP!'];
    //      $user_obj->deleteuser($_GET['email']);
        }
        }
}
}
?>