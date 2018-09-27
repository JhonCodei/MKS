/**
 * 
 * 
 * 
 * 
 *  VARs DEPEND
 * 
 * 
 * 
 * 
 */
var __AJAX__ = "ajax.php?request=";

function __atras__() {
    window.history.back(1);
}

function __clear__(targets) {
    var explode = targets.split(',');

    for (let index = 0; index < explode.length; index++) {
        const element = explode[index];
        $("#" + element).val('').text('').html('');
    }
}

function __send_values__(targets, values, types, modal = null) {
    var xpl_targets = targets.split('~~');
    var xpl_values = values.split('~~');
    var xpl_types = types.split('~~');

    for (let index = 0; index < xpl_targets.length; index++) {

        const target_ = $("#" + xpl_targets[index]);
        const value_ = xpl_values[index];
        const type_ = xpl_types[index];

        if (type_ == 'val') {
            target_.val(value_);
        } else if (type_ == 'html') {
            target_.html(value_);
        } else if (type_ == 'text') {
            target_.text(value_);
        }
    }

    if(modal != null)
    {
        $("#"+modal).modal('hide');
    }
}

function periodo_now() {
    var fechahora = new Date();

    var dd = fechahora.getDate();
    var mm = fechahora.getMonth() + 1;
    var yyyy = fechahora.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }
    var periodo = yyyy + mm;
    return periodo;
}

function advertencia_JS_2(msj, periodo) {
    advertencia_JS_padron(msj);
    advertencia_JS(periodo);
}

function advertencia_JS_padron(msj) {

    $.Notification.notify("error", "top right", 'Padrón médico', msj);
}

function advertencia_JS(periodo) {

    var restante = 0;
    var fecha_completa = null;
    var fechahora = new Date();

    var dd = fechahora.getDate();
    var mm = fechahora.getMonth() + 1;
    var yyyy = fechahora.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }
    if (periodo == 1) {
        var limite_day = 25;
        var limite_month = mm;
        var limite_year = yyyy;

        restante = limite_day - dd;
        fecha_completa = limite_day + '/' + limite_month + '/' + limite_year;
    } else {
        var limite_day = 13;
        var limite_month = mm;
        var limite_year = yyyy;
        restante = limite_day - dd;
        fecha_completa = limite_day + '/' + limite_month + '/' + limite_year;
    }
    if (restante > 1) {
        restante = restante + ' días';
    } else {
        restante = restante + ' día';
    }

    $.Notification.notify("error", "top right", 'Fecha límite de "Ruteo"', "Recordarles que la fecha límite de ingreso del ruteo es el <b>" + fecha_completa + "</b>, que termina en <b>" + restante + "</b><br> <i>Si ya lo realizaste, omite este mensaje.</i>");
    // swal({
    //     title: 'Fecha límite de "Ruteo"',
    //     text: "Recordarles que la fecha límite de ingreso del ruteo termina en " + restante + "<br> <i>Si ya lo realizaste, onite este mensaje.</i>",
    //     type: 'warning',
    //     showCancelButton: false,
    //     confirmButtonText: 'Aceptar',
    //     cancelButtonText: 'No cerrar',
    //     confirmButtonClass: 'btn btn-danger waves-light waves-effect',
    //     cancelButtonClass: 'btn btn-primary m-l-10 waves-light waves-effect',
    //     buttonsStyling: false
    // }).then(function() {

    // }, function(dismiss) {

    // });

}
var spanish = "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json";

function number_format_(num) {
    var n = num.toString(),
        p = n.indexOf('.');
    return n.replace(/\d(?=(?:\d{3})+(?:\.|$))/g, function($0, i) {
        return p < 0 || i < p ? ($0 + ',') : $0;
    });
}

function float_rounded(num) {

    var returned = 0;
    // if (isFinite(num) === true) {
    //     returned = 0;
    // } else {
    //     returned = Math.round(num * 100) / 100;
    // }
    returned = Math.round(num * 100) / 100;
    if (isNaN(returned)) {
        returned = 0;
    }
    return returned;
}

function float_rounded_0(num) {

    var returned = 0;
    // if (isFinite(num) === true) {
    //     returned = 0;
    // } else {
    //     returned = Math.round(num * 100) / 100;
    // }
    returned = Math.round((num * 100) / 100);
    if (isNaN(returned)) {
        returned = 0;
    }
    return returned;
}

function loader() {
    var html = null;
    html = '<div class="page_load"><div class="container_load"><div class="loader"></div></div></div>';
    return html;

    // html = '<div class="page_load container_load"><div class="lds-ripple "><div></div><div></div></div></div>';
    // return html;
}

function close_() {
    $(".alert").css("display", "none");
}

function error_($msg) {
    var html = null;

    html = '<div class="alert"><span class="closebtn" onclick="close_();">&times;</span>';
    html += $msg + '</div>';

    return html;
}

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
    $("table thead").css('background-color', "blue").css('color', 'white');

    setInterval(function() { location.reload(); }, 1500);
}

function excelDiv(divName) {
    var fechahora = new Date();

    var dd = fechahora.getDate();
    var mm = fechahora.getMonth() + 1;
    var yyyy = fechahora.getFullYear();
    var h = fechahora.getHours();
    var i = fechahora.getMinutes();
    var s = fechahora.getSeconds();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    fechahora = mm + '-' + dd + '-' + yyyy + h + i + s;

    let file = new Blob([$('#' + divName).html()], { type: "application/vnd.ms-excel" });
    let url = URL.createObjectURL(file);
    let a = $("<a />", { href: url, download: fechahora + ".xls" }).appendTo("body").get(0).click();
}

function test_excel(divName) {
    // FooTable.get('#'+divName).toCSV(true);
    $('#' + divName).tableExport({ type: 'pdf', escape: 'false' });
}

function validate_length(str, max, min) {

    if (str.length <= max && str.length >= min) {
        return true;
    } else {
        // console.log('OE!!' + str.length + '--' + str);
        return false;
    }
}

function max_length(str, max) {
    //event onkeypress 
    if (str.length <= max) {
        return true;
    } else {
        return false;
    }
}