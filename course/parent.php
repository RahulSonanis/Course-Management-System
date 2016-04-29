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
      if($_SESSION["role"]!="Parent")
      {
          //alert("You are not allowed here!!!!");
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


  $pname=$_SESSION["uname"];
  $link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
  $uname=$_SESSION['uname'];
  $sql="Select name from Parent where uname='$uname'";
  $result=mysqli_query($link,$sql);
  $NAME=mysqli_fetch_array($result)[0];
  $sql="Select sname from Guardian where pname='$uname'";
  $result=mysqli_query($link,$sql);
  $ar=array();
  $SNAME=array();

  while($row=mysqli_fetch_array($result))
  {
    $ar[]=$row[0];
    $sql="Select name from Student where uname='$row[0]'";
    $res=mysqli_query($link,$sql);
    $SNAME[]=mysqli_fetch_array($res)[0];
  }
  // echo "LOL";
?>
<html lang="en">
  <head>
      <link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords"
          content="Ajax Live Search, Autocomplete, Auto Suggest, PHP, HTML, CSS, jQuery, JavaScript, search form, MySQL, web component, responsive">

    <title><?php echo $NAME; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="bootstrap/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="parent.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>
    <script type="text/javascript" src="parent.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Live Search Styles -->
    <link rel="stylesheet" href="css/fontello.css">
    <link rel="stylesheet" href="css/animation.css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/fontello-ie7.css">
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="css/ajaxlivesearch.min.css">
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
        <a class="navbar-brand" href="admin.php">Moodle</a>
      </div>
        <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">
        <div class="navbar-header">
          <a data-target="#myModal" data-toggle="modal" data-backdrop="static" class="navbar-brand" id="lolu" href="#myModal">Add Child</a>
          <!-- <a class="navbar-brand" href = "#myModal">Add Child</a> -->
        </div>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $NAME?>  <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="parent.php">Home</a></li>
            <li><a href="edit-profile.php">My Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
      </div>
    </nav>

    <div class="container">
  <h2 style="text-align:center;">Childs</h2>
  <div class="panel-group" id="accordion">
  <?php
  $i=0;
   foreach($ar as $cname){
    echo"
    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h4 class=\"panel-title\">
          <a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse".$i."\">".$SNAME[$i]."</a>
        </h4>
      </div>
      <div id=\"collapse".$i."\" class=\"panel-collapse collapse \">
        <div class=\"panel-body\">";


      $sql="Select cID from Registered where uname='$cname'";
      $result=mysqli_query($link,$sql);
      $cid=array();
      //echo $sql."<br>";
      while($row=mysqli_fetch_array($result))
      {
        $cid[]=$row[0];
        //echo $row[0];
      }
      echo "
       <table class=\"table\">
    <thead>
      <tr>
        <th>Course Name</th>
        <th>week</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
       ";
        $j=0;
    while($j<count($cid))
    {
      //echo "CID=".$cid[$j]."<br>";
      $sql="Select cName,no_of_weeks from Course where cID=$cid[$j]";
      //echo $sql."<br>";
      $result=mysqli_query($link,$sql);
      $row=mysqli_fetch_array($result);
      $course_name=$row[0];
      $total_weeks=$row[1];
      //echo "HII".$course_name." ".$total_weeks."<br>";
      $sql="Select week from Registered where cID='$cid[$j]' and uname='$cname'";
       //echo $sql." <br>";
       $result=mysqli_query($link,$sql);
       $current_week=mysqli_fetch_array($result)[0];
       //echo "LOL".$current_week;
       $prog=floor((($current_week-1)*100)/$total_weeks);
    //   echo $prog;
      echo "<tr class=\"success clicker\" id=\"course".$i.$j."\">
              <td>".$course_name."</td>
              <td><a href=\"#\" id=\"grade".$i.$j."\" class=\"toggler\" data-prod-cat=\"".$i.$j."\">+View Grades</td>
              <td>
                <div class=\"progress\">
                  <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"".$prog."\"
                    aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$prog."%\">
                  ".$prog."% Complete (success)
                  </div>
                </div>
              </td>";
        $k=1;
        $sql="Select total_marks,obt_marks from Evaluation where cID='$cid[$j]' and uname='$cname'";
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
        while($k<$current_week){
          echo "<tr class=\"success cat".$i.$j."\" style=\"display:none;\">
              <td></td>
              <td>Week ".$k."</td>
              <td>".$obt_marks[$k-1]."/".$total_marks[$k-1]."</td>";
              $k=$k+1;
        }
        $j=$j+1;
    }
        echo "</tbody></table></div>
      </div>
    </div>";
    $i=$i+1;
  }
    ?>

  </div>
</div>

      </div>

<!--       <div class="col-sm-5 heading" >
        <div class="panel panel-default">
          <div class="panel-body">
            Add new Child
          </div>
        </div>

        <div class="child-name">
           <input type = "text" class = "form-control input-lg" id = "childname" placeholder = "Enter Your Child UserName">
        </div>

        <div class="button1">
          <button type="button" class="btn btn-default col-sm-6" id="add-button">Add</button>
        </div>

      </div> -->
    </div>


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 align ="center" style="font-weight: bolder;" class="modal-title" id="modal-title">Add Child</h3>
          </div>

          <form method="POST" enctype="multipart/form-data" role="form">
            <div class="modal-body">

                <div class="child-name">
                    <h4> Enter your child's name: </h4>
                    <!-- <input type="text" class='mySearch' id="ls_query"> -->
                    <!-- <input type = "text" name="child" class = "form-control input-lg" id = "childname" placeholder = "Enter Your Child UserName"> -->
                    <!-- <input type = "text" name="child mySearch" class = "form-control input-lg" id="ls_query" placeholder = "Child's Name"> -->
                    <div style="clear: both">
                        <input type="text" class='mySearch' id="ls_query">
                    </div>
                 </div>
            </div>

            <div class="modal-footer">
              <div class="row">
                <div class="form-group col-sm-2">
                  <button onclick="posting()" type="submit" class="btn btn-primary" id="add-button">Add</button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary close-button" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
    <!-- <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog"> -->

        <!-- Modal content-->
        <!-- <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="modal-title">Enter Child UserName</h4>
          </div>

          <form   class="form-horizontal" method="POST" enctype="multipart/form-data" role="form">
            <div class="modal-body"> -->

<!--               <form action="addchild.php" class="form-horizontal" method="POST" enctype="multipart/form-data" role="form">
 -->
                <!-- <div class="form-group"> -->
                 <!-- <div class="child-name"> -->
                    <!-- <input type = "text" name="child" class = "form-control input-lg" id = "childname" placeholder = "Enter Your Child UserName"> -->
                    <!-- <input type="text" name = "child" class="form-control input-lg mySearch" id="ls_query"> -->
                 <!-- </div> -->
                 <!-- <button type="button" onclick="inputs()"> lol</button> -->
                 <!-- <div style="clear: both">
                     <input type="text" name = "child" class="form-control input-lg mySearch" id="ls_query">
                 </div>
                </div> -->
                <!-- </form> -->
            <!-- </div>

            <div class="modal-footer">
              <div class="row">
                <div class="form-group col-sm-2">
                  <button type="submit" onclick="posting()" class="btn btn-primary" id="add-button">Add</button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary close-button" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div> -->

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
    </script>
    <script src="bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/jquery-1.11.1.min.js"></script>

    <!-- Live Search Script -->
    <script type="text/javascript" src="js/ajaxlivesearch.js"></script>

    <script src="js/jquery-1.11.1.min.js"></script>

    <!-- Live Search Script -->
    <script type="text/javascript" src="js/ajaxlivesearch.js"></script>
    <script>
    jQuery(document).ready(function(){
        console.log("I am here");
        jQuery(".mySearch").ajaxlivesearch({
            // console.log("I am here");
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
    <script type="text/javascript">
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
    // function inputs()
    // {
    // var    dis = document.getElementById('ls_query');
    // var val=dis.value;
    // console.log(val);
    // }
    function posting()
    {
        var    dis = document.getElementById('ls_query');
        var val=dis.value;
        console.log(val);

    		$.ajax(
    	      {
    	        url: "addchild.php",
    	        type:"post",
    	        dataType:"json",
    	        data:
    	        {
    	        	child: val
    	        },

    	        success: function(json)
    	        {
    	            // alert("SUCCESS");
    	            // alert(json.status);
    	            if(json.status == 1)
    	            {
    	                   location.reload();
    	            }
    	            // else if(json.status==0)
    	            // {
    	            // 	document.getElementById("wrong-user").hidden = false;
    				// 	document.getElementById("wrong-user").innerHTML="Wrong username or password";
    				// 	document.getElementById("username").value="";
    				// 	document.getElementById("inputPassword").value="";
    	            // }
    	            // else if(json.status==3)
    	            // {
    	            // 	document.getElementById("wrong-user").hidden = false;
    				// 	document.getElementById("wrong-user").innerHTML="Wrong username or password or not verified yet";
    				// 	document.getElementById("username").value="";
    				// 	document.getElementById("inputPassword").value="";
    	            // }

    	        },

    	        error : function()
    	        {
    	          alert("ERROR");
    	          //console.log("something went wrong");
    	        }
    	      });
    }

</script>
  </body>
</html>
