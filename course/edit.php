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
  $uname=$_SESSION['uname'];
  $role=$_SESSION['role'];
  $name=$_POST['name'];
  $pwd=$_POST['pwd'];
  $email=$_POST['email'];
  $contact=$_POST['contact'];

  $query="Select * from ".$role." where email='$email' and uname!='$uname'";
  $result=mysqli_query($link,$query);
  $num_rows=mysqli_num_rows($result);

  if($num_rows>0)
  {
      $res=array('status'=>0);
      echo json_encode($res);
  }
  else
  {
      $sql="Update ".$role." set name='$name',email='$email',contact=$contact,pwd='$pwd' where uname='$uname'";
      $result=mysqli_query($link,$sql);
      $res=array('status'=>1 );
      echo json_encode($res);
  }
?>
