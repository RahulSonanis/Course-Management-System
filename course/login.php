<?php
include 'Connection.php';
session_start();
if(isset($_SESSION["uname"]))
{
    
    if(strcmp($_SESSION["role"],"Student") == 0)
    {
        header("Location: student.php");
    }
    if($_SESSION["role"]==="Teacher")
    {

        header("Location:faculty.php");
    }
    if($_SESSION["role"]==="Parent")
    {

        header("Location:parent.php");
    }
    if($_SESSION["role"] === "Admin")
    {

        header("Location:admin.php");
    }

}
$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
$name=$_POST['name'];
 $name=mysqli_real_escape_string($link,$name);
$emailid=$_POST['email'];
$emailid=mysqli_real_escape_string($link,$emailid);
$contact=$_POST['contact'];
$contact=mysqli_real_escape_string($link,$contact);
$uname=$_POST['uname'];
$uname=mysqli_real_escape_string($link,$uname);
$pwd=$_POST['pwd'];
$pwd=mysqli_real_escape_string($link,$pwd);
$role=$_POST['role'];
$role=mysqli_real_escape_string($link,$role);

$query="Select * from ".$role." where uname='$uname' AND pwd='$pwd'";
$result=mysqli_query($link,$query);
$num_rows=mysqli_num_rows($result);
if($num_rows>0)
{
	session_start();
	$_SESSION["uname"] = $uname;
	$_SESSION["role"]= $role;
	$res=array('status'=>1);
	echo json_encode($res);
}
else if($role!="Teacher")
{
	$res=array('status'=>0);
	echo json_encode($res);
}
else
{
	$res=array('status'=>3);
	echo json_encode($res);
}
?>