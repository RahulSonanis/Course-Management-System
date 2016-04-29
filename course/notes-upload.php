<?php
include 'Connection.php';
$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
session_start();
if(isset($_SESSION["uname"]))
{
    if($_SESSION["role"]!="Teacher")
    {
      //  alert("You are not allowed here!!!!");
        header("Location:index.php");
    }

}
$uname=$_SESSION["uname"];
$cid=$_POST['cid'];
$week=$_POST['week'];
$info=$_POST['info'];
$type=$_POST['content-type'];
//echo $cid." ".$week." ".$info." ".$type;
if($type=="Notes")
{
	//echo $cid." ".$week." ".$info." ".$type;
	$target_dir="uploads/notes/".$cid."/week".$week;

	//echo "uploads/notes/".$cid."/week".$week;
	mkdir("uploads/notes/".$cid."/week".$week,0777,true);
	$target_dir=$target_dir."/";
	//echo "HIII".$_FILES["uploaded_file"]["name"];
	$target_file = $target_dir . basename($_FILES["uploaded_file"]["name"]);
	$uploadOk = 1;
	//echo "<p>".$target_file."</p>";
	// Check if file already exists
	if (file_exists($target_file)) {
	    //echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if ($uploadOk == 0) {
	    //echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  //echo $target_file;
	    if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
	     //   //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	    } else {
	        //echo "Sorry, there was an error uploading your file.";
	        $uploadOk = 0;
	    }
	}
	if($uploadOk==1)
	{
		// $sql="insert into Notes values($cid,$week,'$target_file','$info')";
		//echo "<br>".$sql;
        $sql="insert into Notes values($cid,$week,'$target_file','$info','".date("Y-m-d")."')";
		$result=mysqli_query($link,$sql);
		//echo "SUCCESS";

	}
	header("Location:faculty-course.php?cid=".$cid);
	//echo "SUCCESS";
}
else
{
	////echo $cid." ".$week." ".$info." ".$type;
	$target_dir="uploads/ass/".$cid."/week".$week;
	echo "uploads/ass/".$cid."/week".$week;
	mkdir("uploads/ass/".$cid."/week".$week,0777,true);
	$target_dir=$target_dir."/";
	//echo "HIII".$_FILES["uploaded_file"]["name"];
	$target_fileques = $target_dir . basename("ques".$_FILES["uploaded_file"]["name"]);
	$uploadOkques = 1;
	echo "<p>".$target_fileques."</p>";
	// Check if file already exists
	if (file_exists($target_fileques)) {
	   // //echo "Sorry, file already exists.";
	    $uploadOkques = 0;
	}
	$imageFileTypeques = pathinfo($target_fileques,PATHINFO_EXTENSION);
	if ($uploadOkques == 0) {
	  echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  //echo $target_file;
	    if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_fileques)) {
	    // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	        $uploadOkques = 0;
	    }
	}
	$target_fileans = $target_dir . basename("ans".$_FILES["uploaded_ans"]["name"]);
	$uploadOkans = 1;
	echo "<p>".$target_fileans."</p>";
	// Check if file already exists
	if (file_exists($target_fileans)) {
	   // //echo "Sorry, file already exists.";
	    $uploadOkans = 0;
	}
	$imageFileTypeans = pathinfo($target_fileans,PATHINFO_EXTENSION);
	if ($uploadOkans == 0) {
	  //  //echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  //echo $target_file;
	    if (move_uploaded_file($_FILES["uploaded_ans"]["tmp_name"], $target_fileans)) {
	     //   //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	    } else {
	        //echo "Sorry, there was an error uploading your file.";
	        $uploadOkans = 0;
	    }
	}
	if($uploadOkques==1 and $uploadOkans==1)
	{
		//$sql="insert into Quiz values($cid,$week,'$target_fileques','$info','$target_fileans')";
		////echo "<br>".$sql;
        $sql="insert into Quiz values($cid,$week,'$target_fileques','$info','$target_fileans','".date("Y-m-d")."')";
		$result=mysqli_query($link,$sql);
		echo "SUCCESS";

	}
	header("Location:faculty-course.php?cid=".$cid);
}

?>
