<?php
include 'Connection.php';
session_start();
if(isset($_SESSION["uname"]))
{
    if($_SESSION["role"]!="Student")
    {
        header("Location:index.php");
    }

}
else
{
  header("Location:index.php");
}


$link=mysqli_connect($db_host,$db_username,$db_password,$db_name);
if (mysqli_connect_errno())
  {
  //////echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }



$uname=$_SESSION["uname"];
$cID=$_POST['cid'];
$week= $_POST['week'];
$score=$_POST['score'];
$question = $_POST['question'];

// $query="insert into Evaluation values($cID,$week,'$uname',$question,$score)";
// ////echo "HI";
// if(mysqli_query($link,$query)){
//   ////echo $query."<br>";
//   $query1 = "select week from Registered where uname= '$uname' and cID = $cID ";

//   $result = mysqli_query($link, $query1);

//   $current_week = mysqli_fetch_array($result)[0];
//   ////echo $week." ".$current_week;
//   if($week == $current_week){
//     ////echo $query1."<br>";
//     $query2 = "update Registered set week =". ($current_week  + 1)." where cID= $cID and uname='$uname' ";

//     mysqli_query($link, $query2);
//     ////echo $query2."<br>";
//     $query3 = "select no_of_weeks from Course where cID= $cID";
//     ////echo $query3."<br>";
//     $result2 = mysqli_query($link,$query3);
//     $no_of_weeks=mysqli_fetch_array($result2)[0];
//     if($week == $no_of_weeks){
//       $query4 = "update Registered set ongoing =0 where cID= $cID and uname='$uname' ";
//       ////echo $query4."<br>";
//       mysqli_query($link, $query4);

//     }
//   }
//   $res=array('status'=>1);
//       //echo json_encode($res);

// }else{
//   $res=array('status'=>0);
// 	//echo json_encode($res);
// }
   $query1 = "select week from Registered where uname= '$uname' and cID = $cID ";
   $result = mysqli_query($link, $query1);
   $current_week = mysqli_fetch_array($result)[0];
   if($current_week>$week)
   {
       $res=array('status'=>0);
       echo json_encode($res);
   }
   else if($current_week==$week)
   {
        //echo "HIII";
         $query="insert into Evaluation values($cID,$week,'$uname',$question,$score)";
        if(mysqli_query($link,$query)){
            //echo $query1."<br>";
            $query2 = "update Registered set week =". ($current_week  + 1)." where cID= $cID and uname='$uname' ";

            mysqli_query($link, $query2);
            //echo $query2."<br>";
            $query3 = "select no_of_weeks from Course where cID= $cID";

            $result2 = mysqli_query($link,$query3);
             $no_of_weeks=mysqli_fetch_array($result2)[0];
             //echo $query3."<br>".$no_of_weeks;
            if($week == $no_of_weeks){
              $query4 = "update Registered set ongoing =0 where cID= $cID and uname='$uname' ";
              //echo $query4."<br>";
              mysqli_query($link, $query4);
            }



          }
           $res=array('status'=>1);
        echo json_encode($res);

   }
   else
   {
      $res=array('status'=>2);
       echo json_encode($res);
   }


?>
