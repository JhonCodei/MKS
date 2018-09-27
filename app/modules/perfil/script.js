function cambio_password()
{
    $("#loader").empty();

    var controller = __AJAX__ + "perfil-cambio_password",
        connect;
    var password_ = $("#password_").val();
    var repassword_ = $("#repassword_").val();
     
    form = "password=" + password_ + "&repassword=" + repassword_;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() 
    {
        if (connect.readyState == 4 && connect.status == 200) 
        {
            $("#loader").empty();
            
            if(parseInt(connect.responseText) == 1)
            {
                swal("Correcto", "Contraseña modificada.", 'success');
                empty_inputs();              
            }else
            {
                swal("Error", connect.responseText, 'warning');
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
function empty_inputs()
{
    $("#password_").val('');
    $("#repassword_").val('');
}