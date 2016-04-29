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

$query = "select name from Student where uname = '$user_name'";
$result=mysqli_query($link,$query);
$Student_name =mysqli_fetch_array($result);

$query = "select CID, cName, start_date, Department, no_of_weeks, fees, syllabus, name, email, contact
from Course natural join Teaches natural join Teacher";
$all_courses=mysqli_query($link,$query);



?>
<html lang="en">
<head>
    <title>
        <?php
        echo $Student_name[0];
        ?>
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="student1.css">

</head>



<body style="background-color:#e9eaea;">
    <script src="student.js"></script>
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

    <h1 align ="center"> All Courses Offered </h1>

    <div class = "search-box">
        <form>
            <input onkeyup = "showResult(this.value)" class = "search" type="text" placeholder="Search..." required autofocus>
            <!-- <input class = "search-button" type="button" value="Search"> -->
        </form>
    </div>
    <!--<! /input-group -->
    <div id ="livesearch" >
        <?php

        while($row=mysqli_fetch_array($all_courses))
        {

            echo "<div id = $row[0] class = \"course-content\">
            <div class = \"course-id-name\">
            <a href = \"$row[6]\" >
            <span class=\"glyphicon glyphicon-education\"></span><br>".$row[0].": ".$row[1]."</a>
            </div>
            <div class = \"begin-date\">
            Offered by $row[3]<br>";


            $query = "select * from Registered where uname = '$user_name' and cID = $row[0]" ;
            $ongoing = mysqli_query($link, $query);
            $num_rows=mysqli_num_rows($ongoing);
            $today = date("Y-m-d");
            $course_begin_date = "$row[2]"; //from db


            $query = "select P.preID from Prerequiste as P where P.cID = $row[0]
            and P.preID not in
            (select cID from Registered where uname = '$user_name' and ongoing = 0)" ;

            $Prerequiste = mysqli_num_rows(mysqli_query($link, $query));

            $query = "select P.preID, C.cName from Prerequiste as P, Course as C where P.cID = $row[0]
            and P.preID = C.cID" ;
            $Prerequiste_courses = mysqli_query($link, $query);
            $number_prerequisite_courses = mysqli_num_rows($Prerequiste_courses);



            if( $num_rows > 0){

                echo "Started on $row[2]<br>
                Duration: $row[4] Weeks</div>
                ";
                ?>


                <div class = "row">

                    <div class = "col-sm-4">
                        <div class = "begin-date" style= "text-align: left; font-weight: bold;">
                            <?php
                            echo "Instructor:   $row[7]<br>
                            Email:    <a href=\"mailto:$row[8]\">$row[8]</a><br>
                            Contact:      $row[9]";
                            ?>
                        </div>

                    </div>



                    <?php
                    if($number_prerequisite_courses > 0){

                        echo "<div class = \"col-sm-5\"></div>
                        <div class = \"col-sm-3\"><div class = \"begin-date\" style= \"text-align: left;\">
                        <div class = \"begin-date\" style= \"text-align: left; font-weight: bold;\">
                        Prerequistes:</div><ul>";
                        while($row2 = mysqli_fetch_array($Prerequiste_courses)){
                            echo "<li> $row2[0] $row2[1]</li>";
                        }

                        echo "</ul>";
                    }
                    else{
                        echo "<div class = \"col-sm-4\"></div>
                        <div class = \"col-sm-4\"><div class = \"begin-date\">
                        <div class = \"begin-date\" style= \" font-weight: bold;\">
                        No <br> Prerequistes <br> Required </div>";
                    }
                    echo "</div>";
                    echo "</div></div>";

                    echo "<div class = \"button-class\">
                    <div class = \"fees\"> Fees:  Rs.$row[5]/- </div>
                    <button id = \"registered\" class=\"button disabled\">Registered</button> </div></div>";
                }
                else if($today < $course_begin_date){

                    echo "Starts on $row[2]<br>
                    Duration: $row[4] Weeks</div>";

                    ?>


                    <div class = "row">

                        <div class = "col-sm-4">
                            <div class = "begin-date" style= "text-align: left; font-weight: bold;">
                                <?php
                                echo "Instructor:   $row[7]<br>
                               Email:    <a href=\"mailto:$row[8]\">$row[8]</a><br>
                                Contact:      $row[9]";
                                ?>
                            </div>

                        </div>



                        <?php
                        if($number_prerequisite_courses > 0){

                            echo "<div class = \"col-sm-5\"></div>
                            <div class = \"col-sm-3\"><div class = \"begin-date\" style= \"text-align: left;\">
                            <div class = \"begin-date\" style= \"text-align: left; font-weight: bold;\">
                            Prerequistes:</div><ul>";
                            while($row2 = mysqli_fetch_array($Prerequiste_courses)){
                                echo "<li> $row2[0] $row2[1]</li>";
                            }

                            echo "</ul>";
                        }
                        else{
                            echo "<div class = \"col-sm-4\"></div>
                            <div class = \"col-sm-4\"><div class = \"begin-date\">
                            <div class = \"begin-date\" style= \" font-weight: bold;\">
                            No <br> Prerequistes <br> Required </div>";
                        }
                        echo "</div>";
                        echo "</div></div>";


                        echo " <div class = \"button-class\">
                        <div class = \"fees\"> Fees:  Rs.$row[5]/- </div>
                        <button id = \"registered\" class=\"button disabled\">Yet to start</button> </div></div>";
                    }
                    else if($Prerequiste > 0){

                        echo "Started on $row[2]<br>
                        Duration: $row[4] Weeks</div>";

                        ?>


                        <div class = "row">

                            <div class = "col-sm-4">
                                <div class = "begin-date" style= "text-align: left; font-weight: bold;">
                                    <?php
                                    echo "Instructor:   $row[7]<br>
                                   Email:    <a href=\"mailto:$row[8]\">$row[8]</a><br>
                                    Contact:      $row[9]";
                                    ?>
                                </div>

                            </div>



                            <?php
                            if($number_prerequisite_courses > 0){

                                echo "<div class = \"col-sm-5\"></div>
                                <div class = \"col-sm-3\"><div class = \"begin-date\" style= \"text-align: left;\">
                                <div class = \"begin-date\" style= \"text-align: left; font-weight: bold;\">
                                Prerequistes:</div><ul>";
                                while($row2 = mysqli_fetch_array($Prerequiste_courses)){
                                    echo "<li> $row2[0] $row2[1]</li>";
                                }

                                echo "</ul>";
                            }
                            else{
                                echo "<div class = \"col-sm-4\"></div>
                                <div class = \"col-sm-4\"><div class = \"begin-date\">
                                <div class = \"begin-date\" style= \" font-weight: bold;\">
                                No <br> Prerequistes <br> Required </div>";
                            }
                            echo "</div>";
                            echo "</div></div>";

                            echo "<div class = \"button-class\">
                            <div class = \"fees\"> Fees:  Rs.$row[5]/- </div>
                            <button id = \"registered\" class=\"button disabled\">Ineligible</button> </div></div>";
                        }
                        else{
                            echo "Started on $row[2]<br>
                            Duration: $row[4] Weeks</div>";

                            ?>


                            <div class = "row">

                                <div class = "col-sm-4">
                                    <div class = "begin-date" style= "text-align: left; font-weight: bold;">
                                        <?php
                                        echo "Instructor:   $row[7]<br>
                                       Email:    <a href=\"mailto:$row[8]\">$row[8]</a><br>
                                        Contact:      $row[9]";
                                        ?>
                                    </div>

                                </div>



                                <?php
                                if($number_prerequisite_courses > 0){

                                    echo "<div class = \"col-sm-5\"></div>
                                    <div class = \"col-sm-3\"><div class = \"begin-date\" style= \"text-align: left;\">
                                    <div class = \"begin-date\" style= \"text-align: left; font-weight: bold;\">
                                    Prerequistes:</div><ul>";
                                    while($row2 = mysqli_fetch_array($Prerequiste_courses)){
                                        echo "<li> $row2[0] $row2[1]</li>";
                                    }

                                    echo "</ul>";
                                }
                                else{
                                    echo "<div class = \"col-sm-4\"></div>
                                    <div class = \"col-sm-4\"><div class = \"begin-date\">
                                    <div class = \"begin-date\" style= \" font-weight: bold;\">
                                    No <br> Prerequistes <br> Required </div>";
                                }
                                echo "</div>";
                                echo "</div></div>";
                                echo "<div class = \"button-class\" >
                                <div class = \"fees\"> Fees:  Rs.$row[5]/- </div>
                                <button id = \"register\" onclick=\"myFunction(this)\" class=\"button button2\">Register</button></div> </div>";
                            }



                            $i++;
                        }
                        ?>
                    </div>


                </body>
