$('#usuario').focus();
function keylogeo(e) 
{
    if (e.keyCode == 13) 
    {
        logeo();
    }
}
function logeo() 
{
    $("#loader").html();

    var controller = __AJAX__+"login-logeo", connect;

    usuario = $('#usuario').val();
    password = $('#password').val();

    form = "usuario=" + usuario + "&password=" + password;
    
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() 
    {
        if (connect.readyState == 4 && connect.status == 200) 
        {
            $("#error").empty();
            $("#loader").empty();
            
            var response = connect.responseText;
            var arrayData = response.split('~~');
            
            if (parseInt(arrayData[0]) == 1) 
            {
                window.location = arrayData[1];
            } else 
            {
                $("#error").html(error_(arrayData[1]));
            }
        } else if (connect.readyState != 4) 
        {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}