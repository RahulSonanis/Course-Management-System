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

$query="Select * from Teacher where uname='$uname' ";
$result=mysqli_query($link,$query);
$num_rowst=mysqli_num_rows($result);


$query="Select * from TeacherUnv where uname='$uname' ";
$result=mysqli_query($link,$query);
$num_rowsu=mysqli_num_rows($result);

$query="Select * from Parent where uname='$uname' ";
$result=mysqli_query($link,$query);
$num_rowsp=mysqli_num_rows($result);

$query="Select * from Student where uname='$uname' ";
$result=mysqli_query($link,$query);
$num_rowss=mysqli_num_rows($result);

$query="Select * from Admin where uname='$uname' ";
$result=mysqli_query($link,$query);
$num_rowsa=mysqli_num_rows($result);

$num_rows=$num_rowsa+$num_rowst+$num_rowsp+$num_rowss+$num_rowsu;
//echo $num_rows;
// $res=array('status'=>$num_rowsa);
// echo json_encode($res);

if($num_rows>0)
{
	$res=array('status'=>0);
	echo json_encode($res);
}
else
{
	$query="Select * from Teacher  where email='$emailid' ";
	$result=mysqli_query($link,$query);
	$num_rowst=mysqli_num_rows($result);


	$query="Select * from TeacherUnv  where email='$emailid' ";
	$result=mysqli_query($link,$query);
	$num_rowsu=mysqli_num_rows($result);

	$query="Select * from Parent  where email='$emailid' ";
	$result=mysqli_query($link,$query);
	$num_rowsp=mysqli_num_rows($result);

	$query="Select * from Student where  email='$emailid' ";
	$result=mysqli_query($link,$query);
	$num_rowss=mysqli_num_rows($result);

	$query="Select * from Admin  where email='$emailid' ";
	$result=mysqli_query($link,$query);
	$num_rowsa=mysqli_num_rows($result);

	$num_rows=$num_rowsa+$num_rowst+$num_rowsp+$num_rowss+$num_rowsu;
	if($num_rows>0)
	{
		$res=array('status'=>1);
		echo json_encode($res);
	}
	else
	{
		if($role=='Teacher')
		{
			$query="Select * from TeacherUnv where uname='$uname'";
			$result=mysqli_query($link,$query);
			$num_rows=mysqli_num_rows($result);
			//echo $num_rows;
			if($num_rows>0)
			{
				$res=array('status'=>0);
				echo json_encode($res);
			}
			else
			{
				$query="Select * from TeacherUnv where email='$emailid'";
				 $result=mysqli_query($link,$query);
				 $num_rows=mysqli_num_rows($result);
				if($num_rows>0)
				{
					$res=array('status'=>1);
					echo json_encode($res);
				}
				else
				{
					$query="insert into TeacherUnv values('$name','$emailid',$contact,'$uname','$pwd')";
					$result=mysqli_query($link,$query);
					
					$res=array('status'=>3 );
					echo json_encode($res);
				}
			}
			
		}
		else
		{
			$query="insert into ".$role." values('$name','$emailid',$contact,'$uname','$pwd')";
			$result=mysqli_query($link,$query);
			
			$res=array('status'=>2 );
			echo json_encode($res);
		}
	}
}

?>