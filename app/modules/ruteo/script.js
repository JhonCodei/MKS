//CODE

$('[id^=cliente_ruc]').keypress(validateNumber);
jQuery('#fecha-consulta').datepicker({orientation: 'auto top', toggleActive: true, autoclose: true, format: "dd/mm/yyyy", todayHighlight: true});
var content_div = $("#_div-container_");
/**
 * FUNCTIONS INIT_
 */
$('#btn_new').click(function(){__call_events_container_("new");_timepicker_hora();});
/**
 * FUNCTIONS INIT_
 */

function _timepicker_hora()
{
    jQuery("input[name='horas_insertar[]']").timepicker({ position:'left', float:'bottom', showMeridian:false, minuteStep: 30});
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
    <div class="text-left" >\
    <button class="btn btn-default waves-effect waves-light btn-sm" id="btn_add" onclick="return newElement(event, 2, ' + "'div'" + ', ' + "'insertar'" + ');">\
    <span  class="fa fa-plus"></span></button>&nbsp;&nbsp;<label for="btn_add" style="color:black;">Agregar</label>\
    </div>\
    <div class="text-left">\
    <button class="btn btn-primary waves-effect waves-light btn-sm" id="btn_save" onclick="_insertar_ruteo();">\
    <span class="fa fa-save"></span>&nbsp; Guardar</button>\
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
    <td class="bg-primary input-sm text-white font-weight-bold border-primary " style="border-radius:0px;">\
    <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="alert(0);">\
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
    <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect">\
    <i class="fa fa-search" onclick="return _buscar_cliente();"></i>\
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
function newElement(e, i, element, form)
{
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
    <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="alert(0);">\
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
    <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect">\
    <i class="fa fa-search" onclick="return _buscar_cliente();"></i>\
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
    </div>';

    i++;
    $("#parent-div_" + form).prepend( html );
    _timepicker_hora();
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
                    content_div.empty();
                }, function(dismiss){});
            } else{
                swal("Error", response, "error");
            }
        }
    });
}