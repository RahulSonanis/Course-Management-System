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

$user_name = $_SESSION["uname"];
$cid=$_POST['cid'];

$query = "delete from Evaluation where uname= '$user_name' and cID=$cid";
mysqli_query($link,$query);

$query = "delete from Registered where uname='$user_name' and cID=$cid";
mysqli_query($link,$query);

// header("Location: student.php");
$arr  = array('status' => 1 );
echo json_encode($arr);
?>
