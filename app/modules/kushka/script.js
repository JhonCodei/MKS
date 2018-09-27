//CODE
$("#formdata").submit(function(evt) {
    var formData = new FormData();
    formData.append('excel', $('#excel')[0].files[0]);
    formData.append('periodo', $("#periodoinp").val());
    formData.append('tipocarga', $("#tipocarga").val());
    formData.append('empresa', $("#empresa").val());

    $.ajax({
        url: __AJAX__ + 'kushka-load_tipo_carga',
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

function generar_data()
{
    $("#loader").empty();
   
    var controller = __AJAX__ + "kushka-generar_data",
        connect, postData, explode;

    var periodo = $("#periodoinp").val();
    postData = "periodo="+periodo;
    
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() 
    {
        if (connect.readyState == 4 && connect.status == 200) 
        {
            $("#loader").empty();
            
            //console.log(connect.responseText);

        } else if (connect.readyState != 4) 
        {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function listar_reporte()
{
    $("#loader").empty();
   
    var controller = __AJAX__ + "kushka-listar_reporte",
        connect, postData, explode;

    var periodo = $("#periodo_data").val();

    if (periodo.length == 0)
    {
        $("#div-content-search-1").html("Ingrese periodo");
        return false;
    }    
    postData = "periodo="+periodo;
    
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() 
    {
        if (connect.readyState == 4 && connect.status == 200) 
        {
            $("#loader").empty();

            var result = connect.responseText;
            //console.log(result);
            var split_data = result.split('|~|');

            var table_print = split_data[0];
            var JSON_DATA = JSON.parse(split_data[1]);
            
            if (parseInt(table_print) == 0)
            {
                $("#div-content-search-1").html("Sin resultados");
            }else
            {
                $("#div-content-search-1").html(table_print);

                var table = $("#table-kushka_all").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: JSON_DATA.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'Reporte Kushka',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        footer: true,
                        className: 'btn btn-default waves-effect waves-light'
                    }],
                    columns: [
                        { "data": "cd"},
                        { "data": "distribuidora"},
                        { "data": "periodo"},
                        { "data": "cod_local"},
                        { "data": "distrito"},
                        { "data": "cliente"},
                        { "data": "descripcion"},
                        { "data": "region"},
                        { "data": "zonag"},
                        { "data": "zona"},
                        { "data": "z_descripcion"},
                        { "data": "producto_cod"},
                        { "data": "producto_name"},
                        { "data": "cantidad"},
                        { "data": "valor"}
                    ],
                    responsive: true,
                    iDisplayLength: 50,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false
                });
                table.buttons().container().appendTo('#table-kushka_all_wrapper .col-md-6:eq(0)');
                $(table.table().body()).css('font-size', '0.7em').css("font-family", "verdana").css("color", "black");
                $("#table-kushka_all_filter").addClass("pull-left");
                $("#table-kushka_all_filter").css("border-color", "#588D9C !important");
                $("#table-kushka_all_paginate").addClass("pull-left");
            }
        } else if (connect.readyState != 4) 
        {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}