$(document).ready(function()
{



$("#check-results").click(function() {
// alert(answers);
var test = false;

for (i = 0; i < answers.length; i++) {
	var str1="input[@name=q";
	str1=str1 + (i+1);
	str1=str1+"]:checked";
   	test = test || !$(str1).val();
}

if (test)
{
	var catStr = "ERROR"
	document.getElementById('modal-title').innerHTML=catStr;

	catStr = "Please Complete the quiz "
	document.getElementById('modal-data').innerHTML=catStr;
}

else {

// catname stores the serial number of questions
var catname = new Array(answers.length+1);
for (i = 1; i <= catname.length; i++) {
    catname[i-1] = i.toString();
}
//catname.push("None");
catname[catname.length] = "None";

// cat stores the result of answer checking
var cat = new Array(answers.length);
var wrong_question_nos = 0;

for (i = 0; i < cat.length; i++) {
	var str1="input[@name=q";
	str1=str1 + (i+1);
	str1=str1+"]:checked";

    cat[i] = ($(str1).val() !== answers[i]);

    if(cat[i])
    {
    	wrong_question_nos++;
    }
}

var cat11 = (wrong_question_nos == answers.length);

var categories = [];

for (i = 0; i < cat.length; i++) {
	if(cat[i])
	{
		categories.push(catname[i]);
	}
}
if(cat11) {categories.push(catname[catname.length])};


// DON'T DELETE CODE FOR PRINTING ON HTML PAGE
// if(cat11)
// {
// 	var catStr = 'You answered all questions incorrectly ';
// 	document.getElementById("questions-status").innerHTML=catStr;
// }
// else
// {
// 	if(wrong_question_nos == 0)
// 	{
// 		var catStr = 'You answered all questions correctly ';
// 		document.getElementById("questions-status").innerHTML=catStr;
// 	}
// 	else
// 	{
// 		var catStr = 'You answered the following questions incorrectly: ' + categories.join(', ') + '';
// 		document.getElementById("questions-status").innerHTML=catStr;
// 	}
// }

// for (i = 1; i <= cat.length; i++) {
// 	if(cat[i-1])
// 	{
// 		var catStr = 'Question '+i+': The correct answer is '+'Option '+answers[i-1]+' ';
// 		var newdiv=document.createElement('p');
// 		newdiv.innerHTML=catStr;
// 		document.getElementById('wrong-ans').appendChild(newdiv);
// 	}
// }

var score = answers.length - wrong_question_nos;

var catStr = "SCORE";

document.getElementById('modal-title').innerHTML=catStr;

var catStr = "Your Score is " + score;
document.getElementById('modal-data').innerHTML=catStr;

$(this).hide();
$("#close-button").show();

for (i = 1; i <= answers.length; i++) {
	var correctone;
	var wrongone;
	if(cat[i-1])
	{

		correctone = "q" + i + "" + answers[i-1];
		document.getElementById(correctone).style.color="green";

		var str1="input[@name=q";
		str1=str1 + i;
		str1=str1+"]:checked";

		wrongone = "q" + i + "" + $(str1).val();
		document.getElementById(wrongone).style.color="red";
	}
	else
	{
		correctone = "q" + i + "" + answers[i-1];
		document.getElementById(correctone).style.color="green";
	}
}
	// alert(cid);
	// alert(week);

		$.ajax(
	      {
	        url: "quizTaken.php",
	        type:"post",
	        dataType:"json",
	        data:
	        {
	            score: score,
	            question: answers.length,
	            cid: cid,
	            week: week
	        },

	        success: function(json)
	        {

	            if(json.status == 1)
	            {

	            	//alert("SUCCESS");
	              //location.href="student-courses.php?cID=".concat(cid);
	            }
	            else if(json.status==0)
	            {
	              //alert("Can not check at the moment!");
	            }
	            else
	           	{
	           		//alert("LOL");
	           	}

	        },

	        error : function()
	        {
	         // alert("ERROR");
	        }
	      });
/*method = "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action","quizTaken.php");


	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name","score");
	hiddenField.setAttribute("value", score);

	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name","question");
	hiddenField.setAttribute("value",answers.length);

	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name","cid");
	hiddenField.setAttribute("value", cid);

	form.appendChild(hiddenField);
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name","week");
	hiddenField.setAttribute("value",week);

	form.appendChild(hiddenField);
   // document.body.appendChild(form);
    form.submit();*/



}
 });
});

// $("#close-button").click(function() {
// 	alert("HII");
// 	location.href="student-courses.php?cID=".concat(cid);
// 	});
function func()
{
//	alert("HII");
	location.href="student-courses.php?cID=".concat(cid);
}
