<!--
  IMPORTANT - First create a folder named "uploads" or pass the name of the folder
  where you need to save the file.

  Folder name is stored in $target_dir

  $uploadOk is a variable that indicates at the end of this script, if the given
  file was uploaded successfully or not.
  $uploadOk = 1 means it was successfully uploaded.
-->

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
else
{
  header("Location:index.php");
}
$uname=$_SESSION["uname"];
$sql="Select * from Course";
$result=mysqli_query($link,$sql);
$course_id=mysqli_num_rows($result);
$course_id=$course_id+1;
echo $course_id;

mkdir("uploads");
mkdir("uploads/syllabus");
mkdir("uploads/syllabus/".$course_id);
$target_dir = "uploads/syllabus/".$course_id."/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
echo "<p>".$target_file."</p>";
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  echo $target_file;
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
     //   echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
        $uploadOk = 0;
    }
}
/*echo "Hiiiii";
$ar=$_POST["myInputs"];
echo $ar[0];
echo $ar[1];
echo "Hiiiii";
echo $_POST["cName"];*/
$name=$_POST["cName"];
$start_date=$_POST["start_date"];
$weeks=$_POST["weeks"];
$fees=$_POST["fees"];
$ar=$_POST["myInputs"];
$dept=$_POST["dept"];
if($uploadOk==1)
{
  $sql="Insert into Course(cID,cName,start_date,Department,no_of_weeks,fees,syllabus) values($course_id,'$name','$start_date','$dept',$weeks,$fees,'$target_file')";
  $result=mysqli_query($link,$sql);
  foreach($ar as $x)
  {
    $cid=strtok($x," ");
    $sql="Insert into Prerequiste values($course_id,$cid)";
    echo $sql;
    $result=mysqli_query($link,$sql);
  }
  $sql="Insert into Teaches values($course_id,'$uname')";
  $result=mysqli_query($link,$sql);
}

header("Location: faculty.php");
?>
