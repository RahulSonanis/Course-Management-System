<?php
include 'Connection.php';
session_start();
if(isset($_SESSION["uname"]))
{
    if($_SESSION["role"]!="Student")
    {

        //alert("You are not allowed here!!!!");
        header("Location:index.php");
    }

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


$uname=$_SESSION["uname"];
$cID=$_POST['cID'];

$query="insert into Registered values($cID,'$uname',1,1)";

if(mysqli_query($link,$query)){
  $res=array('status'=>1);
	echo json_encode($res);
}
else{
  $res=array('status'=>0);
	echo json_encode($res);
}

?>
