$(document).ready(function(){
    
    $("#pole").change(function(){
        if($("#pole").val().length > 3)
        {
            var str = $("#pole").val().toLowerCase();

            if (str=="")
            {
                $("#elm1").html("");
                return;
            } 
            
            if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            }
            
            else
            {// code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {   
                    if(xmlhttp.responseText == 1)
                        $("#elm1").html("<font style=\"color: red\">Existující uživ. jméno, vyberte si jiné</font>");
                    else if(xmlhttp.responseText == -1)
                        $("#elm1").html("!!! chyba - nelze ověřit uživatelské jméno");
                    else
                        $("#elm1").html("<font style=\"color: green\">:)</font>");
                }
            }
            xmlhttp.open("GET","vrat.php?q="+str,true);
            xmlhttp.send();
        }
        
        else
        {
            $("#elm1").html("<font style=\"color: red\">musí být delší než 3 znaky</font>"); 
        }
    });
});


