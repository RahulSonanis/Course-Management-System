<?php
include 'Connection.php';

$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
$sname=$_POST['sname'];
$pname=$_POST['pname'];

$sql="Insert into Guardian values('$sname','$pname')";
$res=mysqli_query($link,$sql);
//$ar=array("status"=>$sql);
$sql="Delete From GuardianUnv where sName='$sname' and pName='$pname'";
$res=mysqli_query($link,$sql);
$ar=array("status"=>1);
echo json_encode($ar);
?>