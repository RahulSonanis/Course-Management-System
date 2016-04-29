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
  $check="Select uname from Teaches where cID=$cid";
  $result=mysqli_query($link,$check);
  $name=mysqli_fetch_array($result)[0];
  if($name!=$user_name)
  {
      header("Location:faculty.php");
  }
  //echo $cid;
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
        <li><a href="faculty-student.php?<?php echo "cid=".$cid;?>">Students</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">

        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><?php echo $NAME;
      ?><span class="caret"></span></a>
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




<p id="demo"></p>
     <?php
      $i=0;

      while($i<$weeks)
      {
        $sql="Select cID,info,link from Notes where cID=$cid and week=".($i+1);
        //echo $sql;
        $result=mysqli_query($link,$sql);
        $num=mysqli_num_rows($result);
        echo "<div id =\"akak".$i."\" class = \"course-content\">
          <div class = \"course-id-name\">
              Week ".($i+1)."
          </div>";
          echo "<div font-size=\"10pt\" style=\"margin-left:3%;\">
          <h4><u><b>Notes</u></b></h4>
          </div>  ";
        while($row=mysqli_fetch_array($result))
        {
            $rev=strrev($row[2]);
            $tok=strtok($rev,"/");
            $fin=strrev($tok);
            echo "
          <div class=\"week-content\" style=\"margin-left:6%;\">
          <p>".$row[1]."</p>
          <a style=\"margin-left:3%;margin-top:-1px;margin-bottom:10%;\" href=\"".$row[2]."\" target=\"_blank\"><span class=\"glyphicon glyphicon-file\">".$fin."</span></a>
          </div>";

        }
//         echo "<button class=\"button button2\" data-toggle=\"modal\" data-target=\"#myModal".$i."\" id=\" ".($i+1)."\">Upload</button>

//           <div class=\"modal fade\" id=\"myModal".$i."\" tabindex=\"-1\" role=\"dialog\"
//      aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
//     <div class=\"modal-dialog\">
//         <div class=\"modal-content\">
//             <!-- Modal Header -->
//             <div class=\"modal-header\">
//                 <button type=\"button\" class=\"close\"
//                    data-dismiss=\"modal\">
//                        <span aria-hidden=\"true\">&times;</span>
//                        <span class=\"sr-only\">Close</span>
//                 </button>
//                 <h4 class=\"modal-title\" id=\"myModalLabel".$i."\">
//                     Week ".($i+1)."
//                 </h4>
//             </div>

//             <!-- Modal Body -->
//             <div class=\"modal-body\">

//                 <form action=\"notes-upload.php\" class=\"form-horizontal\" method=\"POST\" enctype=\"multipart/form-data\" role=\"form\">
//                   <div class=\"form-group\">
//                     <select id=\"content-type".$i."\" style=\"margin-left:2%; width:auto;\" class=\"form-control\" name=\"content-type\">
//                       <option>Notes</option>
//                       <option>Assignment</option>
//                     </select>

//                     <label  class=\"col-sm-2 control-label\"
//                               for=\"info\">Info</label>
//                     <div class=\"col-sm-10\">
//                         <textarea rows=\"3\" columns=\"50\" style=\"resize:none;\" name=\"info\" class=\"form-control\"
//                         id=\"info".$i."\" placeholder=\"Add info related to files\"></textarea>
//                         <input type=\"text\" name=\"week\" value=\"".($i+1)."\" hidden>
//                         <input type=\"text\" name=\"cid\" value=\"".$cid."\" hidden >
//                     </div>
//                   </div>
//                   <div class=\"form-group\">

//                     <div class=\"col-sm-10\">
//                       <label for=\"uploaded_file\">Select A File To Upload:</label>
//                       <input type=\"file\" name=\"uploaded_file\" id=\"uploaded_file".$i."\">
//                     </div>
//                   </div>

//                   <div class=\"form-group\">
//                     <div class=\"col-sm-offset-2\">
//                       <input type=\"submit\" class=\"btn btn-primary\" value=\"Upload\">
//                     </div>
//                   </div>
//                 </form>
//             </div>

//             <!-- Modal Footer -->
//             <div class=\"modal-footer\">
//                 <button type=\"button\" class=\"btn btn-default\"
//                         data-dismiss=\"modal\">
//                             Close
//                 </button>
//             </div>
//         </div>
//     </div>
// </div>
//         </div> ";
        $sql="Select cID,info,qlink,alink from Quiz where cID=$cid and week=".($i+1);
        //echo $sql;
        $result=mysqli_query($link,$sql);
        $num=mysqli_num_rows($result);
        // echo "<div id =\"akak".$i."\" class = \"course-content\">
        //   <div class = \"course-id-name\">
        //       Week ".($i+1)."
        //   </div>";
          echo "<div font-size=\"10pt\" style=\"margin-left:3%;\">
          <h4><u><b>Quiz</u></b></h4>
          </div>  ";
        while($row=mysqli_fetch_array($result))
        {
            $rev=strrev($row[2]);
            $tok=strtok($rev,"/");
            $fin=strrev($tok);
            $r=strrev($row[3]);
            $t=strtok($r,"/");
            $f=strrev($t);
            echo "
          <div class=\"week-content\" style=\"margin-left:6%;\">
          <p>".$row[1]."</p>
          <a style=\"margin-left:3%;\" href=\"".$row[2]."\" target=\"_blank\"><span class=\"glyphicon glyphicon-file\">".$fin."</span></a>
          <br>
          <a style=\"margin-left:3%;\" href=\"".$row[3]."\" target=\"_blank\"><span class=\"glyphicon glyphicon-file\">".$f."</span></a>
          </div>";

        }
        echo "<button class=\"button button2\" data-toggle=\"modal\" data-target=\"#myModal".$i."\" id=\" ".($i+1)."\">Upload</button>

          <div class=\"modal fade\" id=\"myModal".$i."\" tabindex=\"-1\" role=\"dialog\"
     aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <!-- Modal Header -->
            <div class=\"modal-header\">
                <button type=\"button\" class=\"close\"
                   data-dismiss=\"modal\">
                       <span aria-hidden=\"true\">&times;</span>
                       <span class=\"sr-only\">Close</span>
                </button>
                <h4 class=\"modal-title\" id=\"myModalLabel".$i."\">
                    Week ".($i+1)."
                </h4>
            </div>

            <!-- Modal Body -->
            <div class=\"modal-body\">

                <form action=\"notes-upload.php\" class=\"form-horizontal\" method=\"POST\" enctype=\"multipart/form-data\" role=\"form\">
                  <div class=\"form-group\">
                  <select id=\"mySelect".$i."\" onchange=\"myFunction(".$i.")\" style=\"margin-left:2%; width:auto;\" class=\"form-control\" name=\"content-type\">
                  <option value=\"Notes\">Notes
                  <option value=\"Assignment\">Assignment
                  </select>


                    <label  class=\"col-sm-2 control-label\"
                              for=\"info\">Info</label>
                    <div class=\"col-sm-10\">
                        <textarea rows=\"3\" columns=\"50\" style=\"resize:none;\" name=\"info\" class=\"form-control\"
                        id=\"info".$i."\" placeholder=\"Add info related to files\"></textarea>
                        <input type=\"text\" name=\"week\" value=\"".($i+1)."\" hidden>
                        <input type=\"text\" name=\"cid\" value=\"".$cid."\" hidden >
                    </div>
                  </div>
                  <div class=\"form-group\">

                    <div class=\"col-sm-10\">
                      <label for=\"uploaded_file\" id=\"ques-lab".$i."\">Select A File To Upload:</label>
                      <input type=\"file\" name=\"uploaded_file\" id=\"uploaded_file".$i."\" required>
                      <div id=\"ans".$i."\" hidden>
                      <label for=\"uploaded_ans\">Select A Answer File To Upload:</label>
                      <input type=\"file\" name=\"uploaded_ans\" id=\"uploaded_ans".$i."\">
                      </div>
                    </div>
                  </div>

                  <div class=\"form-group\">
                    <div class=\"col-sm-offset-2\">
                      <input type=\"submit\" class=\"btn btn-primary\" value=\"Upload\">

                    </div>
                  </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default\"
                        data-dismiss=\"modal\">
                            Close
                </button>
            </div>
        </div>
    </div>
</div>
        </div> ";
        $i=$i+1;
      }
      ?>
    </div>
    <div class="col-sm-2"></div>


    </div>
    <script>
      function myFunction(i) {
       // alert(i);
        var x = document.getElementById("mySelect"+i).value;
        if(x=="Assignment")
        {
          document.getElementById("ans"+i).hidden=false;
          document.getElementById("ques-lab"+i).innerHTML="Select a question file to upload";
          document.getElementById("uploaded_ans"+i).required=true;
        }
        if(x=="Notes")
        {
          document.getElementById("ans"+i).hidden=true;
          document.getElementById("ques-lab"+i).innerHTML="Select a Notes file to upload";
          document.getElementById("uploaded_ans"+i).required=false;
        }
    }

</script>
</body>
</html>
