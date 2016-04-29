<!DOCTYPE html>
<?php
  include 'Connection.php';
  session_start();
  if(isset($_SESSION["uname"]))
  {
      if($_SESSION["role"]!="Teacher")
      {
       // echo "HII";
         // alert("You are not allowed here!!!!");
          header("Location:index.php");
      }

  }
  else
  {
    header("Location:index.php");
  }
    $user_name = $_SESSION["uname"];
  $link=mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("cannot connect");
  $sql="Select name from Teacher where uname='$user_name'";
//  echo $sql;
  $res=mysqli_query($link,$sql);
  $NAME=mysqli_fetch_array($res)[0];
  if(mysqli_connect_errno())
    echo "FAILED";
  $sql="Select cID,cName from Course";
  $ar=array();
  $result=mysqli_query($link,$sql);
  $num_rows=mysqli_num_rows($result);
  while($row=mysqli_fetch_array($result))
  {
    $ar[]=$row;
  }


?>
<html lang="en">
<head>
  <title><?php echo $NAME;?></title>
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
        <a class="navbar-brand" href="faculty.php">Moodle</a>
      </div>

      <ul class="nav navbar-nav navbar-right" style = "padding-right: 1%">

        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span><?php echo $NAME; ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="faculty.php">Home</a></li>
            <li><a href="edit-profile.php">My Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <div class="col-sm-2">
  </div>
  <div class="container col-sm-8" style="margin-left: 32%;">
      <form action="upload.php" method="post" enctype="multipart/form-data" class="form-signin" role="form" id="account">
      <div class="form-group">
          <h2 class="form-signin-heading" style="margin-left: 10%; margin-bottom: 5%">Enter the details</h2>
          <div class="row">
          <label for="coursename" class="col-sm-2 control-label" >Course Name</label>
          <input type="text" id="coursename" name="cName" class="form-control" placeholder="Course Name" required autofocus>
          </div>
          <div class="row">
          <label for="start_date" class="col-sm-2 control-label">Start Date</label>
          <input type="date" id="start_date" name="start_date" class="form-control" placeholder="Start Date" required>
          </div>
          <div class="row">
          <label for="number_of_weeks" class="col-sm-2 control-label">Number of Weeks</label>
          <input type="number" id="number_of_weeks" name="weeks" class="form-control" placeholder="Number of weeks" required>
          </div>
          <div class="row">
          <label for="fees" class="col-sm-2 control-label">fees</label>
          <input type="number" id="fees" class="form-control" name="fees" placeholder="fees" value="0" required>
          </div>

          <div class="row">
          		<label  class="col-sm-2 control-label">Pre Requisute</label>
              	<button type="button" style="background-color:#66ff66; font-color:black;" class="btn btn-default btn-sm col-sm-1" onClick="addInput()">
          			<span class="glyphicon glyphicon-plus"></span> Add
        		</button>
        		<button hidden id="remove" type="button" style="background-color:#ff3333; font-color:black;" class="btn btn-default btn-sm col-sm-1" onClick="removeInput()">
          			<span class="glyphicon glyphicon-minus"></span> Remove
        		</button>

        </div>
        	<div id="add-pre">
        	</div>
          <div class="row select" style="margin-top:10px;" >
            <label for="inputtype" class="col-sm-2 control-label">Department</label>
              <select name="dept" type="select" id="inputtype" class="form-control">
                <option>CS</option>
                <option>EE</option>
                <option>EC</option>
                <option>ME</option>
              </select>
          </div>
         <div class="row" style="margin-top:10px">
         <label for="fileToUpload" class="col-sm-2 control-label">Upload Syllabus</label>
       		 <input type="file" name="fileToUpload" id="fileToUpload">
       		 </div>
       		<div class="row">
       		<div class="col-sm-2"></div>
          <button align="center" style="width:20%" class="btn btn-lg btn-primary btn-block col-sm-8" type="submit">Create</button>
        </div>
      </form>

    </div>
    <script type="text/javascript">
      var counter = 1;
      var limit = 6;

      var ar = <?php echo json_encode($ar) ?>;
     	$("#remove").toggle();


      function addInput(){
           if (counter == limit)  {
                alert("You have reached the limit of adding " + counter + " inputs");
           }
           else {
           		if(counter==1)
           		{
           			$("#remove").toggle();
           		}
           		var row=document.createElement('div');
           		row.className="row";
           		row.id="create".concat("",counter);
           		var col=document.createElement('label');
           		col.className="col-sm-2 control-label"
           		col.innerHTML="Pre Requisite".concat(" ",counter);
           		row.appendChild(col);
                var newdiv = document.createElement('select');
                newdiv.className="form-control";
                newdiv.setAttribute("name","myInputs[]");
               for(var i=0;i<ar.length;i++)
                {
                  var newdiv1=document.createElement('option');
                  newdiv1.innerHTML=ar[i][0].concat(" ",ar[i][1]);
                // alert(ar[i][0].concat("_",ar[i][1]));
                  newdiv.appendChild(newdiv1);
                }
                row.appendChild(newdiv);
                document.getElementById('add-pre').appendChild(row);
                counter++;
           }
      }
      function removeInput(){
           if (counter == 1)  {
                alert("You have reached the limit of adding " + counter + " inputs");

           }
           else {
           		counter--;
           		var removeid="create".concat("",counter);
           		$("#"+removeid).remove();
           		if(counter==1)
           			$("#remove").toggle();

           }
      }
    </script>
</body>
</html>
