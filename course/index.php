<!DOCTYPE html>

<?php
include 'Connection.php';
session_start();
if(isset($_SESSION["uname"]))
{
    //echo "bhai ko lol";
    //echo $_SESSION["role"];
    if(strcmp($_SESSION["role"],"Student") == 0)
    {
        //echo "bhai ko";
        header("Location: student.php");
    }
    if($_SESSION["role"]==="Teacher")
    {

        header("Location:faculty.php");
    }
    if($_SESSION["role"]==="Parent")
    {
        //echo "bhai ko";
        header("Location:parent.php");
    }
    if($_SESSION["role"] === "Admin")
    {

        //alert("You are not allowed here!!!!");
        header("Location:admin.php");
    }
}
$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
$check="select * from Course";
$result=mysqli_query($link,$check);
$courses=mysqli_num_rows($result);
$check="Select * from Student";
$result=mysqli_query($link,$check);
$students=mysqli_num_rows($result);
$check="Select * from Teacher";
$result=mysqli_query($link,$check);
$teachers=mysqli_num_rows($result);
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
   <!-- <link rel="icon" href="./bootstrap-3.3.6/docs/favicon.ico"> -->

    <title>Moodle - Log In or Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="signin.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <link href="/bootstrap-3.3.6/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet"> -->

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
   <!-- <script src="/bootstrap-3.3.6/docs/assets/js/ie-emulation-modes-warning.js"></script> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>


  <body background="login.jpeg">
  <div class="row" stlye="margin-top:0%;">
    <div class="col-sm-1"></div>
      <div class = "col-sm-2 website-name">
         Welcome to Moodle
      </div>
  </div>

    <div class="row" style="text-align:center;margin-top:12%">
      <p style="font-size:300%;color:black;"> Learn Anywhere Anytime</p><br>
        <button style="margin-top:-1px;" type="button" class="btn xtn" data-toggle="modal" data-target="#myModal">Login | Register</button>
        </div>
      </div>


    <div class="container">
    <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-body">
      <form class="form-signin" id="account" visiblity="false">
        <ul class="nav nav-tabs">
          <li class="active take-all-space"><a href="#login" data-toggle="tab"><span class="glyphicon glyphicon-log-in"></span>
              Log In<i class="fa"></i></a></li>
          <li class="take-all-space"><a href="#signup" data-toggle="tab"><span class="glyphicon glyphicon-user"></span> Sign Up<i class="fa"></i></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="login">
            <h2 class="form-signin-heading">Please sign in</h2>
            <p class="wrong-input" id="wrong-user" hidden></p>
            <label for="username" class="sr-only">Username</label>
            <input type="username" id="username" class="form-control" placeholder="Username" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <div class="type">
              <label >
                <select type="type" id="inputtypel" class="form-control">
                  <option>Student</option>
                  <option>Teacher</option>
                  <option>Parent</option>
                  <option>Admin</option>
                </select>
              </label>
            </div>
            <!-- <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me"> Remember me
              </label>
            </div> -->
            <button class="btn btn-lg btn-primary btn-block" onclick="login_func()">Log in</button>
          </div>
          <div class="tab-pane" id="signup">
            <h2 class="form-signin-heading">Please sign Up</h2>

            <label for="entername" class="sr-only">Name</label>
            <input type="name" id="entername" class="form-control" placeholder="Name" required>
            <label for="enterusername" class="sr-only">Username</label>
            <input type="username" id="enterusername" class="form-control" placeholder="Username" required>
              <p class="wrong-input" id="wrong-new-user" hidden></p>
            <label for="enterEmail" class="sr-only">Email address</label>
            <input type="text" id="enterEmail" class="form-control" placeholder="Email address" required>
              <p class="wrong-input" id="wrong-new-email" hidden></p>
            <label for="enterPassword" class="sr-only">Password</label>
            <input type="password" id="enterPassword" class="form-control" placeholder="Password" required>
            <label for="enterContact" class="sr-only">Password</label>
            <input type="text" id="enterContact" class="form-control" placeholder="Contact Number" required>
            <div class="type">
              <label >
                <select type="type" id="inputtypes" class="form-control">
                  <option>Student</option>
                  <option>Teacher</option>
                  <option>Parent</option>
                  <option>Admin</option>
                </select>
              </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block pull-left" type="submit" onclick="signup_func()">Sign Up</button>
          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>
</div>

    </div>
    <div class="row" style="margin-top:10%">
      <div class="col-sm-1">
      </div>
      <div class="col-sm-2">
        <div class="row numbers">
          <?php echo $courses; ?>
        </div>
        <hr style="margin-top:-1px;margin-bottom:-1px;border-top:3px solid #567702">
        <div class="row number_type" >
        Courses
        </div>
      </div>
      <div class="col-sm-2">
      </div>
      <div class="col-sm-2">
        <div class="row numbers">
          <?php echo $students;?>
        </div>
        <hr style="margin-top:-1px;margin-bottom:-1px;border-top:3px solid #567702">
        <div class="row number_type">
        Students
        </div>
      </div>
      <div class="col-sm-2">
      </div>
      <div class="col-sm-2">
        <div class="row numbers">
          <?php echo $teachers; ?>
        </div>
        <hr style="margin-top:-1px;margin-bottom:-1px;border-top:3px solid #567702">
        <div class="row number_type" >
        Teachers
        </div>
      </div>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
   <!-- <script src="/bootstrap-3.3.6/docs/assets/js/ie10-viewport-bug-workaround.js"></script> -->
  </body>
</html>
