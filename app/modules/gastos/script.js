function close_modal(target)
{
    $("#btn_events").removeAttr("onclick").attr("onclick","_insertar_gasto()");

    $("#" + target).modal('hide');
    $("#id_gasto_sql").val('');
    
    modal_cleaning();
}
function showModalAdd()
{
    $("#modal-add-record").modal('show');
    jQuery('#fecha').datepicker({
        orientation: 'auto top',
        toggleActive: true,
        autoclose: true,
        format: "dd/mm/yyyy",
        todayHighlight: true,
        
    }).datepicker("setDate", new Date());
    $("#id_gasto_sql").val('');
}
function modal_cleaning()
{
    $("#documentos").val('');
    $("#ruc").val('');
    $("#importe").val('');
    $("#ventas").val('');
    $("#ruc-cliente").val('');
    $("#observaciones").val('');
    $("#id_gasto_sql").val('');
}

function _insertar_gasto()
{
    swal({
        title: 'Aviso',
        text: "Desea guardar los cambios",
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-primary waves-light waves-effect',
        cancelButtonClass: 'btn btn-danger m-l-10 waves-light waves-effect',
        buttonsStyling: true
    }).then(function()
    {
        var controller = __AJAX__ + "gastos-_insertar_gasto", postData = new FormData();
        
        var periodo = $("#periodo").val();
        var quincena = $("#quincena").val();
        var fecha = $("#fecha").val();
        var motivo = $("#motivo").val();
        var tipo = $("#tipo").val();
        var documentos = $("#documentos").val();
        var ruc = $("#ruc").val();
        var importe = $("#importe").val();
        var ventas = $("#ventas").val();
        var ruc_cliente = $("#ruc-cliente").val();
        var observaciones = $("#observaciones").val();
        
        //POST DATA[]
        postData.append("periodo", periodo);
        postData.append("quincena", quincena);
        postData.append("fecha", fecha);
        postData.append("motivo", motivo);
        postData.append("tipo", tipo);
        postData.append("documentos", documentos);
        postData.append("ruc", ruc);
        postData.append("importe", importe);
        postData.append("ventas", ventas);
        postData.append("ruc_cliente", ruc_cliente);
        postData.append("observaciones", observaciones);
    
        
        $.ajax({
            url: controller,
            type: 'POST',
            data: postData,
            async: true,
            cache: false,
            contentType: false,
            enctype: 'application/x-www-form-urlencoded',
            processData: false,
            beforeSend: function()
            {
                $("#loader").html(loader());
            },
            complete: function()
            {
                $("#loader").empty();
            },
            success: function(response)
            {

                if (parseInt(response) == 1)
                {
                    swal({
                        title: 'Correcto',
                        text: "Guardado correctamente",
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar',
                        //cancelButtonText: 'No cerrar',
                        confirmButtonClass: 'btn btn-primary waves-light waves-effect',
                        cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
                        buttonsStyling: true
                    }).then(function()
                    {
                        close_modal("modal-add-record");
                        listado_gastos();
                    }, function(dismiss) {});
                } else
                {
                    swal("Error", response, "error");
                }
            }
        });
        
    }, function(dismiss){});   
}
function listado_gastos()
{
    var controller = __AJAX__ + "gastos-listado_gastos", postData = new FormData();

    var periodo = $("#periodo").val();
    var quincena = $("#quincena").val();

    postData.append("periodo", periodo);
    postData.append("quincena", quincena);


    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: true,
        contentType: false,
        enctype: __POST__,
        processData: false,
        beforeSend: function(){$("#loader").html(loader());},
        complete: function(){$("#loader").empty();},
        success: function(response)
        {
            $("#content-div").html(response);

            var table = $("#listado-gastos").DataTable({
                dom: "Bfrtip",
                buttons: [{
                    extend: 'excel',
                    footer: true,
                    title: 'Listado de Gastos',
                    text: '<span class="fa fa-file-excel-o"></span> Excel',
                    className: 'btn btn-default waves-effect waves-light',
                    // exportOptions: {
                    //     columns: '.print_this'
                    // }
                }],
                lengthChange: false,
                responsive: false,
                bLengthChange: false,
                destroy: true,
                bFilter: true,
                bInfo: false,
                bSort: true,
                language:
                {
                    paginate:
                    {
                        previous: "<span class='fa fa-chevron-left'></span>",
                        next: "<span class='fa fa-chevron-right'></span>",
                    }
                },
                pagingType: "simple",
                //order: [ [ 3,  'desc' ] ]
            });
            $("#listado-gastos_filter").addClass("pull-left").css("color", "black");
            _input_search_color();
            $("#listado-gastos_paging").addClass("pull-left"); 
            $(".pagination").css("color", "black");

            var gastos_ = 0;
            var ventas_ = 0;


            $('#listado-gastos tbody tr td:nth-child(7)').each(function() {
                gastos_ += parseInt($(this).html()) || 0;
            });

            $('#listado-gastos tbody tr td:nth-child(6)').each(function() {
                ventas_ += parseInt($(this).html()) || 0;
            });

            $("#sum_gastos").text( "  S/. " + gastos_.toFixed(2));
            $("#sum_ventas").text( "  S/. " + ventas_.toFixed(2));

            //HOLAPE
        }
    });
}
function editar_gasto(id)
{
    $("#btn_events").removeAttr("onclick").attr("onclick","update_gasto()");
    var controller = __AJAX__ + "gastos-editar_gasto", postData = new FormData();

    postData.append("id", id);

    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: true,
        contentType: false,
        enctype: __POST__,
        processData: false,
        beforeSend: function(){$("#loader").html(loader());},
        complete: function(){$("#loader").empty();},
        success: function(response)
        {
            var split_response = response.split('|~|');

            var fecha = split_response[0];
            var motivo = split_response[1];
            var tipo_doc = split_response[2];
            var documento = split_response[3];
            var ruc = split_response[4];
            var importe = split_response[5];
            var ventas = split_response[6];
            var ruc_cliente = split_response[7];
            var observaciones = split_response[8];
            
            $("#id_gasto_sql").val(id);

            $("#motivo").val(motivo).change();
            $("#tipo").val(tipo_doc).change();
            $("#fecha").val(fecha);
            $("#documentos").val(documento);
            $("#ruc").val(ruc);
            $("#importe").val(importe);
            $("#ventas").val(ventas);
            $("#ruc-cliente").val(ruc_cliente);
            $("#observaciones").val(observaciones);

            $("#btn_events").removeAttr("onclick").attr("onclick","update_gasto()");
            $("#modal-add-record").modal('show');

            jQuery('#fecha').datepicker({
                orientation: 'auto top',
                toggleActive: true,
                autoclose: true,
                format: "dd/mm/yyyy",
                todayHighlight: true,
                
            });

            
        }
    });
}
function update_gasto()
{
    swal({
        title: 'Aviso',
        text: "Desea guardar los cambios",
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-primary waves-light waves-effect',
        cancelButtonClass: 'btn btn-danger m-l-10 waves-light waves-effect',
        buttonsStyling: true
    }).then(function()
    {
        var controller = __AJAX__ + "gastos-update_gasto", postData = new FormData();
        
        var id = $("#id_gasto_sql").val();
        var periodo = $("#periodo").val();
        var quincena = $("#quincena").val();
        var fecha = $("#fecha").val();
        var motivo = $("#motivo").val();
        var tipo = $("#tipo").val();
        var documentos = $("#documentos").val();
        var ruc = $("#ruc").val();
        var importe = $("#importe").val();
        var ventas = $("#ventas").val();
        var ruc_cliente = $("#ruc-cliente").val();
        var observaciones = $("#observaciones").val();
        
        //POST DATA[]
        postData.append("id", id);
        postData.append("periodo", periodo);
        postData.append("quincena", quincena);
        postData.append("fecha", fecha);
        postData.append("motivo", motivo);
        postData.append("tipo", tipo);
        postData.append("documentos", documentos);
        postData.append("ruc", ruc);
        postData.append("importe", importe);
        postData.append("ventas", ventas);
        postData.append("ruc_cliente", ruc_cliente);
        postData.append("observaciones", observaciones);
    
        
        $.ajax({
            url: controller,
            type: 'POST',
            data: postData,
            async: true,
            cache: false,
            contentType: false,
            enctype: 'application/x-www-form-urlencoded',
            processData: false,
            beforeSend: function()
            {
                $("#loader").html(loader());
            },
            complete: function()
            {
                $("#loader").empty();
            },
            success: function(response)
            {

                if (parseInt(response) == 1)
                {
                    swal({
                        title: 'Correcto',
                        text: "Guardado correctamente",
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar',
                        //cancelButtonText: 'No cerrar',
                        confirmButtonClass: 'btn btn-primary waves-light waves-effect',
                        cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
                        buttonsStyling: true
                    }).then(function()
                    {
                        close_modal("modal-add-record");
                        listado_gastos();
                        $("#id_gasto_sql").val('');
                    }, function(dismiss) {});
                } else
                {
                    swal("Error", response, "error");
                }
            }
        });
        
    }, function(dismiss){});   
}
function eliminar_gasto(id)
{
    swal({
        title: 'Aviso',
        text: "Desea eliminar el registro?",
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-danger waves-light waves-effect',
        cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
        buttonsStyling: true
    }).then(function()
    {
        var controller = __AJAX__ + "gastos-eliminar_gasto", postData = new FormData();
        //POST DATA[]
        postData.append("id", id);  
        
        $.ajax({
            url: controller,
            type: 'POST',
            data: postData,
            async: true,
            cache: false,
            contentType: false,
            enctype: 'application/x-www-form-urlencoded',
            processData: false,
            beforeSend: function()
            {
                $("#loader").html(loader());
            },
            complete: function()
            {
                $("#loader").empty();
            },
            success: function(response)
            {
                if (parseInt(response) == 1)
                {
                    swal({
                        title: 'Correcto',
                        text: "Eliminado correctamente",
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar',
                        //cancelButtonText: 'No cerrar',
                        confirmButtonClass: 'btn btn-primary waves-light waves-effect',
                        cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
                        buttonsStyling: true
                    }).then(function()
                    {
                        listado_gastos();
                        $("#id_gasto_sql").val('');
                    }, function(dismiss) {});
                } else
                {
                    swal("Error", response, "error");
                }
            }
        });
        
    }, function(dismiss){});   
}