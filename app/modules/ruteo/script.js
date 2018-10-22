//CODE

// $("#master_check").click(function () {
//    alert(1); 
// });



$('[id^=cliente_ruc]').keypress(validateNumber);
jQuery('#fecha-consulta').datepicker({orientation: 'auto top', toggleActive: true, autoclose: true, format: "dd/mm/yyyy", todayHighlight: true});
var content_div = $("#_div-container_");
/**
 * FUNCTIONS INIT_
 */
$('#btn_new').click(function(){__call_events_container_("new");_timepicker_hora();});
change_event();
/**
 * FUNCTIONS INIT_
 */
function _timepicker_hora()
{
    jQuery("input[name='horas_insertar[]']").timepicker({ position:'left', float:'bottom', showMeridian:false, minuteStep: 30});
}
function change_event()
{
    var value = $('#select_event_change').val();
    $('#btn_events').removeAttr('onclick').attr('onclick', 'return ' + value + ';');
}
function close_modal(target)
{
    //$("#" + target).modal('hide');
    //$("#" + target).modal('hide');
    $("#" + target).hide();
}
function _objetivos_(item)
{
    var options;

    arrayName = ['vs||Visita','co||Cobranza','vn||Venta'];

    for (let i = 0; i < arrayName.length; i++)
    { 
        var separa = arrayName[i].split("||");
        
        var value = separa[0];
        var name = separa[1];

        if(item == value)
        {
            options += '<option value="' + value + '" selected><label class="align-middle">' + name + '</label></option>';
        }else
        {
            options += '<option value="' + value + '"><label class="align-middle">' + name + '</label></option>';
        }
    }
    return options;
}
function __call_new_form()
{
    var html;
    
    html = '\
    <div class="text-left">\
    <button class="btn btn-primary waves-effect waves-light btn-sm" id="btn_save" onclick="_insertar_ruteo();">\
    <span class="fa fa-save"></span>&nbsp; Guardar</button>\
    </div><hr>\
    <div class="text-left">\
    <button class="btn btn-default waves-effect waves-light btn-sm" id="btn_add" onclick="return newElement(event, 2, ' + "'div'" + ', ' + "'insertar'" + ');">\
    <span  class="fa fa-plus"></span></button>&nbsp;&nbsp;<label for="btn_add" style="color:black;">Agregar</label>\
    </div>\
    <div class="row" id="parent-div_insertar">\
    <div class="col-lg-6" id="div1">\
    <table class="table table-condensed table-sm">\
    <thead>\
    <tr>\
    <th colspan="2" style="width:auto;"></th>\
    <th style="width:80%;"></th>\
    </tr>\
    </thead>\
    <tbody>\
    <tr>\
    <td class="bg-primary input-sm text-white font-weight-bold border-primary" style="border-radius:0px;">\
    <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="elementRemove(' + "'div1'" + ');">\
    <span class="fa fa-minus"></span></button>\
    </td>\
    <td class="text-right bg-primary input-sm text-white font-weight-bold border-primary align-middle" style="border-radius:0px;">HORA&nbsp;&nbsp;\
    </td>\
    <td>\
    <div class="input-group">\
    <input id="hora_1" name="horas_insertar[]" type="text" class="form-control input-sm text-center border border-secondary">\
    <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect">\
    <i class="md md-access-time"></i>\
    </span>\
    </div>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm">CLIENTE&nbsp;&nbsp;\
    <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect"  onclick="return _buscar_modal(1);">\
    <i class="fa fa-search"></i>\
    </span>\
    </td>\
    <td>\
    <div class="input-group">\
    <input id="codigo_1" name="codigos_insertar[]" style="width:20% !important;" pattern="[0-9.]+" type="number" size="5" maxlength="11"  class="form-control input-sm text-center border border-secondary font_inside_input" onkeypress="return max_length(this.value, 10);validateNumber(event);" placeholder="Codigo">\
    <input id="cliente_1" name="clientes_insertar[]" style="width:30% !important;" type="text" readonly="true"  class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cliente">\
    <span class="input-group-addon input-sm bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__(' + "'codigo_1,cliente_1'" + ');">\
    <i class="fa fa-trash"></i>\
    </span>\
    </div>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBJETIVO&nbsp;&nbsp;\
    </td>\
    <td><select name="objetivos_insertar[]" id="objetivo_1" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">'+ _objetivos_(0) +'</select></td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">IMPORTE&nbsp;&nbsp;</td>\
    <td>\
    <input type="number" id="importe_1" name="importes_insertar[]" class="form-control input-sm border border-secondary text-center font_inside_input" onkeypress="return max_length(this.value, 7);" id="">\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBSERVACI&Oacute;N&nbsp;&nbsp;</td>\
    <td><textarea id="observacion_1" name="observaciones_insertar[]" class="input-sm border border-secondary font_inside_input" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" cols="30" rows="10"></textarea></td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">TIPO&nbsp;&nbsp;</td>\
    <td>\
    <select id="tipo_1" name="tipos_insertar[]" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">\
    <option value="s">SI</option>\
    <option value="n">NO</option>\
    </select>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="3" class="jumbotron"></td>\
    </tr>\
    </tbody>\
    </table>\
    </div>\
    </div>';

    return html;
}
var i = 2;
function newElement(e, xx, element, form)
{
    /*<button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="elementRemove(' + "'" + element + i + "'" + ');">*/
    var html;
    
    html = '\
    <div class="col-lg-6" id="' + element + i + '">\
    <table class="table table-condensed table-sm">\
    <thead>\
    <tr>\
    <th colspan="2" style="width:auto;"></th>\
    <th style="width:80%;"></th>\
    </tr>\
    </thead>\
    <tbody>\
    <tr>\
    <td class="bg-primary input-sm text-white font-weight-bold border-primary " style="border-radius:0px;">\
    <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="elementRemove(' + "'" + element + i + "'" + ');">\
    <span class="fa fa-minus"></span></button>\
    </td>\
    <td class="text-right bg-primary input-sm text-white font-weight-bold border-primary align-middle" style="border-radius:0px;">HORA&nbsp;&nbsp;\
    </td>\
    <td>\
    <div class="input-group">\
    <input id="hora_'+ i +'" name="horas_insertar[]" type="text" class="form-control input-sm text-center border border-secondary">\
    <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect">\
    <i class="md md-access-time"></i>\
    </span>\
    </div>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm">CLIENTE&nbsp;&nbsp;\
    <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect" onclick="return _buscar_modal(' + i + ');">\
    <i class="fa fa-search"></i>\
    </span>\
    </td>\
    <td>\
    <div class="input-group">\
    <input id="codigo_'+ i +'" name="codigos_insertar[]" style="width:20% !important;" pattern="[0-9.]+" type="number" size="5" maxlength="11"  class="form-control input-sm text-center border border-secondary font_inside_input" onkeypress="return max_length(this.value, 10);validateNumber(event);" placeholder="Codigo">\
    <input id="cliente_'+ i +'" name="clientes_insertar[]" style="width:30% !important;" type="text" readonly="true"  class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cliente">\
    <span class="input-group-addon input-sm bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__(' + "'codigo_" + i + ",cliente_" + i + "'" + ');">\
    <i class="fa fa-trash"></i>\
    </span>\
    </div>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBJETIVO&nbsp;&nbsp;\
    </td>\
    <td><select name="objetivos_insertar[]" id="objetivo_'+ i +'" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">'+ _objetivos_(0) +'</select></td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">IMPORTE&nbsp;&nbsp;</td>\
    <td>\
    <input type="number" id="importe_'+ i +'" name="importes_insertar[]" class="form-control input-sm border border-secondary text-center font_inside_input" onkeypress="return max_length(this.value, 7);" id="">\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBSERVACI&Oacute;N&nbsp;&nbsp;</td>\
    <td><textarea id="observacion_'+ i +'" name="observaciones_insertar[]" class="input-sm border border-secondary font_inside_input" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" cols="30" rows="10"></textarea></td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">TIPO&nbsp;&nbsp;</td>\
    <td>\
    <select id="tipo_'+ i +'" name="tipos_insertar[]" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">\
    <option value="s">SI</option>\
    <option value="n">NO</option>\
    </select>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="3" class="jumbotron"></td>\
    </tr>\
    </tbody>\
    </table>\
    </div>';

    i++;
    $("#parent-div_" + form).prepend( html );
    _timepicker_hora();
}
var d = 9999;
function newElement2(e, xx, element, form)
{
    /*<button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="elementRemove(' + "'" + element + i + "'" + ');">*/
    var html;
    
    html = '\
    <div class="col-lg-6" id="' + element + d + '">\
    <table class="table table-condensed table-sm">\
    <thead>\
    <tr>\
    <th colspan="2" style="width:auto;"></th>\
    <th style="width:80%;"></th>\
    </tr>\
    </thead>\
    <tbody>\
    <tr>\
    <td class="bg-primary input-sm text-white font-weight-bold border-primary " style="border-radius:0px;">\
    <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="elementRemove(' + "'" + element + d + "'" + ');">\
    <span class="fa fa-minus"></span></button>\
    </td>\
    <td class="text-right bg-primary input-sm text-white font-weight-bold border-primary align-middle" style="border-radius:0px;">HORA&nbsp;&nbsp;\
    </td>\
    <td>\
    <div class="input-group">\
    <input id="hora_' + d + '" name="horas_insertar[]" type="text" class="form-control input-sm text-center border border-secondary">\
    <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect">\
    <i class="md md-access-time"></i>\
    </span>\
    </div>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm">CLIENTE&nbsp;&nbsp;\
    <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect"  onclick="return _buscar_modal(' + d + ');">\
    <i class="fa fa-search"></i>\
    </span>\
    </td>\
    <td>\
    <div class="input-group">\
    <input id="codigo_' + d + '" name="codigos_insertar[]" style="width:20% !important;" pattern="[0-9.]+" type="number" size="5" maxlength="11"  class="form-control input-sm text-center border border-secondary font_inside_input" onkeypress="return max_length(this.value, 10);validateNumber(event);" placeholder="Codigo">\
    <input id="cliente_' + d + '" name="clientes_insertar[]" style="width:30% !important;" type="text" readonly="true"  class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cliente">\
    <span class="input-group-addon input-sm bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__(' + "'codigo_" + d + ",cliente_" + d + "'" + ');">\
    <i class="fa fa-trash"></i>\
    </span>\
    </div>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBJETIVO&nbsp;&nbsp;\
    </td>\
    <td><select name="objetivos_insertar[]" id="objetivo_' + d + '" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">'+ _objetivos_(0) +'</select></td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">IMPORTE&nbsp;&nbsp;</td>\
    <td>\
    <input type="number" id="importe_' + d + '" name="importes_insertar[]" class="form-control input-sm border border-secondary text-center font_inside_input" onkeypress="return max_length(this.value, 7);" id="">\
    </td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBSERVACI&Oacute;N&nbsp;&nbsp;</td>\
    <td><textarea id="observacion_' + d + '" name="observaciones_insertar[]" class="input-sm border border-secondary font_inside_input" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" cols="30" rows="10"></textarea></td>\
    </tr>\
    <tr>\
    <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">TIPO&nbsp;&nbsp;</td>\
    <td>\
    <select id="tipo_' + d + '" name="tipos_insertar[]" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">\
    <option value="s">SI</option>\
    <option value="n">NO</option>\
    </select>\
    </td>\
    </tr>\
    <tr>\
    <td colspan="3" class="jumbotron"></td>\
    </tr>\
    </tbody>\
    </table>\
    </div>';

    d++;
    $("#parent-div_" + form).prepend( html );
    _timepicker_hora();
}
function elementRemove(id)
{
    $("#" + id).remove();
}
function __call_events_container_(expression)
{
    content_div.empty();

    switch(expression) {
        case "new":
            content_div.html(__call_new_form);
            break;
        case "edit":
            alert("Edit");
            break;
        default:
            console.log("default");
    }
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
function _buscar_cliente(element_id)
{
    var controller = __AJAX__ + "ruteo-_buscar_cliente", postData = new FormData();
    
    var codigo = element_id;//$("#codigo_" + ).val();
    var fecha = $("#fecha-consulta").val();

    postData.append("codigo", codigo);
    postData.append("fecha", fecha);
    
    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: true,
        contentType: false,
        enctype: __POST__,
        processData: false,
        beforeSend: function(){$("#div_modal_body").html(div_loader());},
        complete: function(){},
        success: function(response)
        {
            //$("#div_modal_body").html(response);
            check = response.split('||');

            if (parseInt(check[0]) == 1)
            {
                var datos = check[1].split('~~');

                $("#codigo_" + element_id).val(datos[0]);
                $("#cliente_" + element_id).val(datos[1]);
            }else
            {
                $("#div_modal_body").html(check[1]);
                var table = $("#table_listado_clientes").DataTable({
                    dom: "frtip",
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
                $("#table_listado_clientes_filter").addClass("pull-left").css("color", "black");
                _input_search_color();
                $("#table_listado_clientes_paging").addClass("pull-left"); 
                $(".pagination").css("color", "black");
            }
        }
    });
}
function _buscar_medico(element_id)
{
    var controller = __AJAX__ + "ruteo-_buscar_medico", postData = new FormData();

    var codigo = element_id;
    var fecha = $("#fecha-consulta").val();

    postData.append("codigo", codigo);
    postData.append("fecha", fecha);

    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: true,
        contentType: false,
        enctype: __POST__,
        processData: false,
        beforeSend: function(){$("#div_modal_body").html(div_loader());},
        complete: function(){},
        success: function(response)
        {
            var check = response.split('||');

            if (parseInt(check[0]) == 1)
            {
                var datos = check[1].split('~~');

                $("#codigo_" + element_id).val(datos[0]);
                $("#cliente_" + element_id).val(datos[1]);
            }else
            {
                $("#div_modal_body").html(check[1]);

                var table = $("#table_listado_medicos").DataTable({
                    dom: "frtip",
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
                $("#table_listado_medicos_filter").addClass("pull-left").css("color", "black");
                _input_search_color();
                $("#table_listado_medicos_paging").addClass("pull-left"); 
                $(".pagination").css("color", "black");
           }
        }
    });
}
function _buscar_modal(element_id)
{
    var controller = __AJAX__ + "ruteo-_buscar_modal", postData = new FormData();

    postData.append("element_id", element_id);

    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: false,
        contentType: false,
        enctype: __POST__,
        processData: false,
        beforeSend: function(){},
        complete: function(){},
        success: function(response)
        {
            $("#contenido_modal1").html(response);
            $("#modal-events-mm").modal('show');
        }
    });

}
function _insertar_ruteo()
{
    var controller = __AJAX__ + "ruteo-_insertar_ruteo", postData = new FormData();

    var fecha = $("#fecha-consulta").val()
    
    //insert Array productos[]
    var horas = $("input[name='horas_insertar[]']").map(function() { return $(this).val(); }).get().join("||")    
    var codigos = $("input[name='codigos_insertar[]']").map(function() { return $(this).val(); }).get().join("||")
    var clientes = $("input[name='clientes_insertar[]']").map(function() { return $(this).val(); }).get().join("||")
    var objetivos = $("select[name='objetivos_insertar[]'] option:selected").map(function() { return $(this).val(); }).get().join("||")
    var importes = $("input[name='importes_insertar[]']").map(function() { return $(this).val(); }).get().join("||")
    var observaciones = $("textarea[name='observaciones_insertar[]']").map(function() { return $(this).val(); }).get().join("||")
    var tipos = $("select[name='tipos_insertar[]'] option:selected").map(function() { return $(this).val(); }).get().join("||")

    postData.append("fecha", fecha)
    postData.append("horas", horas)
    postData.append("codigos", codigos)
    postData.append("clientes", clientes)
    postData.append("objetivos", objetivos)
    postData.append("importes", importes)
    postData.append("observaciones", observaciones)
    postData.append("tipos", tipos)

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
                    //history.back(1);
                    //content_div.empty();
                    _listar_ruteo();
                }, function(dismiss){});
            } else{
                swal("Error", response, "error");
            }
        }
    });
}
function _buscar_ruteo()
{
    var controller = __AJAX__ + "ruteo-_buscar_ruteo", postData = new FormData();

    var fecha = $("#fecha-consulta").val();

    postData.append("fecha", fecha);
 
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
            $.when( content_div.html(response) ).then(function()
            { 
                _timepicker_hora();
            });           
        }
    });
}
function _listar_ruteo()
{
    var controller = __AJAX__ + "ruteo-_listar_ruteo", postData = new FormData();

    var fecha = $("#fecha-consulta").val();

    postData.append("fecha", fecha);

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
            content_div.html(response);

            var table = $("#table_listado_ruteos").DataTable({
                dom: "Bfrtip",
                buttons: [{
                    extend: 'excel',
                    footer: true,
                    title: 'Listado de Ruteo',
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
            $("#table_listado_ruteos_filter").addClass("pull-left").css("color", "black");
            _input_search_color();
            $("#table_listado_ruteos_paging").addClass("pull-left"); 
            $(".pagination").css("color", "black");
        }
    });
}
function _eliminar_ruteo()
{
    swal({
        title: 'Eliminar',
        text: "Seguro que desea Eliminar registro?",
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'No, cancelar',
        confirmButtonClass: 'btn btn-danger waves-light waves-effect',
        cancelButtonClass: 'btn btn-default m-l-10 waves-light waves-effect',
        buttonsStyling: true
    }).then(function()
    {
        var controller = __AJAX__ + "ruteo-_eliminar_ruteo", postData = new FormData();

        var fecha = $("#fecha-consulta").val();

        postData.append("fecha", fecha);
    
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
                if(response == 1)
                {
                    // swal("Correcto", "Eliminado correctamente", "success");
                    swal({
                        title: 'Correcto',
                        text: "Eliminado correctamente",
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'No, cancelar',
                        confirmButtonClass: 'btn btn-primary waves-light waves-effect',
                        cancelButtonClass: 'btn btn-default m-l-10 waves-light waves-effect',
                        buttonsStyling: true
                    }).then(function()
                    {
                        _listar_ruteo();
                    }, function(dismiss) {});
                }else
                {
                    swal("Error", response, "error");
                }            
            }
        });
    }, function(dismiss) {});

    
}
function _listar_ruteo_pagos()
{
    var controller = __AJAX__ + "ruteo-_listar_ruteo_pagos", postData = new FormData();

    var periodo = $("#periodo-consulta").val();
    var quincena = $("#quincena-consulta").val();

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
            content_div.html(response);

            checkbox_dependiente("#master_check", "input[name='codigos[]'");   

            var table = $("#table_listado_ruteos_pagos").DataTable({
                dom: "Bfrtip",
                buttons: [{
                    extend: 'excel',
                    footer: true,
                    title: 'Listado de Ruteo',
                    text: '<span class="fa fa-file-excel-o"></span> Excel',
                    className: 'btn btn-default waves-effect waves-light',
                },{
                    text: 'Imprimir',
                    action: function ( e, dt, node, config )
                    {
                        var _codigos_ = $("input[name='codigos[]']:checked").map(function() { return $(this).val(); }).get().join("||")

                        swal({
                            title: 'Correcto',
                            text: "Registrado correctamente",
                            type: 'success',
                            showCancelButton: true,
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar',
                            cancelButtonText: 'No cerrar',
                            confirmButtonClass: 'btn btn-primary waves-light waves-effect',
                            cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
                            buttonsStyling: true
                        }).then(function()
                        {
                            if(_codigos_.length == 0)
                            {
                                swal("Advertencia", "Seleccione almenos un casillero", "info")
                                return false
                            }
                            _print_ruteos(_codigos_);
                        }, function(dismiss){})
                    }
                }
                ],
                lengthChange: false,
                responsive: false,
                bLengthChange: false,
                destroy: true,
                bInfo: false,
                columnDefs: [
                    { orderable: false, targets: [0] }
                ],
                language:
                {
                    paginate:
                    {
                        previous: "<span class='fa fa-chevron-left'></span>",
                        next: "<span class='fa fa-chevron-right'></span>",
                    }
                },
                
                pagingType: "simple",
                order: [ [ 1,  'asc' ] ]
            });
            $("#table_listado_ruteos_pagos_filter").addClass("pull-left").css("color", "black");
            _input_search_color();
            $("#table_listado_ruteos_pagos_paging").addClass("pull-left"); 
            $(".pagination").css("color", "black");
        }
    });
}
function _print_ruteos(codigos)
{
    var controller = __AJAX__ + "ruteo-_print_ruteos", postData = new FormData();

    var data = $("#array-data").val();

    postData.append("codigos", codigos);
    postData.append("data", data);

    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: false,
        contentType: false,
        enctype: __POST__,
        processData: false,
        xhrFields: {
            responseType: 'blob'
        },
        beforeSend: function()
        {
            $("#loader").html(loader());
        },
        complete: function()
        {
            $("#loader").empty();
        },
        success: function(response, status, xhr)
        {            
            var filename = "";                   
            var disposition = xhr.getResponseHeader('Content-Disposition');

            if (disposition)
            {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            } 
            var linkelem = document.createElement('a');

            try 
            {
                var blob = new Blob([response], { type: 'application/octet-stream' });                        

                if (typeof window.navigator.msSaveBlob !== 'undefined')
                {
                    //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                    window.navigator.msSaveBlob(blob, filename);
                } else
                {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename)
                    { 
                        // use HTML5 a[download] attribute to specify filename
                        var a = document.createElement("a");
                        // safari doesn't support this yet
                        if (typeof a.download === 'undefined')
                        {
                            window.location = downloadUrl;
                        } else
                        {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.target = "_blank";
                            a.click();
                        }
                    } else 
                    {
                        window.location = downloadUrl;
                    }
                }   
            } catch (ex)
            {
                console.log(ex);
            } 
        }
    });
    

}
function _print_ruteost(codigos)
{
    var controller = __AJAX__ + "ruteo-_print_ruteos", postData = new FormData();

    var data = $("#array-data").val();
    // console.log(codigos);

    postData.append("codigos", codigos);
    postData.append("data", data);

    $.ajax({
        url: controller,
        type: 'POST',
        data: postData,
        async: true,
        cache: false,
        contentType: false,
        enctype: __POST__,
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
             content_div.html(response);
        }
    });
    

}