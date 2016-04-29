<!DOCTYPE html>
<?php
  include 'Connection.php';
  session_start();
  if(isset($_SESSION["uname"]))
  {
      if($_SESSION["role"]!="Parent")
      {

         // alert("You are not allowed here!!!!");
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
  $sname=$_GET['sname'];
  $sql="Select cID from Registered where uname='$sname'";
  $result=mysqli_query($link,$sql);
  $cid=array();
  while($row=mysqli_fetch_array($result))
  {
    $cid[]=$row[0];
    //echo $row[0];
  }


  ?>
<html lang="en">
<head>
  <title>
    <?php
      echo $sname;
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
      <div class="navbar-header nav-text">
        Parent Dashboard
      </div>
      <ul class="nav navbar-nav navbar-left" style = "padding-right: 1%">
        <li><a href="parent.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">

        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><?php echo $user_name; ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="parent.php">Home</a></li>
            <li><a href="#">My Profile</a></li>
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
        <span class="glyphicon glyphicon-user" ></span>
        <?php echo $sname; ?>
      </div>
      <table class="table">
    <thead>
      <tr>
        <th>Course Name</th>
        <th>week</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $i=0;
    while($i<count($cid))
    {
      $sql="Select cName,no_of_weeks from Course where cID=$cid[$i]";
     // echo $sql."<br>";
      $result=mysqli_query($link,$sql);
      $row=mysqli_fetch_array($result);
      $course_name=$row[0];
      $total_weeks=$row[1];
      $sql="Select week from Registered where cID='$cid[$i]' and uname='$sname'";
     // echo $sql." ".$cid[$i]."<br>";
       $result=mysqli_query($link,$sql);
       $current_week=mysqli_fetch_array($result)[0];

       $prog=floor((($current_week-1)*100)/$total_weeks);
    //   echo $prog;
      echo "<tr class=\"success clicker\" id=\"course".$i."\">
              <td>".$course_name."</td>
              <td><a href=\"#\" id=\"grade".$i."\" class=\"toggler\" data-prod-cat=\"".$i."\">+View Grades</td>
              <td>
                <div class=\"progress\">
                  <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"".$prog."\"
                    aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$prog."%\">
                  ".$prog."% Complete (success)
                  </div>
                </div>
              </td>";
        $j=1;
        $sql="Select total_marks,obt_marks from Evaluation where cID='$cid[$i]' and uname='$sname'";
       // echo $sql;
        $obt_marks=array();
        $total_marks=array();
        $result=mysqli_query($link,$sql);
        while($row=mysqli_fetch_array($result))
        {
            $obt_marks[]=$row[1];
            $total_marks[]=$row[0];
           // echo $obt_marks." ".$total_marks."<br>";
        }
        while($j<$current_week){
          echo "<tr class=\"success cat".$i."\" style=\"display:none;\">
              <td></td>
              <td>Week ".$j."</td>
              <td>".$obt_marks[$j-1]."/".$total_marks[$j-1]."</td>";
              $j=$j+1;
        }
        $i=$i+1;
    }
    ?>
    </tbody>
  </table>

    </div>
    <div class="col-sm-2"></div>


    </div>
    <script type="text/javascript">
     /**/
     $(".toggler").click(function(e){
        e.preventDefault();
        $('.cat'+$(this).attr('data-prod-cat')).toggle();
        if(document.getElementById("grade"+$(this).attr('data-prod-cat')).innerHTML=="+View Grades")
        {
          document.getElementById("grade"+$(this).attr('data-prod-cat')).innerHTML="-Hide Grades";
        }
        else
        {
          document.getElementById("grade"+$(this).attr('data-prod-cat')).innerHTML="+View Grades";
        }
    });

    </script>
</body>
</html>
