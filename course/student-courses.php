<!DOCTYPE html>
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
$cid=$_GET['cID'];
$sql="select * from Registered where cID=$cid and uname='$user_name'";
$result=mysqli_query($link,$sql);
$num_rows=mysqli_num_rows($result);
//echo $num_rows;
if($num_rows==0)
{
    header("Location:student.php");
}
$query = "select * from Course where cID = $cid";
$result=mysqli_query($link,$query);
$course_detail =mysqli_fetch_array($result);

$course_name        = $course_detail[1];
$course_start_date  = $course_detail[2];
$course_department  = $course_detail[3];
$course_weeks       = $course_detail[4];
$course_syllabus    = $course_detail[6];
//echo $course_syllabus;
$query = "select * from Registered where cID = $cid and uname= '$user_name'";
$result=mysqli_query($link,$query);
$details = mysqli_fetch_array($result);
$current_running_week = $details[2];


$sql="(Select N.week, N.info, N.uploaded_date from Notes as N, Registered as R
where R.uname='$user_name' and R.cID = $cid and R.cID = N.cID and R.ongoing = 1)
union
(Select N.week, N.info, N.uploaded_date from Quiz as N, Registered as R
where R.uname='$user_name' and R.cID = $cid and R.cID = N.cID and R.ongoing = 1)
order by uploaded_date desc";
$news = mysqli_query($link, $sql);


$sql = "select name,email from Teacher natural join Teaches where cId= $cid";
$teacher_info = mysqli_fetch_array(mysqli_query($link,$sql));
$teacher_name = $teacher_info[0];
$teacher_mail = $teacher_info[1];

?>


<html lang="en">
<head>
    <title>
        <?php
        $query2 = "select name from Student where uname = '$user_name'";
        $result2=mysqli_query($link,$query2);
        $Student_name =mysqli_fetch_array($result2);
        echo $Student_name[0];
        ?>
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="student-course.css">
</head>



<body style="background-color:#f2f2f2;">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="student.php">Moodle</a>
            </div>
            <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">
                <div class="navbar-header">
                    <a class="navbar-brand" href="AllCourses.php">All Courses</a>
                </div>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><?php echo $Student_name[0];?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="student.php">Home</a></li>
                        <li><a href="edit-profile.php">My Profile</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <a href = "<?php echo $course_syllabus; ?>" >
        <div class="course-name">
            <span class="glyphicon glyphicon-education" ></span>
            <?php echo $course_name; ?>
        </div>
    </a>
    <?php echo "<div class = \"begin-date\">
    Offered by $course_department<br>
    Started on  $course_start_date<br>
    Duration: $course_weeks Weeks
    </div>
    <div class = \"begin-date\" style= \" font-weight: bold;\">
    Instructor: <a href=\"mailto:$teacher_mail\">$teacher_name</a><br>
    </div>";
    ?>
    <hr>

    <div class="row">
        <!-- <div class="col-sm-1"> </div> -->

        <div class="col-sm-7" style = "padding-left:7%; padding-right: 5%;">
            <div class="panel-group" role="tablist" aria-multiselectable="true" id="accordion">
                <?php $current_week = 1;
                while($current_week <= $course_weeks){
                    echo "<div class=\"panel panel-default\" >
                    <div class=\"panel-heading\" id=\"heading".$current_week."\" role=\"tab\" ";

                    if($current_week==$current_running_week){
                        echo " style=\"background-color:#4CAF50;color:white;\" ";
                    }
                    echo " >
                    <h4 style=\"font-weight:bold;\" class=\"panel-title\">
                    <a role=\"button\" data-toggle=\"collapse\" href=\"#collapse".$current_week."\" parent=\"#accordion\" aria-expanded=\"true\" aria-controls=\"collapse".$current_week."\" >
                    Week $current_week
                    </a>
                    </h4>
                    </div>
                    <div id=\"collapse".$current_week."\" style=\"background-color:white;\" class=\"panel-collapse collapse ";
                    if($current_week==$current_running_week)
                    {echo " in";}
                    echo "\" role=\"tabpanel\" aria-labelledby=\"heading".$current_week."\"><div class=\"panel-body\">";
                    ?>

                    <ul class = "notes" style="list-style-type:none">
                        <li >
                            <span style = "font-size: 125%; text-decoration: underline;">Notes</span>
                            <ul>
                                <?php
                                $query_for_notes = "select link,info from Notes where cID = $cid and week=$current_week";
                                $notes = mysqli_query($link, $query_for_notes);

                                while($row = mysqli_fetch_array($notes)){
                                    $x = strrev($row[0]);
                                    $y = strtok($x,"/");
                                    $z = strrev($y);
                                    echo "
                                    <li>
                                    <div class=\"week-notes\">
                                    <div class=\"notes-info\">
                                    $row[1]
                                    </div>
                                    <ul style=\"list-style-type:none\">
                                    <li>
                                    <a href= \"$row[0]\" >
                                    <div class = \"notes-link\">
                                    <span class=\"glyphicon glyphicon-file\"></span>
                                    &nbsp;
                                    $z
                                    </div>
                                    </a>
                                    </li>
                                    </ul>
                                    </div>
                                    </li>
                                    ";
                                } ?>
                            </ul>
                        </li>

                        <?php
                        if($current_week <= $current_running_week){
                            echo "<li>
                            <span align = \"center\" style = \"font-size: 125%; text-decoration: underline;\"> Quiz </span>
                            <ul>";

                            $query_for_quiz = "select qlink,alink,info from Quiz where cID = $cid and week=$current_week";
                            $quiz = mysqli_query($link, $query_for_quiz);

                            while($row = mysqli_fetch_array($quiz)){
                                $x = strrev($row[0]);
                                $y = strtok($x,"/");
                                $z = strrev($y);
                                echo "
                                <li>
                                <div class=\"week-notes\">
                                <div class=\"notes-info\">
                                $row[2]
                                </div>
                                <ul style=\"list-style-type:none\">
                                <li>
                                <a href= \"test.php?qlink=$row[0]&alink=$row[1]\" >
                                <div class = \"notes-link\">
                                <span class=\"glyphicon glyphicon-file\"></span>
                                &nbsp;
                                $z
                                </div>
                                </a>
                                </li>
                                </ul>
                                </div>
                                </li>";
                            }
                            echo "</ul></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php $current_week++;
    }
    ?>
</div>
</div>
<div class="col-sm-5" style = "padding-right:7%;">
    <div style="background-color:#ffffff; padding: 2%;">
        <h4 style="color:grey;"><span class="glyphicon glyphicon-bullhorn" style="width:20px;height:25px;"></span>  Latest News</h4>
        <div  style="overflow-y:auto;max-height: 200px;">
            <ul  style="list-style-type:none">
                <?php
                while($row=mysqli_fetch_array($news)){
                    echo "<li><div class=\"head clearfix\">Week-$row[0] Uploads: $row[1]<div class=\"date\" style=\"font-size:10pt;color:grey;\">$row[2]</div></div><div class=\"info\"></div></li>
                    <hr style=\"margin:1%;color:#f2f2f2\">";
                }
                ?>

            </ul>
        </div>
    </div>

    <div style="margin: 5%; text-align:center;">
        <button id = "grade-button" class="button3 button2">
            Show Grades</button>
        </div>

        <div id = "grades" class="grades">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Grade Item</th>
                        <th>Grade</th>
                        <th>Range</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_for_grades = "select week, obt_marks, total_marks
                        from Evaluation where cID = $cid and uname = '$user_name' order by week asc";
                    $grades = mysqli_query($link, $query_for_grades);
                    $total_marks_obtained = 0;
                    $total_marks = 0;
                    $week_number = 1;
                    while($week_number <= $course_weeks){
                        echo "<tr><td>Week $week_number </td>";
                        if($week_number < $current_running_week){
                            $row1 = mysqli_fetch_array($grades);
                            $total_marks_obtained += $row1[1];
                            $total_marks += $row1[2];
                            echo "<td>$row1[1]</td><td>0-$row1[2]</td>";
                            $perc = ceil(($row1[1]*1000)/($row1[2]*10));
                            echo "<td> $perc %</td>";
                        }
                        else{
                            echo "<td> - </td><td> - </td><td> - </td>";
                        }
                        echo "</tr>";

                        $week_number++;
                    }
                    echo "<tr style=\"font-weight: bolder;\"><td>Course Total</td>";
                    if($current_running_week <= $course_weeks){
                        echo "<td> - </td><td> - </td><td> - </td>";
                    }
                    else{
                        echo "<td>$total_marks_obtained</td><td>0-$total_marks</td>";
                        $perc = ceil(($total_marks_obtained*1000)/($total_marks*10));
                        echo "<td> $perc %</td>";
                    }
                    echo "</tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <div class="deregister">
            <button id = "deregister" data-toggle="modal" data-target="#myModal" class="button button2">
                Un-enroll me from this Course</button>
            </div>
        </div>
        <!-- <div class="col-sm-1"> </div> -->
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 align ="center" style="font-weight: bolder;" class="modal-title" id="modal-title">Un-enroll</h3>
                </div>

                <form method="POST" enctype="multipart/form-data" role="form">
                    <div class="modal-body">
                        <h4 align="center"> Are you sure you want to un-enroll from this course?</h4>
                        <div style="text-align: center;">
                            <button type="button" onclick = "deregister()"class="btn btn-primary close-button">Confirm</button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row">
                            <div style = "text-align:center;">
                                <button type="button" class="btn btn-primary close-button" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
    function myfunction()
    {
        alert(document.getElementById("uploaded_file2").value);
    }

    $('#grade-button').click(function() {

        $('#grades').toggle();
        var e = document.getElementById("grades");
        if(e.style.display == 'block')
        $("#grade-button").html('Hide Grades');
        else
        $("#grade-button").html('Show Grades');
    });

    function deregister()
    {
        var uname = <?php echo json_encode($user_name);?>;
        var cid = <?php echo json_encode($cid);?>;
        $.ajax(
	      {
	        url: "deregister.php",
	        type:"post",
	        dataType:"json",
	        data:
	        {
	        	uname:uname,
	            cid: cid
	        },

	        success: function(json)
	        {
                // alert(json.status);
                location.href = "student.php";
	        },

	        error : function()
	        {
	          alert("ERROR");
	        }
	      });
    }
    </script>
</body>
