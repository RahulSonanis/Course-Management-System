<?php
  include 'Connection.php';
  session_start();
  if(isset($_SESSION["uname"]))
  {

  }
  else
  {
    header("Location:index.php");
  }

  $link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $sender=$_POST['sender'];
  $recv=$_POST['recv'];
  $msg=$_POST['msg'];
  $sub=$_POST['sub'];
  $sender_name=$_POST['sender_name'];
  $sql="Insert into Messages(sender,receiver,msg_body,read_msg,sender_name,msg_subject) values('$sender','$recv','$msg',0,'$sender_name','$sub')";
  mysqli_query($link,$sql);
  $res=array('status' => 1 );
  echo json_encode($res);

?>
