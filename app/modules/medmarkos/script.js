//CODE
/*** AUTO LOADS */
//
//

listar_representantes();


//
//
/*** AUTO LOADS */

$("#formdata").submit(function(evt) {
    var formData = new FormData();
    formData.append('excel', $('#excel')[0].files[0]);
    formData.append('periodo', $("#periodoinp").val());
    formData.append('tipo', $("#tipo").val());

    $.ajax({
        url: __AJAX__+'medmarkos-load_movimiento',
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
function empty_div()
{
    $("#ficha-medica-data").empty();
    $("#cobertura-data").empty();
    $("#div-cobertura-realizada-detalle-data").empty();
}
function listar_representantes()
{
    var controller = __AJAX__+"medmarkos-listar_representantes",
        connect, postData;

    //var mes = $("#mes").val();
    //var year = $("#year").val();

    var periodo = $("#periodo").val();
    var region_src = $("#region_src").val();

    postData = "periodo=" + periodo +
        "&region_src=" + region_src;
        //console.log(postData);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $('#representantes').html(connect.responseText);
            //$('#representantes').selectpicker('refresh');
            $('#representantes').selectpicker('refresh');          

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
/**FICHA MEDICA**/
function ficha_medica() {
    var controller = __AJAX__+"medmarkos-ficha_medica",
        connect, postData;

    var representantes = $("#representantes").val();
    var periodo = $("#periodo").val();

    postData = "representantes=" + representantes +
        "&periodo=" + periodo;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            empty_div();
            $('#ficha-medica-data').html(connect.responseText);
            sum_columns_especialidad();
            sum_columns_localidad();
            graficos_x_especialidad();
            graficos_x_localidad();

            $("#numero-medicos").val($("#tftt").text());
            $("#numero-contactos").val($("#tftc").text());
            $("#representante_name").val($("#representantes :selected").text());

            $("#cant-aa").text($("#tfaa").text());
            $("#cant-a").text($("#tfa").text());
            $("#cant-b").text($("#tfb").text());
            $("#cant-c").text($("#tfc").text());


        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function sum_columns_especialidad() {
    var aa = 0;
    var a = 0;
    var b = 0;
    var c = 0;
    var total = 0;
    var contacto = 0;

    $('#table-x-especialidad tbody tr td:nth-child(2)').each(function() {
        aa += parseInt($(this).html()) || 0;
    });

    $('#table-x-especialidad tbody tr td:nth-child(3)').each(function() {
        a += parseInt($(this).html()) || 0;
    });

    $('#table-x-especialidad tbody tr td:nth-child(4)').each(function() {
        b += parseInt($(this).html()) || 0;
    });

    $('#table-x-especialidad tbody tr td:nth-child(5)').each(function() {
        c += parseInt($(this).html()) || 0;
    });

    $('#table-x-especialidad tbody tr td:nth-child(6)').each(function() {
        total += parseInt($(this).html()) || 0;
    });

    $('#table-x-especialidad tbody tr td:nth-child(7)').each(function() {
        contacto += parseInt($(this).html()) || 0;
    });

    $("#tfaa").html(aa);
    $("#tfa").html(a);
    $("#tfb").html(b);
    $("#tfc").html(c);
    $("#tftt").html(total);
    $("#tftc").html(contacto);
}
function sum_columns_localidad() {
    var aa = 0;
    var a = 0;
    var b = 0;
    var c = 0;
    var total = 0;
    var contacto = 0;

    $('#table-x-localidad tbody tr td:nth-child(2)').each(function() {
        aa += parseInt($(this).html()) || 0;
    });

    $('#table-x-localidad tbody tr td:nth-child(3)').each(function() {
        a += parseInt($(this).html()) || 0;
    });

    $('#table-x-localidad tbody tr td:nth-child(4)').each(function() {
        b += parseInt($(this).html()) || 0;
    });

    $('#table-x-localidad tbody tr td:nth-child(5)').each(function() {
        c += parseInt($(this).html()) || 0;
    });

    $('#table-x-localidad tbody tr td:nth-child(6)').each(function() {
        total += parseInt($(this).html()) || 0;
    });

    $('#table-x-localidad tbody tr td:nth-child(7)').each(function() {
        contacto += parseInt($(this).html()) || 0;
    });

    $("#tfaa_l").html(aa);
    $("#tfa_l").html(a);
    $("#tfb_l").html(b);
    $("#tfc_l").html(c);
    $("#tftt_l").html(total);
    $("#tftc_l").html(contacto);
}
function graficos_x_especialidad() {
    var totalsuma = $('#tftt').html();
    var especialidad = [];
    var cantidad = [];

    $('#table-x-especialidad tbody tr td:nth-child(1)').each(function() { especialidad.push($(this).html() || 'vacio'); });
    $('#table-x-especialidad tbody tr td:nth-child(6)').each(function() { cantidad.push($(this).html() * 100 / totalsuma); });

    var series = [];

    for (var i = 0; i < cantidad.length; i++) {
        series.push([especialidad[i], cantidad[i]]);
    }

    var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: '(%) Especialidad'
        },
        tooltip: {
            pointFormat: 'Cantidad : <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                showInLegend: true,
            }
        },
        series: [{ data: series }]
    };

    var charts = new Highcharts.chart('grafico_especialidad', options);

}
function graficos_x_localidad() {
    var totalsuma = $('#tftc_l').html();
    var localidad = [];
    var cantidad = [];

    $('#table-x-localidad tbody tr td:nth-child(1)').each(function() { localidad.push($(this).html() || 'vacio'); });
    $('#table-x-localidad tbody tr td:nth-child(7)').each(function() { cantidad.push(parseInt($(this).html() || 0)); });

    var chart = Highcharts.chart('grafico_localidad', {
        chart: {
            inverted: true
        },
        title: {
            text: 'Contactos x Localidad'
        },

        subtitle: {
            text: ''
        },

        xAxis: {
            categories: localidad
        },

        series: [{
            type: 'column',
            colorByPoint: true,
            data: cantidad,
            showInLegend: false
        }]
    });

}
/**FICHA MEDICA**/
/**COBERTURA**/
function cobertura()
{
    var controller = __AJAX__+"medmarkos-cobertura",
        connect, postData;

    var representantes = $("#representantes").val();
    var periodo = $("#periodo").val();

    postData = "representantes=" + representantes +
        "&periodo=" + periodo;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            empty_div();
            $('#cobertura-data').html(connect.responseText);
            sum_columns_cobertura_visitados();
            $('#avance-cobertura').html($("#tfooter15").html());
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function cobertura_no_visitados(especialidades, categorias, representantes, mes, year)
{
    var controller = __AJAX__+"medmarkos-cobertura_no_visitados",
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
    var controller = __AJAX__+"medmarkos-cobertura_visitados",
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
function circle(nro)
{
    var circle = '';

    if (nro >= 75) {
        circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>&nbsp;';
    } else if (nro >= 60) {
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
function cobertura_fecha_productos(codigo_medico, fechas_visitadas, mes, year, representantes, medico)
{
    var controller = __AJAX__+"medmarkos-cobertura_fecha_productos",
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
/**COBERTURA**/
function table_realizadas_detalle() {
    var controller = __AJAX__+"medmarkos-table_realizadas_detalle",
        connect, postData;

    var representantes = $("#representantes").val();
    var periodo = $("#periodo").val();

    postData = "periodo=" + periodo + "&representantes=" + representantes;

    // console.log(postData);

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            empty_div();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                $("#div-cobertura-realizada-detalle-data").html(connect.responseText);

                var table = $("#table-detalle-realizadas-data").DataTable({
                    lengthChange: false,
                    buttons: [{
                        extend: 'excel',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel',
                        className: 'btn btn-default waves-effect waves-light'
                    }],
                    iDisplayLength: 10,
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false
                });
                table.buttons().container().appendTo('#table-detalle-realizadas-data_wrapper .col-md-6:eq(0)');

                // var table = $('#table-detalle-realizadas-data').DataTable( {
                //     scrollY:        "300px",
                //     scrollX:        true,
                //     scrollCollapse: true,
                //     paging:         false,
                //     fixedColumns:   {
                //         leftColumns: 1,
                //         rightColumns: 1
                //     }
                // } );
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function table_medicos_x_dia() {
    var controller = __AJAX__+"medmarkos-table_medicos_x_dia",
        connect, postData;

    // var representantes = $("#representantes").val();
    
    var region = $("#region_src").val();
    var periodo = $("#periodo").val();

    postData = "periodo=" + periodo + "&region=" + region;
        //"&representantes=" + representantes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            empty_div();          

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                $("#div-cobertura-realizada-detalle-data").empty();
                $("#div-cobertura-realizada-detalle-data").html(connect.responseText);
                
                var table = $("#table_medicos_x_dia").DataTable({
                    lengthChange: false,
                    buttons: [
                        // {
                        //     extend: 'print',
                        //     text: '<span class="fa fa-print"></span>',
                        //     className: 'btn btn-inverse btn-custom waves-effect waves-light'
                        // },
                        {
                            extend: 'excel',
                            text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel',
                            className: 'btn btn-default waves-effect waves-light'
                        }
                        // ,
                        // {
                        //     extend: 'pdf',
                        //     text: '<span class="fa fa-file-pdf-o"></span>',
                        //     className: 'btn btn-primary btn-custom waves-effect waves-light'
                        // }
                    ],
                    // responsive: {
                    //     breakpoints: [
                    //         { name: 'desktop', width: Infinity },
                    //         { name: 'tablet',  width: 1024 },
                    //         { name: 'fablet',  width: 768 },
                    //         { name: 'phone',   width: 480 }
                    //     ]
                    // },
                    bLengthChange: false,
                    pageLength: -1,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: true,
                    // sScrollY: true
                    
                });
                table.buttons().container().appendTo('#table_medicos_x_dia_wrapper .col-md-6:eq(0)');


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
    var controller = __AJAX__+"medmarkos-cobertura_visitados_detalle",
        connect, postData;

    postData = "representantes=" + representantes +
        "&fecha=" + fecha;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            // console.log(connect.responseText);
            // $("#espec2").html(especialidades);
            // $("#categ2").html(categorias);
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
function table_stock_count()
{
    var controller = __AJAX__+"medmarkos-table_stock_count",
        connect, postData;

    var representantes = $("#representantes").val();
    var name_representante = $("#representantes option:selected").text();
    $("#name_repre").val(name_representante);
    
    var periodo = $("#periodo").val();

    postData = "periodo=" + periodo + "&representantes=" + representantes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            empty_div();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                $("#div-cobertura-realizada-detalle-data").html(connect.responseText);

                var table = $("#table-stock-productos").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    buttons: [{
                        extend: 'excel',
                        title: $("#name_repre").val(),
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel',
                        className: 'btn btn-default waves-effect waves-light',
                        messageTop: 'HOLA WORLD'

                    }],

                    // responsive: {
                    //     breakpoints: [
                    //         { name: 'desktop', width: Infinity },
                    //         { name: 'tablet',  width: 1024 },
                    //         { name: 'fablet',  width: 768 },
                    //         { name: 'phone',   width: 480 }
                    //     ]
                    // },
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    order: [
                        [$('th.this_order').index(), 'desc']
                    ]
                });
                table.buttons().container().appendTo('#table-stock-productos_wrapper .col-md-6:eq(0)');

            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function table_stock_count_all() {
    var controller = __AJAX__+"medmarkos-table_stock_count_all",
        connect, postData;

    var periodo = $("#periodo").val();

    postData = "periodo=" + periodo;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            empty_div();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                $("#div-cobertura-realizada-detalle-data").html(connect.responseText);

                var table = $("#table_stock_count_all").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    buttons: [{
                        extend: 'excel',
                        title: $("#name_repre").val(),
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel',
                        className: 'btn btn-default waves-effect waves-light',
                    }],

                    // responsive: {
                    //     breakpoints: [
                    //         { name: 'desktop', width: Infinity },
                    //         { name: 'tablet',  width: 1024 },
                    //         { name: 'fablet',  width: 768 },
                    //         { name: 'phone',   width: 480 }
                    //     ]
                    // },
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    order: [
                        [$('th.this_order').index(), 'desc']
                    ]
                });
                table.buttons().container().appendTo('#table_stock_count_all_wrapper .col-md-6:eq(0)');
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function view_medicos_x_producto(repre_cod, fecha, prod_cod, prod_name) {
    var controller = __AJAX__+"medmarkos-view_medicos_x_producto",
        connect, postData;

    postData = 'repre_cod=' + repre_cod +
        '&fecha=' + fecha +
        '&prod_cod=' + prod_cod;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                $("#prod_name_modal").text(prod_name);

                $("#tbl_modal_data_medicos_x_productos").html(connect.responseText);
                $("#modal-medicos-x-producto").modal();
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function propagandistas_muestras() {
    var controller = __AJAX__+"medmarkos-propagandistas_muestras",
        connect, postData;

    var periodo = $("#periodo").val();
    var region_src = $("#region_src").val();

    postData = "periodo=" + periodo + "&region_src=" + region_src;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            empty_div();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {

                $("#div-cobertura-realizada-detalle-data").html(connect.responseText);

                var selected = [];
                var table = $("#table-propagandistas_muestras").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    buttons: [{
                        extend: 'excel',
                        title: 'Representantes visitas',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columnDefs: [ 
                        { orderable: false, targets: [0] } ],
                    responsive: false,
                    iDisplayLength: 20,
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    rowCallback: function( row, data ) {
                        if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                            $(row).addClass('selected');
                        }
                    },
                    order: [
                        [$('th.this_order').index(), 'desc']
                    ]
                });

                table.buttons().container().appendTo('#table-propagandistas_muestras_wrapper .col-md-6:eq(0)');

                $("#table-propagandistas_muestras_filter").addClass("pull-left");
                $("#table-propagandistas_muestras_paginate").addClass("pull-left");
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function medicos_productos_cantidad_periodo() {
    var controller = __AJAX__+"medmarkos-medicos_productos_cantidad_periodo",
        connect, postData;


    var periodo = $("#periodo").val();
    var representantes = $("#representantes").val();

    postData = "periodo=" + periodo + "&representantes=" + representantes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            empty_div();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                var info = connect.responseText;

                var info_explo = info.split('||');

                $("#div-cobertura-realizada-detalle-data").html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var columnsum = 8;

                var table = $("#table_medicos_productos_cantidad_periodo").DataTable({
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'medicos_productos_cantidad_periodo',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columns: [
                        { "data": "med_cod" },
                        { "data": "med_name" },
                        { "data": "med_esp" },
                        { "data": "med_cat" },
                        { "data": "med_loc" },
                        { "data": "med_dir" },
                        { "data": "prod_cod" },
                        { "data": "prod_name" },
                        { "data": "cantidad" }
                    ],
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(columnsum)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(columnsum, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(columnsum).footer()).html('<span style="font-size:1.02em;"><b>Cuadro: ' + number_format_(pageTotal) + '<br> Total: ' + number_format_(total) + '</b></span>');
                    },
                    responsive: true,
                    aLengthMenu: [
                        [25, 50, 100, 200, 250, -1],
                        [25, 50, 100, 200, 250, "Todo"]
                    ],
                    iDisplayLength: 25,
                    bLengthChange: true, //show rows
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    bScrollInfinite: true,
                    bScrollCollapse: true,
                    sScrollY: "480px",
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    },
                    order: [
                        [$('th.this_order').index(), 'asc']
                    ]
                });
                table.buttons().container().appendTo('#table_medicos_productos_cantidad_periodo_wrapper .col-md-6:eq(0)');

            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function stock_entregado_resumen() {
    var controller = __AJAX__+"medmarkos-stock_entregado_resumen",
        connect, postData;


    var periodo = $("#periodo").val();
    var representantes = $("#representantes").val();

    postData = "periodo=" + periodo +"&representantes=" + representantes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            empty_div();

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                var info = connect.responseText;

                var info_explo = info.split('||');

                $("#div-cobertura-realizada-detalle-data").html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var columnsum = 5;

                var table = $("#table_stock_entregado_resumen_").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'stock_entregado_resumen',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columns: [
                        { "data": "cod_repre" },
                        { "data": "cod_prod" },
                        { "data": "nom_prod", "className": "text-left" },
                        { "data": "cantidad" },
                        { "data": "entregados" },
                        { "data": "stock_actual" }
                    ],
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        total1 = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(3).footer()).html('<span style="font-size:1.02em;"><b>' + number_format_(total1) + '</b></span>');
                        total2 = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(4).footer()).html('<span style="font-size:1.02em;"><b>' + number_format_(total2) + '</b></span>');
                        total3 = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(5).footer()).html('<span style="font-size:1.02em;"><b>' + number_format_(total3) + '</b></span>');
                    },
                    responsive: true,
                    iDisplayLength: 10,
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    order: [
                        [$('th.this_order').index(), 'desc']
                    ]
                });
                table.buttons().container().appendTo('#table_stock_entregado_resumen__wrapper .col-md-6:eq(0)');
                $("#table_stock_entregado_resumen__filter").addClass("pull-left");
                $("#table_stock_entregado_resumen__paginate").addClass("pull-left");
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function padron_medico_x_represesntante(rep_cod, repre_name) {
    var controller = __AJAX__+"medmarkos-padron_medico_x_represesntante",
        connect, postData;

    var representantes = rep_cod;

    postData = "repre="+representantes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            // empty_div();
            $('html, body').animate({ scrollTop:  $("#div-detalle-data-maestro-medico").position().top }, 'slow');

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                var info = connect.responseText;

                var info_explo = info.split('|~|');

                $("#div-detalle-data-maestro-medico").html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var table = $("#padron-medicos_med").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Padron medico [' + repre_name + ']',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columns: [
                        { "data": "cmp"},
                        { "data": "nombre", className: "text-left"},
                        { "data": "especialidad"},
                        { "data": "categoria"},
                        { "data": "institucion" , className: "text-left"},
                        { "data": "direccion" , className: "text-left"},
                        { "data": "localidad"},
                        { "data": "alta_baja"},
                        { "data": "repre"},
                        { "data": "superv"},
                        { "data": "zona"},
                        { "data": "ubigeo"},
                    ],
                    responsive: true,
                    iDisplayLength: 25,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                });
                table.buttons().container().appendTo('#padron-medicos_med_wrapper .col-md-6:eq(0)');
                $("#padron-medicos_med_filter").addClass("pull-left");
                $("#padron-medicos_med_paginate").addClass("pull-left");

            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function medicos_visitados_x_dia(zonag, representantes, year, mes)
{
    var controller = __AJAX__+"medmarkos-medicos_visitados_x_dia",
        connect, postData;

    postData = "zonag="+zonag +"&repre="+representantes +"&year="+year +"&mes="+mes;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) 
        {
            $("#loader").empty();
            
            $('html, body').animate({ scrollTop:  $("#div-detalle-data-maestro-medico").position().top }, 'slow');

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                var respose = connect.responseText;

                $("#div-detalle-data-maestro-medico").html(respose);

                var table = $("#table_medicos_vistados_x_dia").DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Medicos visitados por dia',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    lengthChange: false,
                    bLengthChange: false,
                    pageLength: 20,
                    bInfo: false,
                    bAutoWidth: true,                
                    order: [
                        [$('th.order_this').index(), 'desc']
                    ]
                });
                $("#table_medicos_vistados_x_dia_filter").addClass('pull-left');
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function listar_padron_medicos(representantes, categorias, var_data, tipo)
{
    var controller = __AJAX__+"medmarkos-listar_padron_medicos",
        connect, postData;

    postData = "representantes=" + representantes +
        "&categorias=" + categorias +
        "&var_data=" + var_data +
        "&tipo=" + tipo;



    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            // empty_div();

            var result = connect.responseText;
            var split_response = result.split('|~|');

            var core_table = split_response[0];
            var json_data = split_response[1];

            // console.log(connect.responseText);

            if (parseInt(core_table) == 0) //error
            {
                swal("Error", core_table, "error");
            } else {

                $('html, body').animate({ scrollTop: $("#div-container-3").position().top }, 'slow');

                $("#div-container-3").html(core_table);

                var json_data = JSON.parse(json_data);

                var table = $("#table-padron-medicos").DataTable({
                    dom: "Bfrtip",
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Padron medicos',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columns: [
                        { "data": "cmp" },
                        { "data": "nombre" },
                        { "data": "especialidad" },
                        { "data": "categoria" },
                        { "data": "institucion" },
                        { "data": "localidad" },
                        { "data": "direccion" }
                    ],
                    responsive: true,
                    iDisplayLength: 20,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false
                });
                table.buttons().container().appendTo('#table-padron-medicos_wrapper .col-md-6:eq(0)');
                $("#table-padron-medicos_filter").addClass('pull-left');
                $("#table-padron-medicos_paginate").addClass('pull-left');
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
