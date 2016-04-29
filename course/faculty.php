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
  Handler::getJavascriptAntiBot();
  $token = Handler::getToken();
  $time = time();
  $maxInputLength = Config::getConfig('maxInputLength');
  $link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
  $uname=$_SESSION['uname'];
  $sql="Select name from Teacher where uname='$uname'";
  $result=mysqli_query($link,$sql);
  $NAME=mysqli_fetch_array($result)[0];
  $sql="Select cid from Teaches where uname='$uname'";
  $result=mysqli_query($link,$sql);
  $cid=array();
  $course_name=array();
  $start_date=array();
  $department=array();
  $weeks=array();
  $num_rows=mysqli_num_rows($result);
  while($row=mysqli_fetch_array($result))
  {
    $cid[]=$row[0];
   // echo $row[0];
    $query="Select * from Course where cID=$row[0]";
     //echo $query;
    $ans=mysqli_query($link,$query);
    while($course_detail=mysqli_fetch_array($ans))
    {
        $course_name[]=$course_detail[1];
      $start_date[]=$course_detail[2];
      $department[]=$course_detail[3];
      $weeks[]=$course_detail[4];
    }
   // echo $ar[0];
  }
  $user_name=$uname;
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
    <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="student.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>
    <script type="text/javascript" src="jquery.slimScroll.min.js"></script>
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
          <a class="navbar-brand" href="faculty.php">Moodle</a>
        </div>
        <ul class="nav navbar-nav navbar-left" style = "padding-right: 3%">
          <li><a href="course-create.php">Create Course</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                            <span class="glyphicon glyphicon-envelope"></span></a>


                                                            <div class="dropdown-menu" style="width: 430px;">
                                                                <div class="chatlist-header">
                                                                    Unread Messages(<?php echo $no_unread_messages; ?>)
                                                                    <div style="float: right; font-weight: normal;"><a data-toggle="modal" href="#Modal2">New Message</a></div>
                                                                </div>

                                                                <div id="chatlist" class="mousescroll">

                                                                    <!-- / .message -->
                                                                    <?php
                                                                    $i=0;
                                                                    while($i<count($messages)){
                                                                        echo "<hr style=\"margin:0px\">
                                                                        <a class=\"click-event\" id=\"".$i."\"  data-toggle=\"modal\" href=\"#myModal\" >";
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
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $NAME; ?>  <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="faculty.php">Home</a></li>
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
                                                        </div> <div class=\"modal-body\"> ";
                                              $total_elements=sizeof($full_convo[$i],0);
                                              $j=0;
                                            //  echo $total_elements;
                                              while($j<$total_elements)
                                              {
                                                      echo"
                                                            <div class=\"sender-name2 \">
                                                                <p id=\"modal-message-name".$i.$j."\"></p>
                                                            </div>

                                                          ";
                                                        $j=$j+1;
                                                  }
                                                  echo "     <div>
                                                        <textarea maxlength=\"1000\" rows=\"3\" columns=\"50\" style=\"resize:none;\" name=\"info\" class=\"form-control\" id=\"info".$i."\" placeholder=\"Reply\"></textarea>
                                                    </div>
                                                    <div>
                                                        <button align=\"center\" style=\"width:20% ;margin-top:2%\" class=\"btn btn-primary\" id=\"r".$i."\" onclick=\"func_reply(this)\" >Reply</button>
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
                                            <div id="Modal2" class="modal fade" role="dialog">
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
                                                                  <input type="text" class='mySearch' id="ls_query_3" placeholder="Search Faculty">
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
    <div class="row all-course-header">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      </div>
      </div>
      <div class="container">
  <h2><span class="glyphicon glyphicon-th-list"></span> My Courses</h2>
  <div class="list-group" style="border-color:black;">
    <?php

    $i=0;

    foreach($course_name as $cname)
    {
      // echo " <div class=\"row\">
      //       <div class=\"col-sm-2\"></div>
      //       <div class=\"col-sm-8\">";
      //   echo "<a href=\"faculty-course.php?cid=".$cid[$i]."\" style = \"text-decoration: none;\">
      //     <div class=\"course-content\">
      //       <div class=\"course-id-name\">".$cid[$i]." ".$cname."</div><br>
      //     </div>
      //   </a></div></div>";
      $sql="Select count(uname) from Registered where cID=$cid[$i] group by cID";
      $result=mysqli_query($link,$sql);
      $num_rows=mysqli_num_rows($result);

      $row=mysqli_fetch_array($result);
      if($num_rows==0)
        $row[0]=0;

      echo " <a href=\"faculty-course.php?cid=".$cid[$i]."\" class=\"list-group-item\">
      <h4 class=\"list-group-item-heading\">".$cid[$i]." ".$cname."</h4>
      <p class=\"list-group-item-text\" style=\"margin-top:1%;margin-bottom:1%;margin-left:2%;\">Start Date: ".$start_date[$i]."</p>
      <p class=\"list-group-item-text\" style=\"margin-bottom:1%;margin-left:2%;\">Department: ".$department[$i]."</p>
      <p class=\"list-group-item-text\" style=\"margin-bottom:1%;margin-left:2%;\">Number of weeks: ".$weeks[$i]."</p>
      <p class=\"list-group-item-text\" style=\"margin-bottom:1%;margin-left:2%;\">Number of Students registered: ".$row[0]."</p>
    </a>";
    /*echo " <div class=\"container\">
    <div class=\"starter-template\">
    <h3>Created Courses</h3></div><div id=\"course\" class=\"course-block\">
        <p id=\"cname\" class=\"course-name\"><a href=\"course.php?cid=".$cid[$i]."\">".$cid[$i]." ".$cname."</a></p>
       <p id=\"tname\" class=\"creator\">Course Creator: </p>
         <p id=\"creator\" class=\"creator2\">creator name</p>
      </div>
</div>";*/
     // echo $cid[i];
    $i=$i+1;
    }
    ?>
    </div>
</div>
    </div>

    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="bootstrap/docs/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

    <script src="bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript" src="js/ajaxlivesearch.js"></script>
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

        document.getElementById("modal-message-name"+x+i).innerHTML=y[x][i][5]+": "+y[x][i][2];
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
    </script>
    <?php
    $sender_uname=$_SESSION['uname'];
    $sql="Select name from Teacher where uname='$sender_uname'";
    $result=mysqli_query($link,$sql);
    $sender_name=mysqli_fetch_array($result)[0];
    ?>
    <script type="text/javascript" >
    function  func_send()
    {
      var to=document.getElementById("ls_query_3").value;
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
        var sender_name=<?php echo json_encode($sender_name);?>;
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
        <script>
        $(function(){

          $('#chatlist').slimScroll({

            height: '500px',
            railVisible: true
            // alwaysVisible: true

          });

        });
        </script>
  </body>
</html>
