<?php
include 'Connection.php';

$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
//mysqli_select_db($db_name);
//echo $db_host;
$name=$_POST['name'];
 // $name=stripslashes($name);
 $name=mysqli_real_escape_string($link,$name);
// //echo $name;
$emailid=$_POST['email'];
//$emailid=stripslashes($emailid);
$emailid=mysqli_real_escape_string($link,$emailid);
// //echo $emailid;
$contact=$_POST['contact'];
//$contact=stripslashes($contact);
$contact=mysqli_real_escape_string($link,$contact);
//echo $contact;
$uname=$_POST['uname'];
//$uname=stripslashes($uname);
$uname=mysqli_real_escape_string($link,$uname);
//echo $uname;
$pwd=$_POST['pwd'];
//$pwd=stripslashes($pwd);
$pwd=mysqli_real_escape_string($link,$pwd);

$role=$_POST['role'];
//$role=stripslashes($role);
$role=mysqli_real_escape_string($link,$role);

$sql="Insert into Teacher values('$name','$emailid',$contact,'$uname','$pwd')";
$res=mysqli_query($link,$sql);
$sql="Delete From TeacherUnv where uname='$uname'";
$res=mysqli_query($link,$sql);
$ar=array("status"=>1);
echo json_encode($ar);
?>