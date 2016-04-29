<!DOCTYPE html>
<?php
  include 'Connection.php';
  session_start();
  if(isset($_SESSION["uname"]))
  {

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
  $role=$_SESSION['role'];
  if($role=="Student")
    $home="student.php";
  else if($role=="Teacher")
    $home="faculty.php";
  else if($role=="Parent")
    $home="parent.php";
  else
    $home="admin.php";
  $uname=$_SESSION['uname'];
  $sql="Select * from ".$role." where uname='$uname'";
  $result=mysqli_query($link,$sql);
  $details=mysqli_fetch_array($result);
  $name=$details[0];
  $email=$details[1];
  $contact=$details[2];
  $pwd=$details[4];
 // echo "<br>HIIII".$name." ".$email." ".$contact." ".$pwd;
?>

<html lang="en">
<head>
  <title><?php echo $name; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="course-create.css">
</head>

<body>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo $home;?>">Moodle</a>
      </div>
      <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">

        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><?php echo $name; ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo $home;?>">Home</a></li>
            <li><a href="#">My Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <div  class = "row">
  <div class="col-sm-4">
  </div>
  <div class="container col-sm-4" >
      <!-- <form   class="form-signin"  id="account1"> -->
      <!-- <div class="form-group"> -->
      <div class = "edit-details">
          <h2 class="form-signin-heading" style="text-align:center; margin-top:5%">Edit the details</h2>
          <div class="row" style="margin-top:50px;">
            <div class = "col-sm-2">
            <label for="cname" class=" control-label" >Name</label>
          </div>
          <div class="col-sm-10">
            <input type="text" id="cname" name="cName" class="form-control" value="<?php echo $name;?>" required autofocus>
          </div></div>
          <div class="row" style="margin-top:5px;">
            <div class = "col-sm-2">
            <label for="email" class=" control-label">Email</label>
            </div>
            <div class="col-sm-10">
            <input type="text" id="email" name="email" class="form-control" value="<?php echo $email;?>" required>
          </div></div>
          <div>
            <p class="wrong-input" id="wrong-new-email" hidden style="text-align:center"></p>
          </div>
          <div class="row" style="margin-top:5px;">
            <div class = "col-sm-2">
            <label for="contact" class="control-label">Contact</label>
            </div>
            <div class="col-sm-10">
            <input type="number" id="contact" name="contact" class="form-control" value="<?php echo $contact;?>" required>
        </div>  </div>
          <div class="row" style="margin-top:5px;">
            <div class = "col-sm-2">
            <label for="pass" class=" control-label">Password</label>
            </div>
            <div class="col-sm-10">
            <input type="password" id="pass" name="contact" class="form-control" value="<?php echo $pwd;?>" required>
        </div>  </div>
       		<div class="row">
         		<div class="col-sm-5"></div>
            <button align="center" style="width:20% ;margin-top:2%" class="btn btn-lg btn-primary btn-block col-sm-8" onclick="func()" >Update</button>
        </div>
      </div>
      <!-- </form> -->

    <!-- </div> -->
  </div>
  <div class="col-sm-4"></div>
</div>
    <script type="text/javascript">

      function func()
      {
        //alert("inside");
        // location.href="faculty.php";
         var home=<?php echo json_encode($home);?>;
          console.log(home);
             //  alert(home);

          var name=document.getElementById("cname").value;
          var pwd=document.getElementById("pass").value;
          var email=document.getElementById("email").value;
          var contact=document.getElementById("contact").value;

          var ev = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          var x= ev.test(email);
          var cv=/^\d+$/;
          var y=cv.test(contact);

          if(x == false)
          {
              alert("Invalid Email");
              document.getElementById("email").value="";
          }
          else
          {
              if(y == false)
              {
                  alert("Invalid Contact");
                  document.getElementById("contact").value="";
              }
              else
              {
                  $.ajax(
                  {
                    url: "edit.php",
                    type:"post",
                    dataType:"json",
                    data:
                    {
                          name:name,
                          pwd:pwd,
                          email:email,
                          contact:contact
                    },

                    success: function(json)
                    {
                        //alert(json.status);
                        if(json.status==1)
                        {
                         // alert("SUCCESS");
                          location.href="faculty.php";
                         // window.location.assign(home);
                        }
                        else if(json.status == 0)
                        {
                          document.getElementById("wrong-new-email").hidden = false;
                          document.getElementById("wrong-new-email").innerHTML="Email already taken";
                          document.getElementById("email").value="";
                        }


                    },

                    error : function()
                    {
                      alert("ERROR");
                      //console.log("something went wrong");
                    }
                  });
              }
          }

      }
    </script>
</body>
</html>
