<?php
function history_of_sender(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "frnd_req_system";
    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT sender, receiver, msg FROM chat WHERE sender=8 AND receiver=14";
$result_of_sender = mysqli_query($conn, $sql);
return $result_of_sender;
}


function history_of_receiver(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "frnd_req_system";
    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT sender, receiver, msg FROM chat WHERE sender=14 AND receiver=8";
$result_of_sender = mysqli_query($conn, $sql);
return $result_of_sender;
}



$result_of_sender=history_of_sender();
if (mysqli_num_rows($result_of_sender) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result_of_sender)) {
    echo "sender: " . $row->sender. "message:" . $row["msg"]. "<br>";
  }
} else {
  echo "0 results";
}

$result_of_receiver=history_of_receiver();
if (mysqli_num_rows($result_of_receiver) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result_of_receiver)) {
    echo "Receiver: " . $row["receiver"]. "message:" . $row["msg"]. "<br>";
  }
} else {
  echo "";
}


?>