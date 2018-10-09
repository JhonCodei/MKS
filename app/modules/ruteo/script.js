//CODE

$('[id^=cliente_ruc]').keypress(validateNumber);

jQuery('#fecha-consulta').datepicker({
 orientation: 'auto top',
 toggleActive: true,
 autoclose: true,
 format: "dd/mm/yyyy",
 todayHighlight: true
});

jQuery("input[name='xx[]']").timepicker({
 position:'left',
 float:'bottom',
 showMeridian:false,
 minuteStep: 30
});




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