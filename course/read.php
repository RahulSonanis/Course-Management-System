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
  $sql="Update Messages set read_msg=1 where receiver='$recv' and sender='$sender'";
  mysqli_query($link,$sql);
  $res=array('status' => 1);
  echo json_encode($res);

?>
