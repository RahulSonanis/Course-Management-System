<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>TEST</title>

  	<link href="test.css" rel="stylesheet">

  	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
	integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
	integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="test.js"></script>
	<script src="jquery.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

    <!-- Include all compiled plugins (below), or include individual files as needed -->

  </head>
  <body>

  <div class="row">
  	<div class="panel panel-default col-sm-8 heading">
	  <div class="panel-body">
	    CLASS TEST
	  </div>
	</div>
  </div>

<!--   <div class="row">
	<div class="panel panel-default col-sm-8 " style=" text-align:center; font-weight:bold; font-size:100%; margin-left: 15%;">
	  <div class="panel-heading">Question 1</div>
	  <div class="panel-body" style="text-align:left">
	    <p>Question</p>
	  </div>

	  <ul class="list-group" style="text-align:left">
	  	<li class="list-group-item">
	  		<div class="radio ">
	  			<input type="radio" name="q1" value="1" id="q1a"><label for="q1a">Option 1</label>
			</div>
	  	</li>
	  	<li class="list-group-item">
	  		<div class="radio ">
			  	<input type="radio" name="q1" value="2" id="q1b"><label for="q1b">Option 2</label>
			</div>
	  	</li>
	  	<li class="list-group-item">
	  		<div class="radio ">
			  	<input type="radio" name="q1" value="3" id="q1c"><label for="q1c">Option 3</label>
			</div>
	  	</li>
	  	<li class="list-group-item">
	  		<div class="radio ">
			 	<input type="radio" name="q1" value="4" id="q1d"><label for="q1d">Option 4</label>
			</div>
	  	</li>
	  </ul>
   </div>
  </div> -->


	<?php
	  $i=1;
      $qfile = $_GET['qlink'];
      $file_path = $qfile;
     strtok($file_path, "/");
       strtok("/");
      $cid = strtok("/");
      $week = substr(strtok("/"),4);
	  	$file= $qfile;
		$linecount = 0;
		$handle = fopen($file, "r");
		while(!feof($handle)){
		  $line = fgets($handle);
		  $linecount++;
		}
		fclose($handle);

		// echo "line count ".$linecount."";

		$file=$qfile;
		$handle = fopen($file, "r");

		$linecount = $linecount/5;

	  while($i<=$linecount)
	  {
	  	$question = fgets($handle);
	  	$option1 = fgets($handle);
	  	$option2 = fgets($handle);
	  	$option3 = fgets($handle);
	  	$option4 = fgets($handle);
	    echo "
	    	  <div class=\"row\">
	    	  	<div class=\"panel panel-default col-sm-8\" style=\" text-align:center; font-weight:bold; font-size:100%; margin-left: 15%;\">
	    	  	  <div class=\"panel-heading\">Question ".$i."</div>
	    	  	  <div class=\"panel-body\" style=\"text-align:left\">
	    	  	    <p>".$question."</p>
	    	  	  </div>

	    	  	  <ul class=\"list-group\" style=\"text-align:left\">
	    	  	  	<li class=\"list-group-item\">
	    	  	  		<div class=\"radio\">
	    	  	  			<input type=\"radio\" name=\"q".$i."\" value=\"1\" id=\"q".$i."11\"><label for=\"q".$i."11\" id=\"q".$i."1\">".$option1."</label>
	    	  			</div>
	    	  	  	</li>
	    	  	  	<li class=\"list-group-item\">
	    	  	  		<div class=\"radio \">
	    	  			  	<input type=\"radio\" name=\"q".$i."\" value=\"2\" id=\"q".$i."22\"><label for=\"q".$i."22\" id=\"q".$i."2\">".$option2."</label>
	    	  			</div>
	    	  	  	</li>
	    	  	  	<li class=\"list-group-item\">
	    	  	  		<div class=\"radio \">
	    	  			  	<input type=\"radio\" name=\"q".$i."\" value=\"3\" id=\"q".$i."33\"><label for=\"q".$i."33\" id=\"q".$i."3\">".$option3."</label>
	    	  			</div>
	    	  	  	</li>
	    	  	  	<li class=\"list-group-item\">
	    	  	  		<div class=\"radio \">
	    	  			 	<input type=\"radio\" name=\"q".$i."\" value=\"4\" id=\"q".$i."44\"><label for=\"q".$i."44\" id=\"q".$i."4\">".$option4."</label>
	    	  			</div>
	    	  	  	</li>
	    	  	  </ul>
	    	     </div>
	    	    </div>
	    ";
	    $i=$i+1;
	  }
	  fclose($handle);

    $afile = $_GET['alink'];
		$file=$afile;
		$ar = array();
		$handle = fopen($file, "r");
		while(!feof($handle)){
		  $line = fgets($handle);
		  $t = strtok($line,"\n");
		  $ar[] = $t;
		}
		fclose($handle);

	?>

	<script type = "text/javascript">
	var answers = <?php
			echo json_encode($ar);

	?>;
	var cid = <?php
			echo json_encode($cid);
	
	?>;
	var week = <?php
			echo json_encode($week);
	
	?>;
	</script>

	<div class="row">
		<div class="button1">
			<button type="button" name = "submit-button" class="btn btn-info btn-md col-sm-4" id="check-results" data-toggle="modal" data-target="#myModal">Submit</button>
		</div>

		<div class="modal fade" id="myModal" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title" id="modal-title"></h4>
		      </div>
		      <div class="modal-body">
		        <p id="modal-data"></p>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>

		  </div>
		</div>
	</div>

  	<div id="questions-status">
	</div>

	<div id="wrong-ans">
  	</div>

  	<div class="row">
  		<div class="button1">
  			<a type="button" name = "close-button" class="btn btn-info btn-md col-sm-4" id="close-button" onclick="func()">Close</a>
  		</div>
  	</div>

  </body>
</html>
