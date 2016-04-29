<!DOCTYPE html>
<?php
include 'Connection.php';

file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Handler.php') ? require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Handler.php' : die('There is no such a file: Handler.php');
file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Config.php') ? require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Config.php' : die('There is no such a file: Config.php');

use AjaxLiveSearch\core\Config;
use AjaxLiveSearch\core\Handler;


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

Handler::getJavascriptAntiBot();
$token = Handler::getToken();
$time = time();
$maxInputLength = Config::getConfig('maxInputLength');

$link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
if (mysqli_connect_errno())
{

  echo "Failed to connect to MySQL: " . mysqli_connect_error();

}
$user_name = $_SESSION["uname"];
$query1 = "select R.cID,C.cName,F.name,R.week,C.no_of_weeks,C.Department from Registered as R, Teaches as T, Teacher as F, Course as C
where R.uname = '$user_name' and R.cID = T.cID and T.uname = F.uname and R.cID = C.cID and R.ongoing=1";
$running_courses=mysqli_query($link,$query1);
$xyz=mysqli_fetch_array($running_courses);
$running_courses=mysqli_query($link,$query1);

$query1 = "select R.cID,C.cName,F.name from Registered as R, Teaches as T, Teacher as F, Course as C
where R.uname = '$user_name' and R.cID = T.cID and T.uname = F.uname and R.cID = C.cID and R.ongoing=0";
$completed_courses=mysqli_query($link,$query1);

$query2 = "select name from Student where uname = '$user_name'";
$result2=mysqli_query($link,$query2);
$Student_name =mysqli_fetch_array($result2);
$sql="Select N.link, N.info, N.uploaded_date, N.cID, N.week from Notes as N, Registered as R
where R.uname='$user_name' and R.cID = N.cID and R.ongoing = 1";
$notes_news = mysqli_query($link, $sql);
$sql="Select N.info, N.uploaded_date, N.cID, N.week from Quiz as N, Registered as R
where R.uname='$user_name' and R.cID = N.cID and R.ongoing = 1";
$quiz_news = mysqli_query($link, $sql);


$sql="select cID,cName,start_date,syllabus from Course where start_date >all (select CURDATE());";
$upcoming_courses = mysqli_query($link, $sql);

$sql = "SELECT *
FROM   Messages AS M
natural JOIN (SELECT M2.sender,

  M2.receiver,
  Max(M2.send_date) AS send_date
  FROM   Messages AS M2
  WHERE  M2.sender = '$user_name'
  OR M2.receiver = '$user_name'
  GROUP  BY M2.sender,
  M2.receiver) AS N
  WHERE  NOT EXISTS (SELECT *
    FROM   ((SELECT *
      FROM   (SELECT M3.sender,
        M3.receiver,
        Max(M3.send_date) AS send_date
        FROM   Messages AS M3
        WHERE  M3.sender = '$user_name'
        GROUP  BY M3.sender,
        M3.receiver) AS send
        WHERE  EXISTS (SELECT *
          FROM   (SELECT M4.sender,
            M4.receiver,
            Max(M4.send_date) AS
            send_date
            FROM   Messages AS M4
            WHERE
            M4.receiver = '$user_name'
            GROUP  BY M4.sender,
            M4.receiver) AS rec
            WHERE  rec.send_date >
            send.send_date and send.receiver = rec.sender))
            UNION
            (SELECT *
              FROM   (SELECT M5.sender,
                M5.receiver,
                Max(M5.send_date) AS send_date
                FROM   Messages AS M5
                WHERE  M5.receiver = '$user_name'
                GROUP  BY M5.sender,
                M5.receiver) AS rec1
                WHERE  EXISTS (SELECT *
                  FROM   (SELECT M6.sender,
                    M6.receiver,
                    Max(M6.send_date) AS
                    send_date
                    FROM   Messages AS M6
                    WHERE  M6.sender = '$user_name'
                    GROUP  BY M6.sender,
                    M6.receiver) AS
                    send1
                    WHERE  send1.send_date >=
                    rec1.send_date and rec1.sender = send1.receiver)))
                    AS Q
                    WHERE  M.sender = Q.sender
                    AND M.receiver = Q.receiver
                    AND M.send_date = Q.send_date) order by send_date desc ";
                    //echo "<br><br>".$sql;
                    $all_messages = mysqli_query($link, $sql);
                    $messages=array();
                    $full_convo=array();
                    $i=0;
                    while($row = mysqli_fetch_array($all_messages))
                    {
                      $messages[]=$row;
                      $full_convo[]=array();
                      //  $temp=array();
                      $sender=$row[0];
                      $recv=$row[1];
                      $sql="Select * from Messages where (sender='$sender' and receiver='$recv') or (sender='$recv' and receiver='$sender') order by send_date asc";
                      //  echo $sql."<br>";
                      $convo=mysqli_query($link,$sql);
                      $j=0;
                      while($x= mysqli_fetch_array($convo))
                      {
                        //$temp[]=$x;
                        $full_convo[$i][$j]=array();
                        $full_convo[$i][$j]=$x;
                        //echo $full_convo[$i][$j][0]." ".$full_convo[$i][$j][1];
                        //  echo $temp[0][0]."<br>".$x[0]."<br>";
                        $j=$j+1;
                      }

                      //    echo $full_convo[0][0][1]."<br>";
                      $i=$i+1;
                    }
                    //  echo sizeof($full_convo[0],0);
                    $sql = "select count(read_msg) from Messages where read_msg = 0 and receiver = '$user_name'";
                    $no_unread_messages = mysqli_fetch_array(mysqli_query($link,$sql))[0];


                    $n_n = array();

                    while($row = mysqli_fetch_array($notes_news)){
                      $sql = "select cName from Course where cID = $row[3]";
                      $coursName = mysqli_fetch_array(mysqli_query($link,$sql));
                      $n_n[] = array("title"=> $coursName[0], "url"=>$row[0], "start"=>$row[2], "tip"=>$coursName[0]." Week - ".$row[3] .": ".$row[1]);
                    }

                    while($row = mysqli_fetch_array($quiz_news)){
                      $sql = "select cName from Course where cID = $row[3]";
                      $coursName = mysqli_fetch_array(mysqli_query($link,$sql));
                      $n_n[] = array("title"=> $coursName[0], "start"=>$row[2], "tip"=>$coursName[0]." Week - ".$row[3] .": ".$row[1]);
                    }

                    while($row= mysqli_fetch_array($upcoming_courses)){
                      $n_n[] = array("title"=> $row[1], "url"=>$row[3], "start"=>$row[2], "tip"=>$row[1]." starts");
                    }
                    ?>
                    <html lang="en">
                    <head>
                      <title>
                        <?php
                        echo $Student_name[0];
                        ?>
                      </title>

                      <link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
                      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                      <meta charset="utf-8">

                      <!-- Live Search Styles -->
                      <link rel="stylesheet" href="css/fontello.css">
                      <link rel="stylesheet" href="css/animation.css">
                      <!--[if IE 7]>
                      <link rel="stylesheet" href="css/fontello-ie7.css">
                      <![endif]-->
                      <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">


                      <meta name="viewport" content="width=device-width, initial-scale=1">
                      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
                      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
                      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.js"></script>



                      <link href='fullcalendar.css' rel='stylesheet' />
                      <link href='fullcalendar.print.css' rel='stylesheet' media='print' />
                      <script src='moment.min.js'></script>
                      <!-- <script src='jquery.min.js'></script> -->
                      <script src='fullcalendar.min.js'></script>
                      <link rel="stylesheet" href="student1.css">
                      <script type="text/javascript" src="jquery.slimScroll.min.js"></script>
                      <script>

                      $(document).ready(function() {

                        var events = <?php echo json_encode($n_n); ?>;
                        //console.log(events.length);
                        $('#calendar').fullCalendar({
                          header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,basicWeek,basicDay'
                          },
                          defaultDate:Date.now(),
                          editable: true,
                          eventLimit: true, // allow "more" link when too many events
                          events,
                          eventRender: function(event, element) {
                            element.attr('title', event.tip);
                          }

                        });

                      });
                      jQuery(document).ready(function($) {
                        $(".clickable-row").click(function() {
                          window.document.location = $(this).data("href");
                        });
                      });

                      (function () {
                        function checkTime(i) {
                          return (i < 10) ? "0" + i : i;
                        }

                        function startTime() {
                          var today = new Date(),
                          h = checkTime(today.getHours()),
                          m = checkTime(today.getMinutes()),
                          s = checkTime(today.getSeconds());
                          //  document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
                          t = setTimeout(function () {
                            startTime()
                          }, 500);
                        }
                        startTime();
                      })();
                      </script>
                      <style>

                      /*#calendar {
                      padding: 5%;
                      max-width: 60%;
                      margin: 0 auto;
                      font-size: 14px;
                      font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
                      border-color: black;
                      border-style: solid;
                      border-width: thin;
                      }*/

                      </style>
                    </head>




                    <body style="background-color:#f2f2f2;">

                      <nav class="navbar navbar-inverse">
                        <div class="container-fluid">
                          <div class="navbar-header">
                            <a class="navbar-brand" href="<?php echo $home;?>">Moodle</a>
                          </div>

                          <ul class="nav navbar-nav navbar-left" style = "padding-right: 1%">
                            <li class="navbar-header">
                              <a class="navbar-brand" href="AllCourses.php">All Courses</a>
                            </li>
                          </ul>
                          <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">

                            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
                              <span class="glyphicon glyphicon-envelope"></span></a>


                              <div class="dropdown-menu" style="width: 430px;">
                                <div class="chatlist-header">
                                  Unread Messages(<?php echo $no_unread_messages; ?>)
                                  <div style="float: right; font-weight: normal;"><a data-toggle="modal" href="#Modal">New Message</a></div>
                                </div>

                                <div id="chatlist" class="mousescroll">

                                  <!-- / .message -->
                                  <?php
                                  $i=0;
                                  while($i<count($messages)){
                                    echo "<hr style=\"margin:0px\">
                                    <a class=\"click-event\" id=\"".$i."\"  data-toggle=\"modal\" href=\"#scroll\" >";
                                    $flag=0;
                                    $j=sizeof($full_convo[$i])-1;

                                    while($j>=0)
                                    {
                                      //  echo $full_convo[$i][$j][0]." ".$user_name." ".strcmp($full_convo[$i][$j][0],$user_name)." ".$full_convo[$i][$j][3];
                                      if(strcmp($full_convo[$i][$j][0],$user_name)!=0 and $full_convo[$i][$j][3]==0)
                                      {
                                        $flag=1;
                                        break;
                                      }
                                      $j=$j-1;
                                    }
                                    if($flag==0){
                                      echo"<div class=\"message\">";
                                    }
                                    else{
                                      echo"<div class=\"message-unread\">";
                                    }

                                    echo "<div class=\"message-subject\">
                                    ".$messages[$i][6]."
                                    </div>

                                    <div class=\"message-description\">
                                    from &nbsp;
                                    <span class=\"sender-name\" style=\"float: none;\">".$messages[$i][5]."</span>
                                    &nbsp;·&nbsp;";
                                    echo $messages[$i][2];
                                    echo"</div>
                                    </div></a>";
                                    //  echo "<p>".$messages[$i][4];
                                    $i=$i+1;
                                  }

                                  ?>




                                </div>
                                <!-- <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 109.842px; background: rgb(0, 0, 0);"></div> -->
                                <!-- <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div> -->
                                <!-- </div> -->
                                <!-- / .messages-list -->
                              </div>
                            </li>
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
                      <?php
                      $total_modals=sizeof($full_convo,0);
                      // echo $total_modals;
                      $i=0;
                      while($i<$total_modals)
                      {
                        echo"  <div id=\"myModal".$i."\" class=\"modal fade\" role=\"dialog\">
                        <div class=\"modal-dialog\">

                        <!-- Modal content-->
                        <div class=\"modal-content\">
                        <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        <h4 class=\"modal-title\">Conversation</h4>
                        </div> <div class=\"modal-body\" id=\"scroll\"> ";
                        $total_elements=sizeof($full_convo[$i],0);
                        $j=0;
                        while($j<$total_elements)
                        {
                          echo"
                          <div class=\"sender-name2\" >
                          <p id=\"modal-message-name".$i.$j."\" ></p>
                          </div>

                          ";
                          $j=$j+1;
                        }
                        echo "     <div>
                        <textarea maxlength=\"1000\" rows=\"3\" columns=\"50\" style=\"resize:none;\" name=\"info\" class=\"form-control\" id=\"info".$i."\" placeholder=\"Reply\"></textarea>
                        </div>
                        <div>
                        <button align=\"center\" style=\"width:20% ;margin-top:2%\" class=\"btn btn-primary\" id=\"r".$i."\"onclick=\"func_reply(this)\" >Reply</button>
                        </div>
                        </div>   <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-default\" id=\"b".$i."\" onclick=\"modal_close(this)\" data-dismiss=\"modal\">Close</button>
                        </div>
                        </div>

                        </div>
                        </div>";
                        $i=$i+1;
                      }
                      ?>
                      <div id="Modal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">New Message</h4>
                            </div>
                            <div class="modal-body">
                              <div class="row" style="margin:5px;">
                                <label for="to" class=" control-label">To</label>
                                <!-- <input type="text" id="to" name="to" class="form-control" required> -->
                                <div id = "to" style="clear: both">
                                  <input type="text" class='mySearch' id="ls_query_2" placeholder="Search Faculty">
                                </div>
                              </div>
                              <div class="row" style="margin:5px;">
                                <label for="subject" class=" control-label">Subject</label>
                                <textarea maxlength="1000" rows="2" columns="50" style="resize:none;" name="info" class="form-control" id="subject" placeholder="Add Subject"></textarea>
                              </div>
                              <div class="row" style="margin:5px;">
                                <label for="new-message" class=" control-label">Message</label>
                                <textarea maxlength="2000" rows="4" columns="50" style="resize:none;" name="info" class="form-control" id="new-message" placeholder="Type Your Message"></textarea>
                              </div>
                              <div>
                                <button align="center" style="width:20%;margin-top:2%;" class="btn btn-primary" onclick="func_send()" >Send</button>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php
                      $sender_uname=$_SESSION['uname'];
                      $sql="Select name from Student where uname='$sender_uname'";
                      $result=mysqli_query($link,$sql);
                      $sender_name=mysqli_fetch_array($result)[0];
                      ?>
                      <script type="text/javascript" >
                      function  func_send()
                      {
                        var to=document.getElementById("ls_query_2").value;
                        var sub=document.getElementById("subject").value;
                        var msg=document.getElementById("new-message").value;
                        var sender=<?php echo json_encode($sender_uname); ?>;
                        var sender_name=<?php echo json_encode($sender_name); ?>;
                        $.ajax(
                          {
                            url: "add_newmsg.php",
                            type:"post",
                            dataType:"json",
                            data:
                            {
                              sender:sender,
                              recv:to,
                              msg:msg,
                              sub:sub,
                              sender_name:sender_name

                            },

                            success: function(json)
                            {
                              //alert("SUCCESS");
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
                        function  func_reply(element)
                        {
                          var id=element.id;
                          console.log(id);
                          id=id.substring(1,id.length);
                          console.log(id);
                          console.log("info"+id);
                          var body=document.getElementById("info"+id).value;
                          console.log(body);
                          var y=<?php echo json_encode($full_convo) ;?>;
                          var sender=<?php echo json_encode($user_name);?>;
                          var sender_name=<?php echo json_encode($Student_name[0]);?>;
                          var recv=y[id][0][0];
                          var sub=y[id][0][6];
                          console.log(sub);
                          if(sender==recv)
                          {
                            recv=y[id][0][1];
                          }
                          console.log(sender);
                          console.log(recv);
                          $.ajax(
                            {
                              url: "reply_newmsg.php",
                              type:"post",
                              dataType:"json",
                              data:
                              {
                                sender:sender,
                                recv:recv,
                                msg:body,
                                sub:sub,
                                sender_name:sender_name

                              },

                              success: function(json)
                              {
                                //alert("SUCCESS");
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
                        function modal_close(element)
                        {
                          var x=element.id;
                          x=x.substring(1,x.length);
                          console.log(x);
                          var y=<?php echo json_encode($full_convo) ;?>;
                          var recv=<?php echo json_encode($user_name);?>;
                          var sender=y[x][0][0];
                          if(sender==recv)
                          {
                            sender=y[x][0][1];
                          }
                          $.ajax(
                            {
                              url: "read.php",
                              type:"post",
                              dataType:"json",
                              data:
                              {
                                recv:recv,
                                sender:sender
                              },

                              success: function(json)
                              {
                                // alert("SUCCESS");
                                console.log(json.status);
                                if(json.status == 1)
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
                          <div class="welcome" align="center">
                          <p style="font-size:150%;color:#204056">Welcome Back!</p>
                          <?php

                              if(strcmp($xyz[5],"CS")==0)
                              {
                                  echo "<img src=\"cse.jpg\" alt=\"CSE\" height=\"100\" width=\"100\" class=\"img-circle\">";
                              }
                              else if(strcmp($xyz[5],"EE")==0)
                              {
                                  echo "<img src=\"elec.jpg\" alt=\"CSE\" height=\"100\" width=\"100\" class=\"img-circle\">";
                              }
                              else if(strcmp($xyz[5],"EC")==0)
                              {
                                  echo "<img src=\"ece.jpg\" alt=\"CSE\" height=\"100\" width=\"100\" class=\"img-circle\">";
                              }
                              else if(strcmp($xyz[5],"ME")==0)
                              {
                                  echo "<img src=\"mech.jpg\" alt=\"CSE\" height=\"100\" width=\"100\" class=\"img-circle\">";
                              }

                          ?>
                          <p style="font-size:175%;color:#204056"><?php echo $xyz[1]; ?></p>
                          <?php
                              $prog=floor(($xyz[3]-1)*100/$xyz[4]);
                           echo "<div class=\"progress success\" style=\"width:50%;\">
                                   <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"".$prog."\"
                                   aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$prog."%\">
                                   </div>
                                  </div>
                                  <p style=\"font-size:125%;color:#204056;margin-top:-1px\" >".$prog."%</p>
                                  <button id = \"$xyz[0]\" onclick=\"myFunction(this)\" class=\"button button2\">Continue</button>";
                          ?>
                          </div>

                          <div style="width:100%">
                          <div>
                          <ul class="nav nav-tabs" style="margin-left:20%;border-color:#b3b3b3;">
                            <li class="active take-all-space" style="width:20%;"><a href="#running" data-toggle="tab">
                                Running</a></li>
                            <li class="take-all-space" style="width:20%;"><a href="#completed" data-toggle="tab">Completed</a></li>
                            <li class="take-all-space" style="width:20%;"><a href="#calendar2" data-toggle="tab">Calendar</a></li>
                            <li class="take-all-space" style="width:20%;"><a href="#clock" data-toggle="tab">Clock</a></li>
                          </ul>
                          </div>
                          <div class="tab-content" style="background-color:#b3b3b3;width:100%;">
                              <div class="tab-pane active" id="running">
                                  <!-- <div class="row" style="background-color: white;"> -->
                                  <div style="padding:2%;margin-left:10%;margin-right:10%;">
                                      <table class="table" >

                                          <tbody >
                                              <?php
                                              while($row=mysqli_fetch_array($running_courses))
                                              {
                                                  $prog=floor(($row[3]-1)*100/$row[4]);
                                                  echo "
                                                  <tr class=\"clickable-row\" style=\"background-color:white;font-size:125%;font-family: Oxygen, sans-serif;\"  data-href=\"student-courses.php?cID=$row[0]\" id=\"course\">
                                                  <td> <a href = \"student-courses.php?cID=$row[0]\">".$row[1]."</a></td>
                                                  <td>".$row[2]."</td>
                                                  <td>
                                                  <div class=\"progress\">
                                                  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"".$prog."\"
                                                  aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$prog."%\">
                                                  ".$prog."% Complete (success)
                                                  </div>
                                                  </div>
                                                  </td>
                                                  </tr>";
                                              }
                                              ?>
                                          </tbody>
                                      </table>
                                      <!-- </div> -->
                                      <!-- <div class="row"> -->
                                  </div>
                              </div>
                              <div class="tab-pane" id="completed" style="background-color:#b3b3b3;">
                                  <div style="padding:2%;margin-left:10%;margin-right:10%;">
                                      <?php
                                      $i=0;
                                      while($row=mysqli_fetch_array($completed_courses))
                                      {
                                          $i=$i+1;
                                          echo "<p class=\"clickable-row\" data-href=\"student-courses.php?cID=$row[0]\" style=\"background-color:white;font-size:125%;margin-top:0.5%;margin-bottom:0.5%;height:10%;font-family: Oxygen, sans-serif;padding-top:1%;padding-bottom:1%;padding-left:2%\" >
                                          ".$row[1]."
                                          </p><hr>";
                                      }
                                      if($i==0)
                                          echo "<div style=\"font-size:125%;color:#ff3333;text-align:center;padding:2%\"><p >You have not completed any courses yet</p></div>";
                                      ?>
                                  </div>
                                  <!-- </div> -->
                          </div>
                          <div class="tab-pane" id="calendar2" style="background-color:#b3b3b3;">
                              <div style="padding:2%;margin-left:10%;margin-right:10%;margin-top:-1px">
                                  <div id='calendar' class = "calender"></div>
                              </div>
                              <!-- </div> -->
                          </div>
                          <div class="tab-pane" id="clock" style="background-color:#b3b3b3;">
                                <div  class="time" style="margin-top:-1px;">
                                  <div class="clock">
                                    Clock
                                  </div>
                                  <div style="float: left;">Server:</div>
                                  <div  style="text-align:right;" id = "clock_server"></div>


                                  <div style="float: left;">You:</div>
                                  <div  style="text-align:right;" id = "clock_client"></div>

                                </div>
                              <!-- </div> -->
                          </div>
                          </div>
                          </div>

                          </div>




                          <script>

                          var diff;
                          (function () {

                            function checkTime(i) {
                              return (i < 10) ? "0" + i : i;
                            }

                            function startTime() {
                              var client_clock = new Date(),
                              serv = client_clock.getTime() + diff,
                              ser = new Date(serv);

                              var time_clock_ser = ser+"";
                              time_clock_ser = time_clock_ser.substring(0, time_clock_ser.length - 14);
                              var time_clock_client = client_clock+"";
                              time_clock_client = time_clock_client.substring(0, time_clock_client.length - 14);
                              document.getElementById('clock_client').innerHTML = time_clock_client ;
                              document.getElementById('clock_server').innerHTML = time_clock_ser  ;
                              t = setTimeout(function () {
                                startTime()
                              }, 500);
                            }

                            var cc = new Date();
                            var sc = new Date(
                              <?php
                              echo time() * 1000
                              ?>
                            );
                            diff = sc.getTime()  - cc.getTime() ;
                            // alert(cc);
                            // alert(sc);
                            // alert(diff);
                            startTime();
                          })();


                          $(function(){

                            $('#chatlist').slimScroll({

                              height: '500px',
                              railVisible: true
                              // alwaysVisible: true

                            });

                          });


                          </script>
                          <script type="text/javascript">
                          $(".click-event").click( function()
                          {
                            //alert(this.id);
                            var x=this.id;
                            console.log(x);
                            var y= <?php echo json_encode($full_convo); ?>;
                            console.log((y[this.id]).length);
                            for(i=0;i<y[this.id].length;i++)
                            {

                              document.getElementById("modal-message-name"+x+i).innerHTML="<b>"+y[x][i][5]+"</b>: "+y[x][i][2];
                              //  document.getElementById("modal-message-body"+x+i).innerHTML=y[x][i][2];
                              //$("#myModal"+x+i).modal();
                            }
                            //alert(y[x][2]);
                            //document.getElementById("modal-message-name").innerHTML="Subject: "+y[x][6];
                            //document.getElementById("modal-message-body").innerHTML=y[x][2];
                            $("#myModal"+x).modal();



                            return false;
                          } );
                          </script>
                          <!-- Placed at the end of the document so the pages load faster -->
                          <!-- <script src="js/jquery-1.11.1.min.js"></script> -->

                          <!-- Live Search Script -->
                          <script type="text/javascript" src="js/ajaxlivesearch.js"></script>

                          <script>
                          jQuery(document).ready(function(){
                            jQuery(".mySearch").ajaxlivesearch({
                              loaded_at: <?php echo $time; ?>,
                              token: <?php echo "'" . $token . "'"; ?>,
                              maxInput: <?php echo $maxInputLength; ?>,
                              onResultClick: function(e, data) {
                                // get the index 1 (second column) value
                                var selectedOne = jQuery(data.selected).find('td').eq('1').text();

                                // set the input value
                                jQuery('.mySearch').val(selectedOne);

                                // hide the result
                                jQuery(".mySearch").trigger('ajaxlivesearch:hide_result');
                              },
                              onResultEnter: function(e, data) {
                                // do whatever you want
                                // jQuery(".mySearch").trigger('ajaxlivesearch:search', {query: 'test'});
                              },
                              onAjaxComplete: function(e, data) {

                              }
                            });
                          })
function myFunction(element) {
    alert("Asdfg");
    location.href="student-courses.php?cID=".concat(element.id);
}
</script>

                        </body>
                        </html>
