//CODE
/***  __INIT__  ***/
$(function()// validations
{
    //history.pushState(null, null, "Editar");
    var uri = document.URL;
    var edt_p = uri.indexOf("pedidos/editar?p=");

    if(edt_p == -1)
    {
        //console.log(edt_p);
        var get_ = getQueryVariable("p");
        //console.log("get p=" + get_);
        if(get_ != false)
        {
            _buscar_pedido(get_);       
        }
    }
});

change_event();

jQuery('#fecha, #fecha-consulta').datepicker({
    orientation: 'auto top',
    toggleActive: true,
    autoclose: true,
    format: "dd/mm/yyyy",
    todayHighlight: true
});
 
/***  __INIT__  ***/
function close_modal_mm_await()
{
    setTimeout(function() {
        location = ''
    }, 0);
}
//var i = 2;


function newElement(e, i, element, form)
{
    var html;
    html = '<tr id="' + element + i + '">\
    <td>\
    <button class="btn btn-sm btn-danger waves-effect waves-light"  onclick="elementRemove(' + "'" + element + i + "'" + ');elementRemove(' + "'2" + element + i + "'" + ');">\
    <span class="fa fa-minus"></span>\
    </button>\
    </td>\
    <td style="width:30% !important;">\
    <div class="row">\
    <div class="col-md-4">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect" onclick="return _buscar_producto(' + i + ');"><i class="fa fa-search"></i></span>\
    <input type="number" style="font-size:0.75em !important;" class="form-control input-sm text-center border border-secondary font_inside_input"  placeholder="Cod." name="prod_cod_' + form + '[]" id="prod_cod_' + i + '" onblur="precio_lista_x_prod_x_dist('+ i +');_buscar_producto('+ i +');" onchange="precio_lista_x_prod_x_dist('+ i +');">\
    </div>\
    </div>\
    <div class="col-md-4">\
    <div>\
    <span class="input-group-addon input-sm bg-inverse text-white border border-dark inp-g-ad_alter">Producto</span>\
    <textarea readonly="true" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" class="font_inside_input input-sm" id="prod_desc_' + i + '" name="prod_desc_' + form + '[]"></textarea>\
    </div>\
    </div>\
    <div class="col-md-4" >\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-dark inp-g-ad_alter">Cant.</span>\
    <input type="number" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cant." id="cant_' + i + '" name="prod_cant_' + form + '[]" onchange="precio_lista_x_prod_x_dist(' + i + ');"  onblur="precio_lista_x_prod_x_dist(' + i + ');" onkeypress="return max_length(this.value, 4);">\
    </div>\
    </div>\
    </div></td>\
    <td style="width:30% !important;">\
    <div class="row">\
    <div class="col-md-3">\
    <form class="form-inline">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Prec.<br>list.</span>\
    <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_list_' + i + '" name="prec_list_' + form + '[]">\
    </div>\
    </form>\
    </div>\
    <div class="col-md-3">\
    <form class="form-inline">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Desc.<br>%</span>\
    <input type="number" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="porc_desc_' + i + '" name="porc_desc_' + form + '[]">\
    </div>\
    </form>\
    </div>\
    <div class="col-md-3" >\
    <form class="form-inline">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Prec.<br>Uni.</span>\
    <input type="number" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_unidad_' + i + '" name="prec_unidad_' + form + '[]">\
    </div>\
    </form>\
    </div>\
    <div class="col-md-3">\
    <form class="form-inline">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">P.U.<br>+IGV</span>\
    <input type="number" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_uni_igv_' + i + '" name="prec_uni_igv_' + form + '[]">\
    </div>\
    </form>\
    </div>\
    </div></td>\
    <td style="width:30% !important;">\
    <div class="row">\
    <div class="col-md-4">\
    <form class="form-inline">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Monto<br>Desc.</span>\
    <input type="number" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="monto_desc_' + i + '" name="monto_desc_' + form + '[]">\
    </div>\
    </form>\
    </div>\
    <div class="col-md-4">\
    <form class="form-inline">\
    <div class="input-group">\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Valor<br>Neto</span>\
    <input type="number" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="valor_neto_' + i + '" name="valor_neto_' + form + '[]">\
    </div>\
    </form>\
    </div>\
    <div class="col-md-4">\
    <div>\
    <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Monto</span>\
    <input type="number" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="monto_line_total_' + i + '" name="monto_line_total_' + form + '[]">\
    </div>\
    </div>\
    </div>\
    </td>\
    </tr>\
    <tr id="2' + element + i + '">\
    <td>\
    </td>\
    <td colspan="3">\
    <input type="text" class="form-control border border-secondary" placeholder="observaciones" onkeypress="return max_length(this.value, 130);" id="obs_line_' + i + '" name="obs_line_' + form + '[]">\
    </td>\
    </tr>';

    i++;
    $("#parent-div_" + form).prepend( html );
}
function close_modal_await()
{
    swal({
        title: 'Cancelar',
        text: "Desea salir del pedido?",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Si, Cerrar',
        cancelButtonText: 'No, cerrar',
        confirmButtonClass: 'btn btn-danger waves-light waves-effect',
        cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
        buttonsStyling: false
    }).then(function() {
        __atras__();
    }, function(dismiss) {
    });
}
function change_event()
{
    var value = $('#select_event_change').val();
    $('#event_button_').removeAttr('onclick').attr('onclick', 'return ' + value + ';');
}
function close_modal(target)
{
    //$("#" + target).modal('hide');
    //$("#" + target).modal('hide');
    $("#" + target).hide();
}
function _Show_small_notification(i, msg)
{
    $("#prod_cod_" + i).notify(msg, {
        position: 'top',
        elementPosition: 'right',
        className: 'success',
        autoHide: true,
        autoHideDelay: 4000
    });
}
function elementRemove(id)
{
    $("#" + id).remove();
    sum_all_table();
}
function clear_text(id1, id2)
{
    $("#" + id1).val('');
    $("#" + id2).val('');
}
/**
 *              __FUNCTIONS__
 */
function _buscar_cliente()
{
    var controller = "../" + __AJAX__ + "pedidos-_buscar_cliente";
    var cliente_ruc = $("#cliente_ruc").val();

    if (cliente_ruc.length == 0)
    {
        //cliente_ruc = 'S';
        swal('Importante', 'Ingrese un ruc !', 'error');
        return false;
    }
    postData = "cliente_ruc=" + cliente_ruc;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200)
        {
            $("#loader").empty();

            var result = connect.responseText;
            
            if(parseInt(result) == 8)
            {
                swal('Importante', 'Ingrese un ruc !', 'error');
                return false;
            }
            
            var check = result.split('||');

            if (parseInt(check[0]) == 1)
            {
                var datos = check[1].split('~~');

                $("#cliente_ruc").val(datos[0]);
                $("#cliente_name").val(datos[1]);
            } else
            {
                $("#_contenido_data_clientes_").html(check[1]);
                var table = $("#table-listar-clientes").DataTable({
                                    dom:"frtip",
                                    lengthChange: false,
                                    responsive: false,
                                    bLengthChange: false,
                                    destroy: true,
                                    bFilter: true,
                                    bInfo: false,
                                    bSort: true,
                                    sScrollY: "330px",
                                    scrollCollapse: true,
                                    fixedColumns: true,
                                    fixedHeader: true,
                                    bScrollInfinite: true,
                                    bScrollCollapse: true,
                                    scrollX: true,
                                    paging: false,
                                    
                                    //iDisplayLength: 8,
                                    // order: [ [ 0,  'desc' ] ]
                                });
                                $("#table-listar-clientes_filter").addClass("pull-left").css("color", "black");
                                $("#table-listar-clientes_paging").addClass("pull-left");
                                $("#_modal_buscar_cliente").modal();
                // $.when($("#_modal_buscar_cliente").show().modal()).then(function() {
                //   setTimeout(function(){ 
                //     $('.sorting_asc').trigger('click');
                //     }, 200);
                // });
           }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function _buscar_vendedor()
{
    var controller = "../" + __AJAX__ + "pedidos-_buscar_vendedor"
    var cod_vend = $("#cod_vend").val();

    if (cod_vend.length == 0)
    {
        cod_vend = 'S';
    }
    postData = "cod_vend=" + cod_vend;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200)
        {
            $("#loader").empty();

            var result = connect.responseText;
            
            var check = result.split('||');

            if (parseInt(check[0]) == 1)
            {
                var datos = check[1].split('~~');

                $("#cod_vend").val(datos[0]);
                $("#name_vend").val(datos[1]);
            } else
            {
                $("#_contenido_data_vendedor_").html(check[1]);
                var table = $("#table-listar-vendedor").DataTable({
                                    dom:"frtip",
                                    lengthChange: false,
                                    responsive: false,
                                    bLengthChange: false,
                                    destroy: true,
                                    bFilter: true,
                                    bInfo: false,
                                    bSort: true,
                                    // sScrollY: "330px",
                                    // scrollCollapse: true,
                                    // fixedColumns: true,
                                    // fixedHeader: true,
                                    // bScrollInfinite: true,
                                    // bScrollCollapse: true,
                                    // scrollX: true,
                                    paging: true,
                                    
                                    //iDisplayLength: 8,
                                    // order: [ [ 0,  'desc' ] ]
                                });
                                $("#table-listar-vendedor_filter").addClass("pull-left").css("color", "black");
                                $("#table-listar-vendedor_paging").addClass("pull-left");
                                
                                $("#_modal_buscar_vendedor").modal();
                // $.when($("#_modal_buscar_vendedor").show().modal()).then(function() {
                //   setTimeout(function(){ 
                //     $('.sorting_asc').trigger('click');
                //     }, 200);
                // });
           }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function _buscar_producto(target)
{
    $("#loader").empty();

    var controller = "../"+ __AJAX__ + "pedidos-_buscar_producto",
        connect;

    var prod_cod = $("#prod_cod_" + target).val();

    if (prod_cod.length == 0) {
        prod_cod = 'S';
    }
    postData = "prod_cod=" + prod_cod + "&target=" + target;

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
                        //_Show_small_notification(target, datos[1]);
                    }
                    $("#prod_cod_" + target).val(datos[0]);

                    $("#prod_desc_" + target).val(datos[1]);

                    precio_lista_x_prod_x_dist(target);

                } else {
                    $("#_contenido_data_productos_").html(check[1]);
                    //$.fn.DataTable.ext.pager.numbers_length = 3;
                    var table = $("#table-listar-productos").DataTable({
                                    dom:"frtip",
                                    lengthChange: false,
                                    responsive: false,
                                    bLengthChange: false,
                                    destroy: true,
                                    bFilter: true,
                                    bInfo: false,
                                    bSort: true,
                                    language: {
                                        paginate:
                                        {
                                            previous: "<span class='fa fa-chevron-left'></span>",
                                            next: "<span class='fa fa-chevron-right'></span>",
                                        }
                                    },
                                    pagingType: "simple",
                                    // sScrollY: "330px",
                                    // scrollCollapse: true,
                                    // fixedColumns: true,
                                    // fixedHeader: true,
                                    // bScrollInfinite: true,
                                    // bScrollCollapse: true,
                                    // scrollX: true,
                                    //paging: false,
                                    
                                    //iDisplayLength: 8,
                                    order: [ [ 3,  'desc' ] ]
                                });
                                $("#table-listar-productos_filter").addClass("pull-left").css("color", "black");
                                $("#table-listar-productos_paging").addClass("pull-left"); 
                                $(".pagination").css("color", "black");

                                $("#_modal_buscar_productos").modal();
                // $.when($("#_modal_buscar_productos").show().modal()).then(function() {
                //   setTimeout(function(){ 
                //     $('.sorting_desc').trigger('click');
                //     }, 200);
                // });
                //$("#modal-events-mm").modal();
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
function __send_values__inter(target, targets, values, types, modal)
{
    __send_values__(targets, values, types, modal);

    $.when( __send_values__(targets, values, types, modal) ).then(function()
        { 
            precio_lista_x_prod_x_dist(target);
        });

}
function precio_lista_x_prod_x_dist(target)
{
    var controller = "../" + __AJAX__ + "pedidos-precio_lista_x_prod_x_dist";
    
    var cantidad = $("#cant_" + target).val();
    var producto = $("#prod_cod_" + target).val();
    var distribuidora = $("#distribuidora").val();
    var fecha = $("#fecha").val();

    //console.log("cantidad=" + cantidad + " -- " + "producto=" + producto);

    if (cantidad.length == 0 || producto.length == 0)
    {
        return false;
    }
    postData =  "producto=" + producto +  "&fecha=" + fecha + "&distribuidora=" + distribuidora; //"&cantidad=" + cantidad +
    //console.log(postData);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200)
        {
            //$("#loader").empty();

            var result = connect.responseText;
            //console.log(result);
            var datos = result.split("||");

            var precio_lista = datos[0];
            var descuento = datos[1];

            var p_u_sin_igv = (precio_lista - (precio_lista * (descuento / 100))).toFixed(2);
            var p_u_con_igv = (p_u_sin_igv * 1.18).toFixed(2);
            var monto_descuento = (cantidad * (precio_lista * (descuento / 100))).toFixed(2);
            var _valor_neto_ = (cantidad * (precio_lista - (precio_lista * (descuento / 100)))).toFixed(2);
            var monto_total_line = (cantidad * precio_lista).toFixed(2);
            

            $("#prec_list_" + target).val(precio_lista);
            $("#porc_desc_" + target).val(descuento);
            $("#prec_unidad_" + target).val(p_u_sin_igv);
            $("#prec_uni_igv_" + target).val(p_u_con_igv);
            $("#monto_desc_" + target).val(monto_descuento);
            $("#valor_neto_" + target).val(_valor_neto_);
            $("#monto_line_total_" + target).val(monto_total_line);

            sum_all_table();

        } else if (connect.readyState != 4)
        {
            //$("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
    
}
/**
 *     __ SUM REAL TIME __
 */
function sum_all_table()
{
    var sm_m_b = 0.00;
    var sm_desc = 0.00;
    var sm_m_i = 0.00;
    var sm_igv = 0.00;
    var sm_total = 0.00;

    $("input[name='monto_line_total_insertar[]']").each(function(){sm_m_b += +$(this).val();});
    $("input[name='porc_desc_insertar[]']").each(function(){sm_desc += +$(this).val();});
    $("input[name='valor_neto_insertar[]']").each(function(){sm_m_i += +$(this).val();});
    
    sm_igv = sm_m_i * 0.18;
    sm_total = sm_m_i + sm_igv;

    $("#sm_m_b").text(number_format_(sm_m_b.toFixed(2)));
    $("#sm_desc").text(number_format_(sm_desc.toFixed(2)));
    $("#sm_m_i").text(number_format_(sm_m_i.toFixed(2)));
    $("#sm_igv").text(number_format_(sm_igv.toFixed(2)));
    $("#sm_total").text(number_format_(sm_total.toFixed(2)));
}
/**
 *     __ SUM REAL TIME __
 */

function _insertar_pedido()
{
    var controller = "../"+ __AJAX__ + "pedidos-_insertar_pedido", postData = new FormData();

    var fecha = $("#fecha").val();
    var condicion_pago = $("#condicion_pago").val();
    var distribuidora = $("#distribuidora").val();
    var cliente_ruc = $("#cliente_ruc").val();
    var cliente_name = $("#cliente_name").val();
    var cod_vend = $("#cod_vend").val();
    var name_vend = $("#name_vend").val();
    var notas = $("#notas").val();
    var especial = 0;

    if ($('#checkbox1').is(":checked"))
    {
        especial = 1;
    }
    
    //insert Array productos[]
    var prod_cod = $("input[name='prod_cod_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prod_desc = $("textarea[name='prod_desc_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prod_cant = $("input[name='prod_cant_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prec_list = $("input[name='prec_list_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var porc_dscu = $("input[name='porc_desc_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prec_uni = $("input[name='prec_unidad_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prec_uni_igv = $("input[name='prec_uni_igv_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var monto_desc = $("input[name='monto_desc_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var valor_neto = $("input[name='valor_neto_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var monto_line_total = $("input[name='monto_line_total_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var observaciones = $("input[name='obs_line_insertar[]']").map(function() { return $(this).val(); }).get().join("||");  

    postData.append("fecha", fecha)
    postData.append("cond_pago", condicion_pago)
    postData.append("distribuidora", distribuidora)
    postData.append("ruc", cliente_ruc)
    postData.append("cli_name", cliente_name)
    postData.append("cod_vend", cod_vend)
    postData.append("name_vend", name_vend)
    postData.append("prod_cod", prod_cod)
    postData.append("prod_desc", prod_desc)
    postData.append("prod_cant", prod_cant)
    postData.append("prec_list", prec_list)
    postData.append("porc_dscu", porc_dscu)
    postData.append("prec_uni", prec_uni)
    postData.append("prec_uni_igv", prec_uni_igv)
    postData.append("monto_desc", monto_desc)
    postData.append("valor_neto", valor_neto)
    postData.append("monto_line_total", monto_line_total)
    postData.append("observaciones", observaciones)
    postData.append("notas", notas);
    postData.append("especial", especial);

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
                    text: "Registrado correctamente",
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
                    history.back(1);
                }, function(dismiss){});
            } else{
                swal("Error", response, "error");
            }
        }
    });
}
function _listar_pedidos()
{
    var controller = __AJAX__ + "pedidos-_listar_pedidos",
        connect, postData;
    var fecha = $("#fecha-consulta").val();
 
    postData = "fecha=" + fecha;
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;
            var split_response = result.split('|~|');

            var core_table = split_response[0];
            var json_data = split_response[1];

            if (parseInt(core_table) == 0) //error
            {
                $("#div-listado_mm").html('No hay registros');
            } else {

                $("#div-listado_mm").html(core_table);

                var json_data = JSON.parse(json_data);

                var table = $("#_listado_pedidos_").DataTable({
                    //dom: "Bfrtip",
                    dom: "frtip",
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Listado de pedidos',
                        text: '<span class="fa fa-file-excel-o"></span>',
                        className: 'btn btn-default waves-effect waves-light',
                        exportOptions: {
                            columns: '.print_this'
                        }
                    }],
                    columns: [
                        { "data": "id"},
                        { "data": "vendedor"},
                        { "data": "estado"},
                        { "data": "cliente_ruc"},
                        { "data": "nombre_comercial"},
                        { "data": "desc_dist"},
                        { "data": "condicion_pago"},
                        //{ "data": "codigo_producto"},
                        { "data": "desc_producto", className: 'text-left'},
                        { "data": "cantidad"},
                        { "data": "acciones"}
                    ],
                    responsive: true,
                    iDisplayLength: 25,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false,
                    language: {
                        paginate:
                        {
                            previous: "<span class='fa fa-chevron-left'></span>",
                            next: "<span class='fa fa-chevron-right'></span>",
                        }
                    },
                    pagingType: "simple"
                });
                table.buttons().container().appendTo('#_listado_pedidos__wrapper .col-md-6:eq(0)');
                $("#_listado_pedidos__filter").addClass('pull-left');
                $("#_listado_pedidos__paginate").addClass('pull-left');
                $("#_listado_pedidos_ tbody").css('font-size', '0.87em').css('color', 'black');
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function _buscar_pedido(id)
{
    var controller = "../" + __AJAX__ + "pedidos-_buscar_pedido", connect, postData;

    postData = "id=" + id;
        
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function()
    {
        if (connect.readyState == 4 && connect.status == 200)
        {
            $("#loader").empty();

            var result = connect.responseText;
            if(parseInt(result) == 404)
            {
                //__atras__();
                self.location = "../Pedidos/";
            }else
            {
                $("#core-row").html(result);
                sum_all_table();
            }
        }else if (connect.readyState != 4)
        {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function _actualizar_pedido(id)
{
    var controller = "../"+ __AJAX__ + "pedidos-_actualizar_pedido", postData = new FormData();

    var fecha = $("#fecha").val();
    var condicion_pago = $("#condicion_pago").val();
    var distribuidora = $("#distribuidora").val();
    var cliente_ruc = $("#cliente_ruc").val();
    var cliente_name = $("#cliente_name").val();
    var cod_vend = $("#cod_vend").val();
    var name_vend = $("#name_vend").val();
    var notas = $("#notas").val();
    var especial = 0;

    if ($('#checkbox1').is(":checked"))
    {
        especial = 1;
    }
    
    //insert Array productos[]
    var prod_cod = $("input[name='prod_cod_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prod_desc = $("textarea[name='prod_desc_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prod_cant = $("input[name='prod_cant_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prec_list = $("input[name='prec_list_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var porc_dscu = $("input[name='porc_desc_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prec_uni = $("input[name='prec_unidad_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var prec_uni_igv = $("input[name='prec_uni_igv_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var monto_desc = $("input[name='monto_desc_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var valor_neto = $("input[name='valor_neto_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var monto_line_total = $("input[name='monto_line_total_insertar[]']").map(function() { return $(this).val(); }).get().join("||");
    var observaciones = $("input[name='obs_line_insertar[]']").map(function() { return $(this).val(); }).get().join("||");

    postData.append("id", id)
    postData.append("fecha", fecha)
    postData.append("cond_pago", condicion_pago)
    postData.append("distribuidora", distribuidora)
    postData.append("ruc", cliente_ruc)
    postData.append("cli_name", cliente_name)
    postData.append("cod_vend", cod_vend)
    postData.append("name_vend", name_vend)
    postData.append("prod_cod", prod_cod)
    postData.append("prod_desc", prod_desc)
    postData.append("prod_cant", prod_cant)
    postData.append("prec_list", prec_list)
    postData.append("porc_dscu", porc_dscu)
    postData.append("prec_uni", prec_uni)
    postData.append("prec_uni_igv", prec_uni_igv)
    postData.append("monto_desc", monto_desc)
    postData.append("valor_neto", valor_neto)
    postData.append("monto_line_total", monto_line_total)
    postData.append("observaciones", observaciones)
    postData.append("notas", notas);
    postData.append("especial", especial);


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
                    text: "Actualizado correctamente",
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
                    history.back(1);
                }, function(dismiss) {});
            } else{
                swal("Error", response, "error");
            }
        }
    });
}
function _eliminar_pedido(id)
{
    swal({
        title: 'Eliminar',
        text: "Seguro que desea eliminar?",
        type: 'question',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-sm btn-danger waves-light waves-effect',
        cancelButtonClass: 'btn btn-sm btn-primary waves-light waves-effect',
        buttonsStyling: true
    }).then(function()
    {
        var controller = __AJAX__ + "pedidos-_eliminar_pedido", connect, postData;

        postData = "id=" + id;
            
        connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        connect.onreadystatechange = function()
        {
            if (connect.readyState == 4 && connect.status == 200)
            {
                $("#loader").empty();
    
                var result = connect.responseText;
     
                if(parseInt(result) == 1)
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
                        _listar_pedidos();
                    }, function(dismiss) {});
                }else if(parseInt(result) == 404)
                {
                    swal("Error", "No puedes eliminar este registro", "error");
                }else
                {
                    swal("Error", result, "error");
                }
            }else if (connect.readyState != 4)
            {
                $("#loader").html(loader());
            }
        }
        connect.open('POST', controller, true);
        connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        connect.send(postData);
    }, function(dismiss){});
    
}