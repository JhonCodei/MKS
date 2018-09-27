//CODE
jQuery('#fecha__mm').datepicker({
    orientation: 'auto top',
    toggleActive: true,
    autoclose: true,
    format: "dd/mm/yyyy",
    todayHighlight: true
});
function press_enter_(e) {
    if (e.keyCode == 13) {
        load_table_modal_event();
    }
}

function close_modal(target) {
    $("#" + target).modal('hide');
}

function clear_after_input(target1, target2) {

    var taget1_val = $("#" + target2).val();

    if (taget1_val.length == 0) {
        $("#" + target2).val('');
    }
}

function close_modal_mm_await() {
    swal({
        title: 'Cancelar',
        text: "Desea cerrar el formulario?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Cerrar',
        cancelButtonText: 'No cerrar',
        confirmButtonClass: 'btn btn-danger waves-light waves-effect',
        cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
        buttonsStyling: false
    }).then(function() {

        setTimeout(function() {
            location = ''
        }, 0);
    }, function(dismiss) {
        // dismiss can be 'cancel', 'overlay',
        // 'close', and 'timer'
        // if (dismiss === 'cancel') {
        //     setTimeout(function() {
        //         location = ''
        //     }, 0);
        // }
    });
}


function load_table_modal_event(tipo_consulta) {
    $("#loader").empty();

    var connect;

    if (tipo_consulta == 0) {

        var controller = __AJAX__ + "muestramedica-_buscar_medicos";
        var med_cod = $("#med_cmp").val();

        if (med_cod.length == 0) {
            med_cod = 'S';
        }
        postData = "med_cod=" + med_cod;

    } else if (tipo_consulta == 1) {

        var controller = __AJAX__ + "muestramedica-_buscar_supervisor"
        var cod_sup = $("#cod_sup").val();

        if (cod_sup.length == 0) {
            cod_sup = 'S';
            // jQuery('#fecha_entrega_mm').datepicker({
            //     orientation: 'auto top',
            //     toggleActive: true,
            //     autoclose: true,
            //     format: "dd/mm/yyyy",
            //     todayHighlight: true
            // });
        }
        postData = "cod_sup=" + cod_sup;
    }

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;
            var check = result.split('||');

            if (parseInt(check[0]) == 1) {

                var datos = check[1].split('~~');

                if (tipo_consulta == 0) {

                    $("#med_cmp").val(datos[0]);
                    $("#med_name").val(datos[1]);

                } else if (tipo_consulta == 1) {

                    $("#cod_sup").val(datos[0]);
                    $("#name_sup").val(datos[1]);
                }
            } else {
                $("#table-events-mm").html(check[1]);
                $("#modal-events-mm").modal();
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function keyup_filter_events() {
    var $rows = $('#table-list-events tbody tr');
    var val = $.trim($("#search_input_events").val()).replace(/ +/g, ' ').toLowerCase().split(' ');
    $rows.hide().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        var matchesSearch = true;
        $(val).each(function(index, value) {
            matchesSearch = (!matchesSearch) ? false : ~text.indexOf(value);
        });
        return matchesSearch;
    }).show();
}

function send_field_(id1, val1, id2, val2) {
    $("#" + id1).val(val1);
    $("#" + id2).val(val2);
    $("#modal-events-mm").modal('hide');
}

var i = 2;

function newElement(e) {
    var html = '';

    html = '<tr id="tr' + i + '">';
    // html += '<td>';
    // html += '<button class="btn btn-sm btn-danger waves-effect waves-light" onclick="removeElement(' + "'tr" + i + "'" + ');"><span class="fa fa-minus"></span></button>';
    // html += '</td>';

    html += '<td>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<span class="input-group-btn">';
    html += '<button type="button" class="btn btn-default btn-sm bootstrap-touchspin-down waves-effect waves-light" onclick="return _buscar_producto(' + i + ');">';
    html += '<span class="fa fa-search"></span>';
    html += '</button>';
    html += '</span>';
    html += '<input type="number" name="prod_cod[]" onkeypress="return max_length(this.value, 14);" class="form-control input-sm border-input" placeholder="codigo" id="prod_cod_' + i + '">';
    html += '</div>';
    html += '</td>';

    // html += '<td>';
    // html += '<div class="form-group">';
    // html += '<input name="prod_desc[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="prod_desc_' + i + '" placeholder="descripcion">';
    // html += '</div>';
    // html += '</td>';

    html += '<td>';
    html += '<div class="form-group">';
    html += '<input name="prod_cant[]" type="number" class="form-control input-sm text-center border-input" onkeypress="return max_length(this.value, 2);" onblur="return onblur_stock_actual(' + i + ');" id="cant_' + i + '" placeholder="cantidad">';
    html += '</div>';
    html += '</td>';


    html += '<td>';
    html += '<div class="form-group">';
    html += '<input name="prod_desc[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="prod_desc_' + i + '" placeholder="descripcion">';
    html += '</div>';
    html += '</td>';

    html += '<td>';
    html += '<div class="form-group">';
    html += '<input name="prod_stock[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="stock_' + i + '" placeholder="stock">';
    html += '</div>';
    html += '</td>';

    html += '<td>';
    html += '<div class="form-group">';
    html += '<input name="prod_stock_actual[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="stk_act_' + i++ + '" placeholder="stock actual">';
    html += '</div>';
    html += '</td>';
    html += '</tr>';

    $("#parent-div").prepend(html);

}

function _Show_small_notification(i, msg) {
    $("#prod_cod_" + i).notify(msg, {
        position: 'top',
        elementPosition: 'right',
        className: 'success',
        autoHide: true,
        autoHideDelay: 4000
    });
}

function removeElement(id) 
{
    $("#" + id).remove();
}
function delete_field(id)
{
    $('#row' + id).remove();
}
function clear_text(id1, id2)
{
    $("#" + id1).val('');
    $("#" + id2).val('');
}

function keyup_filter_()
{
    var $rows = $('#table_listado_medicos_ tbody tr');
    var val = $.trim($("#search_input").val()).replace(/ +/g, ' ').toLowerCase().split(' ');
    $rows.hide().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        var matchesSearch = true;
        $(val).each(function(index, value) {
            matchesSearch = (!matchesSearch) ? false : ~text.indexOf(value);
        });
        return matchesSearch;
    }).show();
}

//////////////////////////
function muestra_medica_form(id)
{
    var controller = __AJAX__ + "muestramedica-muestra_medica_form",
        connect, postData;

    postData = "id=" + id;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            //console.log(connect.responseText);
            $("#div_content_modal").html(connect.responseText);
            $("#modal_muestra_medica").modal();

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function muestra_medica_form_direct(data)
{
    var controller = __AJAX__ + "muestramedica-muestra_medica_form_direct",
        connect, postData;

    postData = "id=x";

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            //console.log(connect.responseText);
            $("#div_content_modal").html(connect.responseText);

            if (parseInt(data) == 0) {
                jQuery('#fecha_entrega_mm').datepicker({
                    orientation: 'auto top',
                    toggleActive: true,
                    autoclose: true,
                    format: "dd/mm/yyyy",
                    todayHighlight: true
                });
            } else {
                var array = data.split('||');

                var medcomplete = array[0];
                var name_med = array[1];
                var fecha = array[2];
                $("#fecha_entrega_mm").val(fecha);
                $("#fecha_entrega_mm").attr('readonly', true);
                $("#med_cmp").val(medcomplete);
                $("#med_cmp").attr('readonly', true);
                $("#med_name").val(name_med);


                // console.log("DESDE = " + medcomplete + '-' + name_med + '-' +fecha);
            }

            var screenView = screen.width;
            //alert(screenView);
            //console.log(screemView);
            if (screenView < 600) {

                $("#type_modal").removeClass('modal-full').addClass('modal-full');

            } else {
                $("#type_modal").removeClass('modal-full').addClass('modal-lg');
            }

            $("#modal_muestra_medica").modal();


        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function onblur_stock_actual(target)
{

    var cantidad = $("#cant_" + target).val();
    var stock = $("#stock_" + target).val();

    var diff = stock - cantidad;

    if (diff.length == 0) {
        diff = '';
    }

    $("#stk_act_" + target).val(diff);
}
function send_field_x4(id1, val1, id2, val2, id3, val3)
{

    $("#" + id1).val(val1);
    $("#" + id2).val(val2);
    $("#" + id3).val(val3);

    var final = id1.split('_');
    var last = final.pop();

    _Show_small_notification(last, val2);

    $("#modal-events-mm").modal('hide');
}
function _buscar_producto(target)
{

    $("#loader").empty();

    var controller = __AJAX__ + "muestramedica-_buscar_producto",
        connect;

    var prod_cod = $("#prod_cod_" + target).val();
    var fecha_entrega_mm = $("#fecha_entrega_mm").val();

    if (prod_cod.length == 0) {
        prod_cod = 'S';
    }
    postData = "prod_cod=" + prod_cod + "&fecha=" + fecha_entrega_mm + "&target=" + target;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;

            if (parseInt(result) == 8) {

                swal('error', 'Error de cobertura.!', 'error');

            } else {
                var check = result.split('||'); //separo los arrays

                if (parseInt(check[0]) == 1) {

                    var datos = check[1].split('~~');

                    if (parseInt(datos[0]) !== 0) {
                        _Show_small_notification(target, datos[1]);
                    }
                    $("#prod_cod_" + target).val(datos[0]);

                    $("#prod_desc_" + target).val(datos[1]);
                    // $("#cant_" + target).val(datos[0]);
                    $("#stock_" + target).val(datos[2]);

                } else {
                    $("#table-events-mm").html(check[1]);
                    $("#modal-events-mm").modal();
                }
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function change_estado()
{
    var status_mm = parseInt($("#status_mm").val());

    if (status_mm == 1) {

        $("#button_on_insert").removeAttr('disabled');
        $("#table-mm-add").removeClass('d-none');

    } else if (status_mm == 0) {
        // $("#button_on_insert").removeAttr('disabled').attr('disabled', true);
        $("#button_on_insert").removeAttr('disabled');
        $("#table-mm-add").addClass('d-none');
    } else if (status_mm == 2) {
        $("#button_on_insert").removeAttr('disabled');
        $("#table-mm-add").addClass('d-none');
    }
}
function insert_muestra_medica()
{
    // alert('insert_ruteo()');
    var controller = __AJAX__ + "muestramedica-insert_muestra_medica",
        connect, postData;

    var med_cmp = $("#med_cmp").val();
    var status_mm = $("#status_mm").val();
    var supervisor_mm = $("#cod_sup").val();
    var fecha_entrega_mm = $("#fecha_entrega_mm").val();

    var prod_cod = $("input[name='prod_cod[]']").map(function() { return $(this).val(); }).get();
    var prod_desc = $("input[name='prod_desc[]']").map(function() { return $(this).val(); }).get();
    var prod_cant = $("input[name='prod_cant[]']").map(function() { return $(this).val(); }).get();
    var stock = $("input[name='prod_stock[]']").map(function() { return $(this).val(); }).get();

    postData = "med_cmp=" + med_cmp + "&status_mm=" + status_mm + "&fecha_entrega_mm=" + fecha_entrega_mm +
        "&prod_cod=" + prod_cod + "&prod_desc=" + prod_desc + "&prod_cant=" + prod_cant + "&supervisor_mm=" + supervisor_mm + "&stock=" + stock;
    // console.log(postData);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            if (parseInt(connect.responseText) == 1) //ok
            {
                swal({
                    title: 'Registrado',
                    text: "Muestra médica registrada correctamente",
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger m-l-10',
                    buttonsStyling: false
                }).then(function() {
                    setTimeout(function() {
                        location = ''
                    }, 0);
                });
                // listado_mm();
            } else if (parseInt(connect.responseText) == 2) {
                swal("Registrado", "Muestra médica registrada correctamente", "success");
                // empty_inputs();
                // list_ruteo();
            } else if (parseInt(connect.responseText) == 0) {
                swal("Advertencia", "Error", "error");
            } else {
                swal("Advertencia", connect.responseText, "error");
                // console.log(connect.responseText);
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function listado_mm()
{
    var controller = __AJAX__ + "muestramedica-listado_mm",
        connect, postData;

    var fecha__mm = $("#fecha__mm").val();

    if (fecha__mm.length == 0) {
        swal("Error", "Ingrese una fecha", "error");
        return false;
    } else {
        postData = "fecha__mm=" + fecha__mm;
        // console.log(postData);
        connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        connect.onreadystatechange = function() {
            if (connect.readyState == 4 && connect.status == 200) {
                $("#loader").empty();

                var result = connect.responseText;
                var split_response = result.split('|~|');
                // console.log(result);
                var core_table = split_response[0];
                var json_data = split_response[1];

                if (parseInt(core_table) == 0) //error
                {
                    swal("Error", core_table, "error");
                } else if (parseInt(core_table) == 1) //error
                {
                    $("#div-listado_mm").html("-");
                } else {

                    $("#div-listado_mm").html(core_table);

                    var json_data = JSON.parse(json_data);

                    var table = $("#tbl_listado_mm").DataTable({
                        dom: "Bfrtip",
                        lengthChange: false,
                        destroy: true,
                        data: json_data.data,
                        buttons: [{
                            extend: 'excel',
                            footer: true,
                            title: 'Listado de Muestra medica',
                            text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                            className: 'btn btn-default waves-effect waves-light',
                            exportOptions: {
                                columns: '.print_this'
                            }
                        }],
                        columns: [
                            { "data": "medpro_id", className: 'text-center' },
                            { "data": "medpro_medico_cmp", className: 'text-center' },
                            { "data": "medpro_medico_name" },
                            { "data": "medpro_producto", className: 'text-center' },
                            { "data": "medpro_descripcion_producto" },
                            { "data": "medpro_cantidad", className: 'text-center' },
                            { "data": "medpro_estado", className: 'text-center' },
                            { "data": "medpro_fecha", className: 'text-center' },
                            { "data": "medpro_acciones", className: 'text-left' },
                        ],
                        // columnDefs: [
                        //     { "visible": false, "targets": 0 }
                        // ],
                        responsive: true,
                        iDisplayLength: 25,
                        bLengthChange: false, //show rows
                        bFilter: true,
                        bInfo: false,
                        order: [
                            [0, 'desc']
                        ],
                    });
                    table.buttons().container().appendTo('#tbl_listado_mm_wrapper .col-md-6:eq(0)');
                    $("#tbl_listado_mm_filter").addClass('pull-left');
                    $("#tbl_listado_mm_paginate").addClass('pull-left');
                    $("#tbl_listado_mm tbody").css('font-size', '0.87em');
                }
            } else if (connect.readyState != 4) {
                $("#loader").html(loader());
            }
        }
        connect.open('POST', controller, true);
        connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        connect.send(postData);
    }
}
function eliminar_mm(cmp, fecha)
{
    swal({
        title: 'Seguro de Anular?',
        text: "La cantidad ingresada se volvera a añadir a su Stock",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Anular',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-danger',
        cancelButtonClass: 'btn btn-default m-l-10',
        buttonsStyling: false
    }).then(function() {

        var controller = __AJAX__ + "muestramedica-eliminar_mm",
            connect, postData;

        postData = "cmp=" + cmp + "&fecha=" + fecha;

        connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        connect.onreadystatechange = function() {
            if (connect.readyState == 4 && connect.status == 200) {
                $("#loader").empty();

                var result = connect.responseText;

                if (parseInt(result) == 1) {
                    swal({
                        title: 'Anulado',
                        text: "Registro anulado correctamente",
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-default m-l-10',
                        buttonsStyling: false
                    }).then(function() {
                        listado_mm();
                        // setTimeout(function() {
                        //     location = ''
                        // }, 0);
                    }, function(dismiss) {
                        // dismiss can be 'cancel', 'overlay',
                        // 'close', and 'timer'
                        if (dismiss === 'cancel') {
                            setTimeout(function() {
                                location = ''
                            }, 0);
                        }
                    });
                } else if (parseInt(result) == 0) {
                    swal('Error', 'Fuera de fecha.', 'error');
                } else {
                    swal('Error', result, 'error');
                }
            } else if (connect.readyState != 4) {
                $("#loader").html(loader());
            }
        }
        connect.open('POST', controller, true);
        connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        connect.send(postData);
    }, function(dismiss) {
        if (dismiss === 'cancel') {

        }
    });
}
function table_medicos_x_dia()
{
    var controller = __AJAX__ + "muestramedica-table_medicos_x_dia",
        connect, postData;

    var fecha__mm = $("#fecha__mm").val();

    if (fecha__mm.length == 0) {
        swal("Error", "Ingrese una fecha", "error");
        return false;
    }
    postData = "fecha__mm=" + fecha__mm;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            $("#div-listado_mm").empty();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' No hay registros ', "error");
            } else {

                $("#div-listado_mm").html(connect.responseText);

                var table = $("#table_medicos_x_dia").DataTable({
                    lengthChange: false,
                    responsive: false,
                    bLengthChange: false,
                    bFilter: false,
                    bInfo: false,
                    bSort: false,
                    bAutoWidth: false,
                    "paging": false
                });
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function cobertura_visitados_detalle(representantes, fecha)
{
    var controller = __AJAX__ + "muestramedica-cobertura_visitados_detalle",
        connect, postData;

    postData = "representantes=" + representantes +
        "&fecha=" + fecha;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();


            $('#table-modal-medicos-dia').html(connect.responseText);
            $("#modal-medicos-x-dia").modal();

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function cobertura_fecha_productos(codigo_medico, fechas_visitadas, mes, year, representantes, medico)
{
    var controller = __AJAX__ + "muestramedica-cobertura_fecha_productos",
        connect, postData;

    postData = "codigo_medico=" + codigo_medico +
        "&dia=" + fechas_visitadas +
        "&mes=" + mes +
        "&year=" + year +
        "&representantes=" + representantes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            // console.log(connect.responseText);

            $("#medico_desc").html(medico);
            $('#modal-table-producto-detalle-fecha').html(connect.responseText);
            $("#modal-productos-fechas").modal();


        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
change_event();
function change_event()
{
    var value = $('#select_event_change').val();
    $('#event_button_').removeAttr('onclick').attr('onclick', 'return ' + value + ';');
}
function update_columns_medpro()
{
    var controller = __AJAX__ + "muestramedica-update_columns_medpro",
        connect, postData;

    var cod_repre_go = $("#cod_repre_go").val();
    var periodo_reload = $("#periodo_reload").val();

    postData = "cod_repre=" + cod_repre_go + "&periodo=" + periodo_reload;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            $("#div-listado_mm").html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function productos_stock_representantes()
{
    var controller = __AJAX__ + "muestramedica-productos_stock_representantes",
        connect, postData;

    var fecha__mm = $("#fecha__mm").val();
    postData = "fecha_mm="+ fecha__mm;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            $("#div-listado_mm").html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function onsite_modal()
{
    var controller = __AJAX__ + "muestramedica-onsite_modal",
        connect, postData;

    postData = "x=x";

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            //console.log(connect.responseText);
            $("#div_content_modal_marcar").html(connect.responseText);

            var screemView = screen.width;
            //console.log(screemView);
            if (screemView < 600) {

                $("#type_modal").removeClass('modal-full').addClass('modal-full');

            } else {
                $("#type_modal").removeClass('modal-full').addClass('modal-lg');
            }

            $("#modal_marcar_sitio").modal();

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function insert_punto_marcacion()
{
    var controller = __AJAX__ + "muestramedica-insert_punto_marcacion",
        connect, postData;

    var med_cmp = $("#med_cmp").val();

    if (med_cmp.length == 0) {
        swal("Advertencia", "Ingrese el CMP", "error");
        return false;
    }

    postData = "med_cod=" + med_cmp;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            //console.log(connect.responseText);
            if (parseInt(connect.responseText) == 2) {
                swal("Advertencia", "El medico ya tiene este estado!.", "error");
                return false;
            }
            if (parseInt(connect.responseText) != 0) //ok
            {
                var response = connect.responseText;
                swal({
                    title: 'Correcto',
                    text: "Marcación registrada: " + response,
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger m-l-10',
                    buttonsStyling: false
                }).then(function() {
                    setTimeout(function() {
                        location = ''
                    }, 0);
                });
                listado_mm();
            } else {
                swal("Advertencia", "Ha ocurrido un error :(", "error");
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function finalizar_marcacion(med_cmp, fecha)
{
    var controller = __AJAX__ + "muestramedica-finalizar_marcacion",
        connect, postData;

    if (med_cmp.length == 0) {
        swal("Advertencia", "Ingrese el CMP", "error");
        return false;
    }

    postData = "med_cod=" + med_cmp + '&fecha=' + fecha;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            //console.log(connect.responseText);
            if (parseInt(connect.responseText) == 2) {
                swal("Advertencia", "El medico ya tiene este estado!.", "error");
                return false;
            }
            if (parseInt(connect.responseText) == 3) {
                swal("Advertencia", "Solo puede finalizarlo el mismo día . . .", "error");
                return false;
            }
            if (parseInt(connect.responseText) != 0) //ok
            {
                var response = connect.responseText;
                swal({
                    title: 'Correcto',
                    text: "Finalizado: " + response,
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger m-l-10',
                    buttonsStyling: false
                }).then(function() {
                    setTimeout(function() {
                        location = ''
                    }, 0);
                });
                listado_mm();
            } else {
                swal("Advertencia", "Ha ocurrido un error :(", "error");
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function eliminar_ingreso_salida(cmp, fecha)
{
    swal({
        title: 'Seguro de Anular?',
        text: "Se anulara la fecha y hora del contacto.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Anular',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-danger',
        cancelButtonClass: 'btn btn-default m-l-10',
        buttonsStyling: false
    }).then(function() {

        var controller = __AJAX__ + "muestramedica-eliminar_ingreso_salida",
            connect, postData;

        postData = "cmp=" + cmp + "&fecha=" + fecha;

        connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        connect.onreadystatechange = function() {
            if (connect.readyState == 4 && connect.status == 200) {
                $("#loader").empty();

                var result = connect.responseText;

                if (parseInt(result) == 1) {
                    swal({
                        title: 'Anulado',
                        text: "Registro anulado correctamente",
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-default m-l-10',
                        buttonsStyling: false
                    }).then(function() {
                        listado_mm();
                        // setTimeout(function() {
                        //     location = ''
                        // }, 0);
                    }, function(dismiss) {
                        // dismiss can be 'cancel', 'overlay',
                        // 'close', and 'timer'
                        if (dismiss === 'cancel') {
                            setTimeout(function() {
                                location = ''
                            }, 0);
                        }
                    });
                } else if (parseInt(result) == 0) {
                    swal('Error', 'Fuera de fecha.', 'error');
                } else {
                    swal('Error', result, 'error');
                }
            } else if (connect.readyState != 4) {
                $("#loader").html(loader());
            }
        }
        connect.open('POST', controller, true);
        connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        connect.send(postData);
    }, function(dismiss) {
        if (dismiss === 'cancel') {

        }
    });
}
function launch_modal_mm(medcomplete, name_med, fecha)
{
    var data = medcomplete + "||" + name_med + "||" + fecha;
    // console.log(data);
    muestra_medica_form_direct(data);

}
function table_medicos_x_visitar()
{
    var controller = __AJAX__ + "muestramedica-table_medicos_x_visitar",
        connect, postData;

    var fecha__mm = $("#fecha__mm").val();

    if (fecha__mm.length == 0) {
        swal("Error", "Ingrese una fecha", "error");
        return false;
    } else {
        postData = "fecha__mm=" + fecha__mm;
        // console.log(postData);
        connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        connect.onreadystatechange = function() {
            if (connect.readyState == 4 && connect.status == 200) {
                $("#loader").empty();

                var result = connect.responseText;
                var split_response = result.split('|~|');

                var core_table = split_response[0];
                var json_data = split_response[1];

                // console.log(core_table + '\n');
                // console.log(json_data + '\n');

                if (parseInt(core_table) == 0) //error
                {
                    $("#div-listado_mm").html('No hay registros');
                } else {

                    $("#div-listado_mm").html(core_table);

                    var json_data = JSON.parse(json_data);

                    var table = $("#table-medicos_x_visitar").DataTable({
                        dom: "Bfrtip",
                        lengthChange: false,
                        destroy: true,
                        data: json_data.data,
                        buttons: [{
                            extend: 'excel',
                            footer: true,
                            title: 'Listado de Muestra medica',
                            text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                            className: 'btn btn-default waves-effect waves-light'
                        }],
                        columns: [
                            { "data": "cmp", className: 'text-center' },
                            { "data": "nombre", className: 'text-left' },
                            { "data": "especialidad", className: 'text-center' },
                            { "data": "categoria", className: 'text-center' },
                            { "data": "institucion", className: 'text-left' },
                            { "data": "direccion", className: 'text-left' },                          
                            // { "data": "localidad", className: 'text-center' },
                            { "data": "mensaje_", className: 'text-left' }
                        ],
                        responsive: true,
                        iDisplayLength: 25,
                        bLengthChange: false, //show rows
                        bFilter: true,
                        bInfo: false,
                        order: [
                            [3, 'asc']
                        ],
                    });
                    table.buttons().container().appendTo('#table-medicos_x_visitar_wrapper .col-md-6:eq(0)');
                    $("#table-medicos_x_visitar_filter").addClass('pull-left');
                    $("#table-medicos_x_visitar_paginate").addClass('pull-left');
                    $("#table-medicos_x_visitar tbody").css('font-size', '0.87em').css('color', 'black');
                }
            } else if (connect.readyState != 4) {
                $("#loader").html(loader());
            }
        }
        connect.open('POST', controller, true);
        connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        connect.send(postData);
    }
}//cobertura_representante
function cobertura_representante()
{
    var controller = __AJAX__ + "muestramedica-cobertura_representante",
        connect, postData;

    var fecha__mm = $("#fecha__mm").val();

    if (fecha__mm.length == 0) {
        swal("Error", "Ingrese una fecha", "error");
        return false;
    }
    postData = "fecha__mm=" + fecha__mm;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            // empty_div();
            $('#div-listado_mm').html(connect.responseText);
            sum_columns_cobertura_visitados2();
            
            var calculo = (($("#tfooter14").text()*100)/$("#tfooter13").text());
            $('#avance-cobertura').html(circle(calculo.toFixed(0)) + calculo.toFixed(0)+'%');//$("#tfooter15").html()

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function circle(nro)
{
    var circle = '';

    if (nro >= 90) {
        circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>&nbsp;';
    } else if (nro >= 80) {
        circle = '<span style="color:#F1E290;"><i class="fa fa-circle"></i></span>&nbsp;';
    } else {
        circle = '<span style="color:#C70039;"><i class="fa fa-circle"></i></span>&nbsp;';
    }

    return circle;
}
function sum_columns_cobertura_visitados()
{
    var aap = 0;
    var aar = 0;
    var aac = 0;

    var ap = 0;
    var ar = 0;
    var ac = 0;

    var bp = 0;
    var br = 0;
    var bc = 0;

    var cp = 0;
    var cr = 0;
    var cc = 0;

    var totalp = 0;
    var totalr = 0;
    var totalcobrado = 0;
    var pendiente = 0;

    var aacn = 0;
    var acn = 0;
    var bcn = 0;
    var ccn = 0;
    var tcn = 0;

    $('#tablecobertura tbody tr td:nth-child(2)').each(function() {
        aap += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(3)').each(function() {
        aar += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(4)').each(function() {
        aac += parseInt($(this).text()) || 0;
        aacn++;
    });

    $('#tablecobertura tbody tr td:nth-child(5)').each(function() {
        ap += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(6)').each(function() {
        ar += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(7)').each(function() {
        ac += parseInt($(this).text()) || 0;
        acn++;
    });

    $('#tablecobertura tbody tr td:nth-child(8)').each(function() {
        bp += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(9)').each(function() {
        br += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(10)').each(function() {
        bc += parseInt($(this).text()) || 0;
        bcn++;
    });

    $('#tablecobertura tbody tr td:nth-child(11)').each(function() {
        cp += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(12)').each(function() {
        cr += parseInt($(this).text()) || 0;
    });


    $('#tablecobertura tbody tr td:nth-child(13)').each(function() {
        cc += parseInt($(this).text()) || 0;
        ccn++;
    });

    $('#tablecobertura tbody tr td:nth-child(14)').each(function() {
        totalp += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(15)').each(function() {
        totalr += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(16)').each(function() {
        totalcobrado += parseInt($(this).text()) || 0;
        tcn++;
    });

    $('#tablecobertura tbody tr td:nth-child(17)').each(function() {
        pendiente += parseInt($(this).text()) || 0;

    });

    var paac = float_rounded_0((aar * 100) / aap); // Math.round(aac / aacn);
    var pac = float_rounded_0((ar * 100) / ap); //Math.round(ac / acn);
    var pbc = float_rounded_0((br * 100) / bp); //Math.round(bc / bcn);
    var pcc = float_rounded_0((cr * 100) / cp); //Math.round(cc / ccn);
    var ptcn = float_rounded_0((totalr * 100) / totalp); //Math.round(totalcobrado / tcn);

    $("#tfooter1").html(aap);
    $("#tfooter2").html(aar);


    $("#tfooter3").html(circle(paac) + paac + ' %');

    $("#tfooter4").html(ap);
    $("#tfooter5").html(ar);
    $("#tfooter6").html(circle(pac) + pac + ' %');

    $("#tfooter7").html(bp);
    $("#tfooter8").html(br);
    $("#tfooter9").html(circle(pbc) + pbc + ' %');

    $("#tfooter10").html(cp);
    $("#tfooter11").html(cr);
    $("#tfooter12").html(circle(pcc) + pcc + ' %');

    $("#tfooter13").html(totalp);
    $("#tfooter14").html(totalr);
    $("#tfooter15").html(circle(ptcn) + ptcn + ' %');

    $("#tfooter16").html(pendiente);

}
function sum_columns_cobertura_visitados2()
{
    var aap = 0;
    var aar = 0;

    var ap = 0;
    var ar = 0;

    var bp = 0;
    var br = 0;

    var cp = 0;
    var cr = 0;

    var totalp = 0;
    var totalr = 0;

    var pendiente = 0;

    var aacn = 0;
    var acn = 0;
    var bcn = 0;
    var ccn = 0;
    var tcn = 0;

    $('#tablecobertura tbody tr td:nth-child(2)').each(function() {
        aap += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(3)').each(function() {
        aar += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(4)').each(function() {
        ap += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(5)').each(function() {
        ar += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(6)').each(function() {
        bp += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(7)').each(function() {
        br += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(8)').each(function() {
        cp += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(9)').each(function() {
        cr += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(10)').each(function() {
        totalp += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(11)').each(function() {
        totalr += parseInt($(this).text()) || 0;
    });

    $('#tablecobertura tbody tr td:nth-child(12)').each(function() {
        pendiente += parseInt($(this).text()) || 0;

    });

    var paac = float_rounded_0((aar * 100) / aap); // Math.round(aac / aacn);
    var pac = float_rounded_0((ar * 100) / ap); //Math.round(ac / acn);
    var pbc = float_rounded_0((br * 100) / bp); //Math.round(bc / bcn);
    var pcc = float_rounded_0((cr * 100) / cp); //Math.round(cc / ccn);
    var ptcn = float_rounded_0((totalr * 100) / totalp); //Math.round(totalcobrado / tcn);

    $("#tfooter1").html(aap);
    $("#tfooter2").html(aar);

    $("#tfooter4").html(ap);
    $("#tfooter5").html(ar);

    $("#tfooter7").html(bp);
    $("#tfooter8").html(br);

    $("#tfooter10").html(cp);
    $("#tfooter11").html(cr);

    $("#tfooter13").html(totalp);
    $("#tfooter14").html(totalr);

    $("#tfooter16").html(pendiente);

}
function cobertura_no_visitados(especialidades, categorias, representantes, mes, year)
{
    var controller = __AJAX__ + "muestramedica-cobertura_no_visitados",
        connect, postData;

    postData = "representantes=" + representantes +
        "&mes=" + mes +
        "&year=" + year +
        "&especialidades=" + especialidades +
        "&categorias=" + categorias;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            $('#modal-table-medicos-no-visitados').html(connect.responseText);

            $("#espec").html(especialidades);
            $("#categ").html(categorias);

            $("#modal-cobertura-no-visitados").modal();

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function cobertura_visitados(especialidades, categorias, representantes, mes, year)
{
    var controller = __AJAX__ + "muestramedica-cobertura_visitados",
        connect, postData;

    postData = "representantes=" + representantes +
        "&mes=" + mes +
        "&year=" + year +
        "&especialidades=" + especialidades +
        "&categorias=" + categorias;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            // console.log(connect.responseText);
            $("#espec2").html(especialidades);
            $("#categ2").html(categorias);
            $('#modal-table-medicos-visitados').html(connect.responseText);
            $("#modal-cobertura-visitados").modal();

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}