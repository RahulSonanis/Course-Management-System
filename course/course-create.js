var counter = 1;
var limit = 3;

var ar = <?php echo json_encode($ar) ?>;
for(var i=0;i<ar.length;i++)
{
	var newdiv1=document.createElement('option');
	option.innerHTML=ar[i][1];
	alert(ar[i][1]);
	document.getElementById('pre-course').appendChild(newdiv1);
}


function addInput(divName){
	var ar=<?php echo json_encode($ar); ?>;
     if (counter == limit)  {
          alert("You have reached the limit of adding " + counter + " inputs");
     }
     else {
          var newdiv = document.createElement('div');
          newdiv.innerHTML = " <label for=\"pre-requisite\" class=\"sr-only\">Pre Requisite</label> " +"<input type=\"text\" id=\"pre-requisite\" class=\"form-control\" placeholder=\"Pre Requisite "+ (counter+1) +" \" name=\"myInputs[]\" required> ";
          document.getElementById(divName).appendChild(newdiv);
          counter++;
     }
}