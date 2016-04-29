<!-- <!DOCTYPE html> -->
<?php
  include 'Connection.php';
  session_start();
  if(isset($_SESSION["uname"]))
  {
      if($_SESSION["role"]!="Admin")
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
  $admin=$_SESSION["uname"];
  $sql="SELECT name from Admin where uname='$admin'";
  $result=mysqli_query($link,$sql);
  $NAME=mysqli_fetch_array($result)[0];
  
  $sql='Select * from TeacherUnv';
  $result=mysqli_query($link,$sql);
  $uname=array();
  $pwd=array();
  $email=array();
  $contact=array();
  $name=array();
  $num_teacher_requests=mysqli_num_rows($result);
  while($row=mysqli_fetch_array($result))
  {
    $name[]=$row[0];
    $email[]=$row[1];
    $contact[]=$row[2];
    $uname[]=$row[3];
    $pwd[]=$row[4];
  }

  $sql="Select * from GuardianUnv";
  $result=mysqli_query($link,$sql);
  $sname=array();
  $pname=array();
  $num_child_requests=mysqli_num_rows($result);
  while($row=mysqli_fetch_array($result))
  {
    $sname[]=$row[0];
    $pname[]=$row[1];
  }
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $NAME;?></title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="admin.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
        <a class="navbar-brand" href="admin.php">Moodle</a>
      </div>
        <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">
        <!-- <div class="navbar-header">
          <a data-target="#myModal" data-toggle="modal" data-backdrop="static" class="navbar-brand" id="lolu" href="#myModal">Add Child</a>
        </div> -->
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $NAME;?>  <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="parent.php">Home</a></li>
            <li><a href="edit-profile.php">My Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
      </div>
    </nav>

      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-primary head-panel">
            <div class="panel-heading" style="height: 6%;">Faculty Approval</div>
          </div>

          <?php

            $i=0;

            if($i == $num_teacher_requests)
            {
              echo "
                  <h1 class=\"no-notify\"><small style=\"font-size: 180%\">No Pending Requests</small></h1>";
            }
            else
            {
              while($i < $num_teacher_requests)
              {
                echo "
                      <div class=\"panel panel-default parent-panel\" id = \"b".$i."\">
                        <div class=\"panel-body row\">
                            <div class=\"col-sm-6\" style=\"font-size:150%\">".$uname[$i]."
                            </div>
                            <div>
                              <button type=\"button\" id=\"td".$i."\" class=\"btn btn-success pull-right success-button\" onclick=\"teacher_decline(this)\" >Decline</button>
                            </div>
                            <div>
                              <button type=\"button\" id=\"ta".$i."\" class=\"btn btn-success pull-right success-button\" onclick=\"teacher_approve(this)\" >Approve</button>
                            </div> 
                        </div>
                      </div>
                ";
                $i++;
              }
            }
          ?>

        </div>

        <div class="col-sm-6">
          <div class="panel panel-primary head-panel">
            <div class="panel-heading" style="height: 6%">Parent Approval</div>
          </div>

        <?php

            $i=0;

            if($i == $num_child_requests)
            {
              echo "
                  <h1 class=\"no-notify\"><small style=\"font-size: 180%\">No Pending Requests</small></h1>";
            }
            else
            {
              while($i < $num_child_requests)
              {
              echo "
                 <div class=\"panel panel-default parent-panel\" id = \"b".$i."\">
                   <div class=\"panel-body row\">
                       <div class=\"col-sm-6\" style=\"font-size:150%\">
                         Is <strong>".$pname[$i]."</strong> parent of <strong>".$sname[$i]."</strong>?
                       </div> 
                       <div>
                         <button type=\"button\" id=\"pd".$i."\" class=\"btn btn-success pull-right success-button\" onclick=\"parent_decline(this)\" >Decline</button>
                       </div>
                       <div>
                         <button type=\"button\" id=\"pa".$i."\" class=\"btn btn-success pull-right success-button\" onclick=\"parent_approve(this)\" >Approve</button>
                       </div> 
                   </div>
                 </div>
                ";
                $i++;
              }
            }
          ?>

        </div>
      </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="bootstrap/docs/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script>
        function create_func()
        {
            location.href="course-create.php";
        }
        function teacher_approve(element)
        {
            var index=element.id;
            index=index.substring(2,index.length);
            var uname=<?php echo json_encode($uname) ; ?>[index];
            var name=<?php echo json_encode($name) ; ?>[index];
            var email=<?php echo json_encode($email) ; ?>[index];
            var pwd=<?php echo json_encode($pwd) ; ?>[index];
            var contact=<?php echo json_encode($contact) ; ?>[index];
            var num=<?php echo json_encode($num_teacher_requests); ?>[0];
            var id="#b"+index;
            console.log(id);
            $.ajax(
            {
              url: "login_verify_faculty.php",
              type:"post",
              dataType:"json",
              data:
              {
                name:name,
                email:email,
                  uname:uname,
                  contact:contact,
                  pwd:pwd,
                  role:"Teacher"
              },

              success: function(json)
              {
                  console.log(json.status);
                  if(json.status==1)
                  {
                   
                    location.reload();
                  }
              },

              error : function()
              {
                alert("ERROR");
                //console.log("something went wrong");
              }
            });
        }
        function teacher_decline(element)
        {
            var index=element.id;
            index=index.substring(2,index.length);
            var uname=<?php echo json_encode($uname) ; ?>[index];
            var name=<?php echo json_encode($name) ; ?>[index];
            var email=<?php echo json_encode($email) ; ?>[index];
            var pwd=<?php echo json_encode($pwd) ; ?>[index];
            var contact=<?php echo json_encode($contact) ; ?>[index];
            var num=<?php echo json_encode($num_teacher_requests); ?>[0];
            var id="#b"+index;
            console.log(id);
            $.ajax(
            {
              url: "login_unverify_faculty.php",
              type:"post",
              dataType:"json",
              data:
              {
                name:name,
                email:email,
                  uname:uname,
                  contact:contact,
                  pwd:pwd,
                  role:"Teacher"
              },

              success: function(json)
              {
                  console.log(json.status);
                  if(json.status==1)
                  {
                   
                    location.reload();
                  }
              },

              error : function()
              {
                alert("ERROR");
                //console.log("something went wrong");
              }
            });
        }
        function parent_approve(element)
        {
            var index=element.id;
            index=index.substring(2,index.length);
            //console.log(index);
            var sname=<?php echo json_encode($sname) ; ?>[index];
            var pname=<?php echo json_encode($pname) ; ?>[index];
           
             $.ajax(
            {
              url: "parent_verify_child.php",
              type:"post",
              dataType:"json",
              data:
              {
                sname:sname,
                pname:pname
              },

              success: function(json)
              {
                  console.log(json.status);
                  if(json.status==1)
                  {
                   
                    location.reload();
                  }
              },

              error : function()
              {
                alert("ERROR");
                //console.log("something went wrong");
              }
            });

        }
         function parent_decline(element)
        {
            var index=element.id;
            index=index.substring(2,index.length);
            //console.log(index);
            var sname=<?php echo json_encode($sname) ; ?>[index];
            var pname=<?php echo json_encode($pname) ; ?>[index];
           
             $.ajax(
            {
              url: "parent_unverify_child.php",
              type:"post",
              dataType:"json",
              data:
              {
                sname:sname,
                pname:pname
              },

              success: function(json)
              {
                  console.log(json.status);
                  if(json.status==1)
                  {
                   
                    location.reload();
                  }
              },

              error : function()
              {
                alert("ERROR");
                //console.log("something went wrong");
              }
            });

        }
    </script>
    <script src="bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
