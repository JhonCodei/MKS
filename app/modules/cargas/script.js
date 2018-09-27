//CODE
$("#formdata").submit(function(evt) {
    var formData = new FormData();
    formData.append('excel', $('#excel')[0].files[0]);
    formData.append('periodo', $("#periodoinp").val());
    formData.append('region', $("#region_id").val());

    $.ajax({
        url: 'ajax.php?request=cargas-load_csv',
        type: 'POST',
        data: formData,
        async: true,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $("#loader").html(loader());
        },
        complete: function() {
            $("#loader").empty();
        },
        success: function(response) {
            $("#event").html('<span>' + response + '</span>');
            // alert(response);
        }
    });
    return false;
});

function generar_reporte_venta() {
    var controller = __AJAX__ + "cargas-generar_reporte_venta",
        connect, form;

    form = "periodo="+ $("#periodoinp").val();

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $("#graph_portafolio").html(connect.responseText);
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}

function sierra_central_proceso() {
    var controller = __AJAX__ + "cargas-sierra_central_proceso",
        connect, form;

    form = "periodo="+ $("#periodoinp").val();

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $("#graph_portafolio").html(connect.responseText);
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}
function generar_reporte_visitas()
{
    var controller = __AJAX__ + "cargas-generar_reporte_visitas",
        connect, form;

    form = "periodo="+ $("#periodoinp_2").val() + "&region=" + $("#region_select_id").val();

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $("#graph_portafolio").html(connect.responseText);
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}