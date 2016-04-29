function showResult(str) {
    //alert(str);
    // if (str.length==0) {
    //     return;
    // }
    //document.getElementById("livesearch").innerHTML="";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
            //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "livesearch_course.php?q=" + str, true);
    xmlhttp.send();
}


function myFunction(element) {
    var parent = element.parentNode.parentNode;
    var cID = parent.id;
    $.ajax(
        {
            url: "registerCourse.php",
            type:"post",
            dataType:"json",
            data:
            {
                cID: cID
            },

            success: function(json)
            {
                if(json.status == 1)
                {
                    location.href="student-courses.php?cID=".concat(cID);
                }
                else if(json.status==0)
                {
                    alert("Can not register at the moment!");
                }

            },

            error : function()
            {
                alert("ERROR");
            }
        });
    }
