$('.dropdown-menu a').on('click', function(){
    $('.dropdown-toggle').html($(this).html() + '<span class="caret"></span>');
})

function login_func()
{
		var uname=document.getElementById("username").value;
		var pwd=document.getElementById("inputPassword").value;
		var role=document.getElementById("inputtypel").value;
		$.ajax(
	      {
	        url: "login.php",
	        type:"post",
	        dataType:"json",
	        data:
	        {
	        	uname:uname,
	            pwd:pwd,
	            role:role
	        },

	        success: function(json)
	        {
	            //alert("SUCCESS");
	            //alert(json.status);
	            if(json.status == 1)
	            {
	              	if(role=="Teacher")
	              	{
	              		location.href="faculty.php";
	              	}
	              	else if(role=="Student")
	              	{
	              		location.href="student.php";
	              	}
	              	else if(role=="Parent")
	              	{
	              		location.href="parent.php";
	              	}
	              	else
	              	{
	              		location.href="admin.php";
	              	}
	            }
	            else if(json.status==0)
	            {
	            	document.getElementById("wrong-user").hidden = false;
					document.getElementById("wrong-user").innerHTML="Wrong username or password";
					document.getElementById("username").value="";
					document.getElementById("inputPassword").value="";
	            }
	            else if(json.status==3)
	            {
	            	document.getElementById("wrong-user").hidden = false;
					document.getElementById("wrong-user").innerHTML="Wrong username or password or not verified yet";
					document.getElementById("username").value="";
					document.getElementById("inputPassword").value="";
	            }

	        },

	        error : function()
	        {
	          alert("ERROR");
	          //console.log("something went wrong");
	        }
	      });
}
function signup_func()
{
	var patcontact=/^[0-9]+/;
	var name=document.getElementById("entername").value;
	var uname=document.getElementById("enterusername").value;
	var email=document.getElementById("enterEmail").value;
	var pwd=document.getElementById("enterPassword").value;
	var contact=document.getElementById("enterContact").value;
	var role=document.getElementById("inputtypes").value;
	//console.log(cntact.match(patcontact));
	var ev = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var x= ev.test(email);
    var cv=/^\d+$/;
    var y=cv.test(contact);
    if(x==false)
    {
    	alert("Invalid Email");
    	document.getElementById("enterEmail").value="";
    }
    else
    {
		if(y==false)
		{
			alert("Invalid Contact");
			document.getElementById("enterContact");value="";
		}
		else
		{
			 $.ajax(
		      {
		        url: "signup.php",
		        type:"post",
		        dataType:"json",
		        async: false,
		        data:
		        {
		        	name:name,
		        	email:email,
		            uname:uname,
		            contact:contact,
		            pwd:pwd,
		            role:role
		        },

		        success: function(json)
		        {
		        	console.log(json.status);
		            if(json.status == 2)
		            {
		              //alert("SUCCESSFUL");
                      location.href="index.php";
		            }
		            else if(json.status==0)
		            {
		            	document.getElementById("wrong-new-user").hidden = false;
						document.getElementById("wrong-new-user").innerHTML="Username already taken.";
						document.getElementById("enterusername").value="";
		            }
		            else if(json.status==1)
		            {
		            	document.getElementById("wrong-new-email").hidden = false;
						document.getElementById("wrong-new-email").innerHTML="Email already taken";
						document.getElementById("enterEmail").value="";
		            }
		            else if(json.status==3)
		            {
		            	alert("Submitted Successfully for admin verification");
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
