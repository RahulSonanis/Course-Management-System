<?php
 include 'Connection.php';
  session_start();
  if(isset($_SESSION["uname"]))
  {
      if($_SESSION["role"]!="Parent")
      {
       // echo "HII";
         // alert("You are not allowed here!!!!");
          header("Location:index.php");
      }

  }
  else
  {
    header("Location:index.php");
  }
$sname=$_POST['child'];
$pname=$_SESSION["uname"];
// echo $sname;
$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
$sql="Insert into GuardianUnv values('$sname','$pname')";
$res=mysqli_query($link,$sql);

$arrayName = array('status' => 1 );
echo json_encode($arrayName);
// header("Location:parent.php");
?>
