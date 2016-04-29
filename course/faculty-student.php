<!DOCTYPE html>
<?php
  include 'Connection.php';
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

  $link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
  if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $user_name = $_SESSION["uname"];
   $sql="Select name from Teacher where uname='$user_name'";
 // echo $sql;
  $result=mysqli_query($link,$sql);
  $NAME=mysqli_fetch_array($result)[0];
  $cid=$_GET['cid'];
 // echo $cid;
  $check="Select uname from Teaches where cID=$cid";
  $result=mysqli_query($link,$check);
  $name=mysqli_fetch_array($result)[0];
  if($name!=$user_name)
  {
      header("Location:faculty.php");
  }
  $query2 = "select * from Course where cID = $cid";
  $result2=mysqli_query($link,$query2);
  $course_detail =mysqli_fetch_array($result2);
  $course_name=$course_detail[1];
  $start_date=$course_detail[2];
  $department=$course_detail[3];
  $weeks=$course_detail[4];

  ?>
<html lang="en">
<head>
  <title>
    <?php
      echo $NAME;
      ?>
  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="student.css">
</head>



<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="faculty.php">Moodle</a>
      </div>
      <ul class="nav navbar-nav navbar-left" style = "padding-right: 1%">
        <li><a href="#">Students</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">

        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><?php echo $NAME; ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="faculty.php">Home</a></li>
            <li><a href="edit-profile.php">My Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <div class="title">
        <span class="glyphicon glyphicon-education" ></span>
        <?php echo $course_name; ?>
      </div>
      <div class="date">
        Start date - <?php echo $start_date; ?>
      </div>
      <div class="date">
        Duration - <?php echo $weeks; ?> weeks
      </div>
      <table class="table">
    <thead>
      <tr>
        <th>Name</th>
<!--         <th>Registered On</th>
 -->        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $i=0;
      $sql="Select uname,week from Registered where cID=$cid";
      $result=mysqli_query($link,$sql);
      while($row=mysqli_fetch_array($result))
      {
        //echo $row[1]."HIII".$weeks;
        $y=$row[1]-1;
        $i=floor(($y*100/$weeks));

        echo "<tr class=\"success\">
                <td>".$row[0]."</td>
                <td>
                  <div class=\"progress\">
                    <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"".($i)."\"
                      aria-valuemin=\"0\" value=\"".$row[1]."weekscompleted\" aria-valuemax=\"100\" style=\"width:".($i)."%\">
                    ".($i)."% Complete (success)
                    </div>
                  </div>
                </td>";
         // $i=$i+1;
        }
    ?>
    </tbody>
  </table>

    </div>
    <div class="col-sm-2"></div>


    </div>
</body>
</html>
