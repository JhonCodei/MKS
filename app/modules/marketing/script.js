//CODE
var view = $("#div-content-view-data");
var view2 = $("#div-content-view-data2");
var filter_view = $("#filter_view");

function empty_div() {
    view.empty();
    view2.empty();
}

function test(num) {
    var target = "body_" + num;

    //$("#"+target).css('background-color', 'red');
    $("#" + target).fadeToggle("fast");
}
//OK
function filter_resumen_ventas()
{
    var html = '<div class="col-md-4"></div>';

    html += '<div class="col-lg-3">';

    html += '<div class="form-group">';
    html += '<label for="field-1" class="control-label" style="color:#404969;">Periodo</label>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<input style="border-color:#588D9C;" type="number" onblur="list_representantes();" value="'+periodo_now()+'" id="input_periodo"  class="form-control text-center"/>';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-primary waves-effect waves-light" type="button" onclick="return resumen_ventas();">';
    html += '<span class="fa fa-search"></span> &nbsp;Buscar';
    html += '</button>';
    html += '</span>';
    html += '</div>';
    html += '</div>';

    html += '</div>';
    // html += '</div>';

    empty_div();
    filter_view.empty();
    filter_view.html(html);
}
function filter_vendedor_cliente()
{
    var html = '<div class="col-md-1"></div>';
    html += '<div class="col-md-2">';
    html += '<div class="form-group">';
    html += '<label for="field-1" class="control-label" style="color:#404969;">Periodo</label>';
    html += '<input style="border-color:#588D9C;" type="number" onblur="list_representantes();" value="'+periodo_now()+'" id="input_periodo"  class="form-control text-center"/>';
    html += '</div>';
    html += '</div>';

    html += '<div class="col-lg-3">';
    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Supervisores</label>';
    html += '<select class="form-control input-md" id="region" style="border-color:#588D9C;text-align-last:center;" onchange="return list_representantes();">';
    html += '<option value="T" selected>Todos</option>';
    html += '<option value="13">CADENAS</option>';
    html += '<option value="1">ROSA CAMARGO</option>';
    html += '<option value="99">JULIO VALDIVIA</option>';
    html += '<option value="3">VACANCE NSC</option>';
    html += '<option value="11">SIMON CUTTI</option>';
    html += '<option value="9">RICARDO PEREZ</option>';
    html += '<option value="10">FERNANDO ZEGARRA</option>';
    html += '<option value="2">JHONNY CHAVEZ</option>';
    html += '<option value="22">HUGO VALENCIA</option>';
    html += '</select>';
    html += '</div>';
    html += '</div>';

    html += '<div class="col-lg-3">';

    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Representantes</label>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<select class="form-control" id="representantes" style="border-color:#588D9C;text-align-last:center;"></select>';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-primary waves-effect waves-light" type="button" onclick="vendedor_cliente();">';
    html += '<span class="fa fa-search"></span> &nbsp;Buscar';
    html += '</button>';
    html += '</span>';
    html += '</div>';
    html += '</div>';

    html += '</div>';
    // html += '</div>';
    empty_div();
    filter_view.empty();
    filter_view.html(html);
    list_representantes();
}
function filter_producto_cliente_zona()
{
    var html = '<div class="col-md-1"></div>';
    html += '<div class="col-md-2">';
    html += '<div class="form-group">';
    html += '<label for="field-1" class="control-label" style="color:#404969;">Periodo</label>';
    html += '<input style="border-color:#588D9C;" type="number" onblur="list_productos_x_distribuidoras();" value="'+periodo_now()+'" id="input_periodo"  class="form-control text-center"/>';
    html += '</div>';
    html += '</div>';

    html += '<div class="col-lg-3">';
    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Distribuidoras</label>';
    html += '<select class="form-control input-md" id="distribuidoras_list" style="border-color:#588D9C;text-align-last:center;" onchange="return list_productos_x_distribuidoras();"></select>';
    html += '</div>';
    html += '</div>';

    html += '<br><div class="col-lg-3">';

    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Productos</label>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<select class="form-control" id="productos_list" style="border-color:#588D9C;text-align-last:center;"></select>';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-primary waves-effect waves-light" type="button" onclick="return producto_cliente_zona();">';
    html += '<span class="fa fa-search"></span> &nbsp;Buscar';
    html += '</button>';
    html += '</span>';
    html += '</div>';
    html += '</div>';

    html += '</div>';
    // html += '</div>';

    empty_div();
    filter_view.empty();
    filter_view.html(html);
    list_productos();
    list_distribuidoras();
    list_productos_x_distribuidoras();
}
function filter_productos_region()
{
    var html = '<div class="col-md-1"></div>';
    html += '<div class="col-md-2">';
    html += '<div class="form-group">';
    html += '<label for="field-1" class="control-label" style="color:#404969;">Periodo</label><br>';
    html += '<input class="form-control text-center" value="'+periodo_now()+'" id="input_periodo" style="border-color:#588D9C;" onblur="return list_productos_region();">';
    html += '</div>';
    html += '</div>';

    html += '<div class="col-lg-3">';
    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Supervisores</label>';
    html += '<select class="form-control input-md" id="region" style="border-color:#588D9C;text-align-last:center;" onchange="return list_productos_region();">';
    html += '<option value="T" selected>Todos</option>';
    html += '<option value="13">CADENAS</option>';
    html += '<option value="1">ROSA CAMARGO</option>';
    html += '<option value="99">JULIO VALDIVIA</option>';
    html += '<option value="3">VACANCE NSC</option>';
    html += '<option value="11">SIMON CUTTI</option>';
    html += '<option value="9">RICARDO PEREZ</option>';
    html += '<option value="10">FERNANDO ZEGARRA</option>';
    html += '<option value="2">JHONNY CHAVEZ</option>';
    html += '<option value="22">HUGO VALENCIA</option>';
    html += '</select>';
    html += '</div>';
    html += '</div>';

    html += '<br><div class="col-lg-3">';

    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Productos</label>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<select class="form-control" id="productos_region" style="border-color:#588D9C;text-align-last:center;"></select>';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-primary waves-effect waves-light" type="button" onclick="producto_region_periodo();">';
    html += '<span class="fa fa-search"></span> &nbsp;Buscar';
    html += '</button>';
    html += '</span>';
    html += '</div>';
    html += '</div>';

    html += '</div>';
    // html += '</div>';
    empty_div();
    filter_view.empty();
    filter_view.html(html);
    list_productos_region();
}
function filter_cuota_x_producto()
{
    var html = '<div class="col-md-3"></div>';

    html += '<div class="col-md-2">';
    html += '<div class="form-group">';
    html += '<label for="field-1" class="control-label" style="color:#404969;">Periodo</label>';
    html += '<div class="input-group">';
    html += '<input style="border-color:#588D9C;" type="number" onblur="list_productos();" value="'+periodo_now()+'" id="input_periodo"  class="form-control text-center"/>';
    html += '</div>';
    html += '</div>';
    html += '</div>';

    html += '<div class="col-lg-3">';

    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Productos</label>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<select class="form-control" id="productos_list" style="border-color:#588D9C;text-align-last:center;"></select>';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-primary waves-effect waves-light" type="button" onclick="return cuota_x_producto();">';
    html += '<span class="fa fa-search"></span> &nbsp;Buscar';
    html += '</button>';
    html += '</span>';
    html += '</div>';
    html += '</div>';

    html += '</div>';

    empty_div();
    filter_view.empty();
    filter_view.html(html);
    list_productos();
}
function filter_prod_zona()
{
    var html = '<div class="col-md-4"></div>';

    html += '<div class="col-lg-3">';
    html += '<div class="form-group">';
    html += '<label  style="color:#404969;" for="field-1" class="control-label">Periodo</label>';
    html += '<div class="input-group bootstrap-touchspin">';
    html += '<input style="border-color:#588D9C;" type="number" value="'+periodo_now()+'" id="input_periodo"  class="form-control text-center"/>';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-primary waves-effect waves-light" type="button" onclick="return prod_zona();">';
    html += '<span class="fa fa-search"></span> &nbsp;Buscar';
    html += '</button>';
    html += '</span>';
    html += '</div>';
    html += '</div>';

    html += '</div>';

    empty_div();
    filter_view.empty();
    filter_view.html(html);
}
//OK
function list_distribuidoras()
{
    var controller = __AJAX__ + "marketing-list_distribuidoras",
        connect, postData;

    postData = "x=x";

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $('#distribuidoras_list').html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function list_productos_region()
{
    var controller = __AJAX__ + "marketing-list_productos_region",
        connect, postData;

    var periodo = $("#input_periodo").val();
    var region = $("#region").val();

    postData = "periodo=" +periodo + "&region=" + region;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $('#productos_region').html(connect.responseText);
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function list_productos_x_distribuidoras()
{
    var controller = __AJAX__ + "marketing-list_productos_x_distribuidoras",
        connect, postData;

    var periodo = $("#input_periodo").val();
    var distribuidora = $("#distribuidoras_list").val();

    postData = "periodo=" + periodo + "&distribuidora=" + distribuidora;
    /*+
    "&region="+region;*/

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $('#productos_list').html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);

}
function list_productos() {
    var controller = __AJAX__ + "marketing-list_productos",
        connect, postData;

    var periodo = $("#input_periodo").val();

    postData = "periodo=" + periodo;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $('#productos_list').html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function list_representantes() {
    var controller = __AJAX__ + "marketing-list_representantes",
        connect, postData;

    var periodo = $("#input_periodo").val();
    var region = $("#region").val();

    postData = "periodo=" + periodo + "&region=" + region;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $('#representantes').html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function vendedor_cliente()
{
    var controller = __AJAX__ + "marketing-vendedor_cliente",
        connect, postData;


    var periodo = $("#input_periodo").val();
    var region = $("#region").val();
    var representantes = $("#representantes").val();

    postData = "periodo=" + periodo + "&region=" + region + "&vendedor=" + representantes;

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

                view.html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var table = $("#table-vendedor-cliente-data").DataTable({
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'Vendedor_cliente',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columns: [
                        { "data": "cliente_ruc" },
                        { "data": "cliente_name" },
                        { "data": "distribuidora_name" },
                        { "data": "prod_name" },
                        { "data": "cantidad" },
                        { "data": "valor" },
                        { "data": "valor_total" }
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
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(5, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(5).footer()).html('<span style="font-size:1.02em;"><b>Cuadro: S/ ' + number_format_(pageTotal.toFixed(2)) + '<br> Total: S/ ' + number_format_(total.toFixed(2)) + '</b></span>');
                    },

                    // responsive: {
                    //     breakpoints: [
                    //         { name: 'desktop', width: Infinity },
                    //         { name: 'tablet',  width: 1024 },
                    //         { name: 'fablet',  width: 768 },
                    //         { name: 'phone',   width: 480 }
                    //     ]
                    // },
                    responsive: true,
                    aLengthMenu: [
                        [25, 50, 100, 200, 250, -1],
                        [25, 50, 100, 200, 250, "Todo"]
                    ],
                    iDisplayLength: 100,
                    bLengthChange: true, //show rows
                    bFilter: true,
                    bInfo: false,
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    },
                    order: [
                        [$('th.this_order').index(), 'desc']
                    ],
                });
                table.buttons().container().appendTo('#table-vendedor-cliente-data_wrapper .col-md-6:eq(0)');
                // table.columns( '.sumavalor' ).every( function () {
                //     var sum = this
                //         .data()
                //         .reduce( function (a,b) {
                //             return a + b;
                //         } );

                //     $("#total").html( 'Sum: '+sum );
                // } );



                // table.on( 'search.dt', function () {
                //     // $('#filterInfo').html( 'Currently applied global search: '+table.search() );
                //     alert("HOlAXD");
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
function producto_region_periodo()
{
    var controller = __AJAX__ + "marketing-producto_region_periodo",
        connect, postData;


    var periodo = $("#input_periodo").val();
    var region = $("#region").val();
    var producto = $("#productos_region").val();

    postData = "periodo=" + periodo +
        "&region=" + region +
        "&producto=" + producto;

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

                view.html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                // var columnsum = 8;

                var table = $("#table_producto_region_periodo_data").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'Producto_cliente_zona',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',

                    }],
                    columns: [
                        { "data": "region_code_sup" },
                        { "data": "supervisor" },
                        { "data": "zona_g" },
                        { "data": "repre_name" , className: 'text-left'},
                        { "data": "cliente_ruc" },
                        { "data": "cliente_name", className: 'text-left' },
                        // { "data": "distrito", className: 'text-left' },
                        { "data": "unidades" },
                        { "data": "valores" },
                        { "data": "precio_uni" }
                    ],
                    columnDefs: [
                        { "visible": false, "targets": 0 },
                        { "visible": false, "targets": 1 },
                        // { "visible": false, "targets": 6 }

                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var rows = api.rows({ page: 'current' }).nodes();
                        var last = null;
                        var igv = 1.18;

                        api.column(0, { page: 'current' }).data().each(function(group, i) {
                            val = api.row(api.row($(rows).eq(i)).index()).data();

                            if (last !== group) {
                                $(rows).eq(i).before(
                                    $("<tr style='background-color:#61B292;' class='group text-white font-weight-bold'></tr>", { "data-id": group }).append($("<td></td>", {
                                        "colspan": 2,
                                        "class": "pocell text-center",
                                        "html": '<b>' + val.supervisor
                                    })).append($("<td></td>", {
                                        "id": "b" + group,
                                        "class": "noCount",
                                        "text": "-"
                                    })).append($("<td></td>", {
                                        "id": "c" + group,
                                        "class": "noCount",
                                        "text": "-"
                                    })).append($("<td></td>", {
                                        "id": "d" + group,
                                        "class": "noCount",
                                        "text": "-"
                                    })).append($("<td></td>", {
                                        "id": "e" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "f" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).prop('outerHTML'));

                                last = group;
                            }
                            // $("#e" + val.reg_sup).text(float_rounded(parseFloat($("#e" + val.reg_sup).text()) + parseFloat(val.cuota_uni)));

                            // $("#d" + val.region_code).text(float_rounded(parseFloat($("#d" + val.region_code).text()) + parseFloat(val.unidad)));
                            // $("#d" + val.region_code_sup).text(float_rounded(parseFloat($("#d" + val.region_code_sup).text()) + parseFloat(val.unidades)));
                            $("#d" + val.region_code_sup).text("-");
                            $("#e" + val.region_code_sup).text(float_rounded(parseFloat($("#e" + val.region_code_sup).text()) + parseFloat(val.valores)));
                            // $("#f" + val.region_code).text('---------');
                            /*
                            float_rounded((parseFloat($("#e" + val.region_code).text()) / parseFloat($("#f" + val.region_code).text())) * (igv)) + '%'
                            
                            */
                        });
                    },
                    footerCallback: function(row, data, start, end, display) {
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
                        total7 = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal7 = api
                            .column(7, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // Total over all pages
                        total8 = api
                            .column(8)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal8 = api
                            .column(8, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        total_col7 = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pag_total_col7 = api
                            .column(7, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        total_col8 = api
                            .column(8)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pag_total_col8 = api
                            .column(8, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        porcj_cuadro = float_rounded((pag_total_col8 / pag_total_col7) * 1.18);
                        porcj_total = float_rounded((total_col8 / total_col7) * 1.18);

                        // $(api.column(8).footer()).html('<span style="font-size:1.02em;"><b>-</b></span>');

                        // $(api.column(9).footer()).html('<span style="font-size:1.02em;"><b>Pantalla: ' +
                        // porcj_cuadro + '%</b><br> <b>Total: ' +
                        // porcj_total + '%</b></span>');
                        $(api.column(6).footer()).html('-');
                        $(api.column(7).footer()).html('-');
                        $(api.column(8).footer()).html('-');
                        // Update footer
                        // $(api.column(6).footer()).html('<span style="font-size:1.2em;"><b>Pantalla: ' + pageTotal7.toFixed(0) + '<br> Total: ' + total7.toFixed(0) + '</b></span>');
                        // $(api.column(7).footer()).html('<span style="font-size:1.2em;"><b>Pantalla: S/ ' + number_format_(pageTotal8.toFixed(2)) + '<br> Total: S/ ' + number_format_(total8.toFixed(2)) + '</b></span>');
                        // $(api.column(9).footer()).html('<span style="font-size:1.02em;"><b>Cuadro: ' + pageTotal9.toFixed(2) + '<br> Total: ' + total9.toFixed(2) + '</b></span>');
                    },
                    responsive: true,
                    aLengthMenu: [
                        [25, 50, 100, 200, 250, -1],
                        [25, 50, 100, 200, 250, "Todo"]
                    ],
                    iDisplayLength: 100,
                    bLengthChange: true, //show rows
                    bFilter: true,
                    bInfo: false,
                    bSort: false,
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    },
                    order: [
                        [$('th.this_order').index(), 'desc'],
                        [$('th.this_order2').index(), 'desc']
                    ]
                });
                table.buttons().container().appendTo('#table_producto_region_periodo_data_wrapper .col-md-6:eq(0)');
                $("#table_producto_region_periodo_data_filter").addClass("pull-left");

            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function producto_cliente_zona()
{
    var controller = __AJAX__ + "marketing-producto_cliente_zona",
        connect, postData;

    var periodo = $("#input_periodo").val();
    var distribuidora = $("#distribuidoras_list").val();
    var producto = $("#productos_list").val();

    postData = "periodo=" + periodo + "&distribuidora=" + distribuidora + "&producto=" + producto;

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

                view.html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                // var columnsum = 8;

                var table = $("#table-producto_cliente_zona-data").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'Producto_cliente_zona',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',

                    }],
                    columns: [
                        { "data": "region_code" },
                        { "data": "supervisor" },
                        { "data": "zona_g" },
                        { "data": "repre_name" },
                        { "data": "cliente_ruc" },
                        { "data": "cliente_name", className: 'text-left' },
                        { "data": "distrito", className: 'text-left' },
                        { "data": "unidad" },
                        { "data": "valores" },
                        { "data": "prom_pvf" }
                    ],
                    columnDefs: [
                        { "visible": false, "targets": 0 },
                        { "visible": false, "targets": 1 }

                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var rows = api.rows({ page: 'current' }).nodes();
                        var last = null;
                        var igv = 1.18;

                        api.column(0, { page: 'current' }).data().each(function(group, i) {
                            val = api.row(api.row($(rows).eq(i)).index()).data();

                            if (last !== group) {
                                $(rows).eq(i).before(
                                    $("<tr style='background-color:#61B292;' class='group text-white font-weight-bold'></tr>", { "data-id": group }).append($("<td></td>", {
                                        "colspan": 2,
                                        "class": "pocell text-center",
                                        "html": '<b>' + val.supervisor
                                    })).append($("<td></td>", {
                                        "id": "b" + group,
                                        "class": "noCount",
                                        "text": "-"
                                    })).append($("<td></td>", {
                                        "id": "c" + group,
                                        "class": "noCount",
                                        "text": "-"
                                    })).append($("<td></td>", {
                                        "id": "d" + group,
                                        "class": "noCount",
                                        "text": "-"
                                    })).append($("<td></td>", {
                                        "id": "e" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "f" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "g" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).prop('outerHTML'));

                                last = group;
                            }

                            $("#e" + val.region_code).text(float_rounded(parseFloat($("#e" + val.region_code).text()) + parseFloat(val.unidad)));
                            $("#f" + val.region_code).text(float_rounded(parseFloat($("#f" + val.region_code).text()) + parseFloat(val.valores)));
                            $("#g" + val.region_code).text(float_rounded((parseFloat($("#e" + val.region_code).text()) / parseFloat($("#f" + val.region_code).text())) * (igv)) + '%');
                        });
                    },
                    footerCallback: function(row, data, start, end, display) {
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
                        total7 = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal7 = api
                            .column(7, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // Total over all pages
                        total8 = api
                            .column(8)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal8 = api
                            .column(8, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        total_col7 = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pag_total_col7 = api
                            .column(7, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        total_col8 = api
                            .column(8)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pag_total_col8 = api
                            .column(8, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        porcj_cuadro = float_rounded((pag_total_col8 / pag_total_col7) * 1.18);
                        porcj_total = float_rounded((total_col8 / total_col7) * 1.18);

                        $(api.column(9).footer()).html('<span style="font-size:1.02em;"><b>-</b></span>');

                        // $(api.column(9).footer()).html('<span style="font-size:1.02em;"><b>Pantalla: ' +
                        // porcj_cuadro + '%</b><br> <b>Total: ' +
                        // porcj_total + '%</b></span>');

                        // Update footer
                        $(api.column(7).footer()).html('<span style="font-size:1.2em;"><b>Pantalla: ' + pageTotal7.toFixed(0) + '<br> Total: ' + total7.toFixed(0) + '</b></span>');
                        $(api.column(8).footer()).html('<span style="font-size:1.2em;"><b>Pantalla: S/ ' + number_format_(pageTotal8.toFixed(2)) + '<br> Total: S/ ' + number_format_(total8.toFixed(2)) + '</b></span>');
                        // $(api.column(9).footer()).html('<span style="font-size:1.02em;"><b>Cuadro: ' + pageTotal9.toFixed(2) + '<br> Total: ' + total9.toFixed(2) + '</b></span>');
                    },
                    responsive: true,
                    aLengthMenu: [
                        [25, 50, 100, 200, 250, -1],
                        [25, 50, 100, 200, 250, "Todo"]
                    ],
                    iDisplayLength: 100,
                    bLengthChange: true, //show rows
                    bFilter: true,
                    bInfo: false,
                    bSort: false,
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    },
                    order: [
                        [$('th.this_order').index(), 'desc'],
                        [$('th.this_order2').index(), 'desc']
                    ]
                });
                table.buttons().container().appendTo('#table-producto_cliente_zona-data_wrapper .col-md-6:eq(0)');
                $("#table-producto_cliente_zona-data_filter").addClass("pull-left");

            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function resumen_ventas()
{
    var controller = __AJAX__ + "marketing-resumen_ventas",
        connect, postData;

    var periodo = $("#input_periodo").val();

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
                var info = connect.responseText;
                // console.log(info);
                view.html(info);

            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function cuota_x_producto()
{
    var controller = __AJAX__ + "marketing-cuota_x_producto",
        connect, postData;

    var periodo = $("#input_periodo").val();
    var producto = $("#productos_list").val();

    postData = "periodo=" + periodo + "&producto=" + producto;

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

                view.html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var columnsum = 7;

                var table = $("#table-cuota_x_producto-data").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'cuota_x_producto',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        className: 'btn btn-default waves-effect waves-light',

                    }],
                    columns: [
                        { "data": "region_cod", },
                        { "data": "region_sup" },
                        { "data": "repre_zona" },
                        { "data": "repre_name", "class": "text-left" },
                        { "data": "cuota_uni" },
                        { "data": "cantidad_vendida" },
                        { "data": "venta_uni" },
                        { "data": "valor_vendida" },
                        { "data": "alcance" },
                    ],
                    "columnDefs": [
                        { "visible": false, "targets": 0 },
                        { "visible": false, "targets": 1 }
                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var rows = api.rows({ page: 'current' }).nodes();
                        var last = null;

                        api.column(0, { page: 'current' }).data().each(function(group, i) {
                            val = api.row(api.row($(rows).eq(i)).index()).data();

                            if (last !== group) {
                                $(rows).eq(i).before(
                                    $("<tr style='background-color:#61B292;' class='group text-white font-weight-bold'></tr>", { "data-id": group }).append($("<td></td>", {
                                        "colspan": 2,
                                        "class": "pocell text-left",
                                        "html": '<b>' + val.region_sup
                                    })).append($("<td></td>", {
                                        "id": "b" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "c" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "d" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "e" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "f" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).prop('outerHTML'));

                                last = group;
                            }

                            $("#b" + val.region_cod).text(float_rounded(parseFloat($("#b" + val.region_cod).text()) + parseFloat(val.cuota_uni)));
                            $("#c" + val.region_cod).text(float_rounded(parseFloat($("#c" + val.region_cod).text()) + parseFloat(val.cantidad_vendida)));
                            $("#d" + val.region_cod).text(float_rounded(parseFloat($("#d" + val.region_cod).text()) + parseFloat(val.venta_uni)));
                            $("#e" + val.region_cod).text(float_rounded(parseFloat($("#e" + val.region_cod).text()) + parseFloat(val.valor_vendida)));
                            $("#f" + val.region_cod).text(float_rounded((parseFloat($("#e" + val.region_cod).text()) * 100) / (parseFloat($("#d" + val.region_cod).text()))) + '%');
                        });
                    },
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        //4
                        total4 = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        pageTotal4 = api
                            .column(4, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(4).footer()).html('<span style="font-size:1.02em;">Pantalla<b>: ' +
                            number_format_(pageTotal4.toFixed(0)) + '<br> Total: ' +
                            number_format_(total4.toFixed(0)) + '</b></span>');
                        //4

                        total5 = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        pageTotal5 = api
                            .column(5, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(5).footer()).html('<span style="font-size:1.02em;"><b>Pantalla: ' +
                            number_format_(pageTotal5.toFixed(0)) + '<br> Total: ' +
                            number_format_(total5.toFixed(0)) + '</b></span>');
                        //4
                        total6 = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        pageTotal6 = api
                            .column(6, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(6).footer()).html('<span style="font-size:1.02em;"><b id="colum6_page">Pantalla: S/ ' +
                            number_format_(pageTotal6.toFixed(2)) + '</b><br> <b id="colum6_total">Total: S/ ' +
                            number_format_(total6.toFixed(2)) + '</b></span>');

                        total7 = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pageTotal7 = api
                            .column(7, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        $(api.column(7).footer()).html('<span style="font-size:1.02em;"><b id="colum6_page">Pantalla: S/ ' +
                            number_format_(pageTotal7.toFixed(2)) + '</b><br> <b>Total: S/ ' +
                            number_format_(total7.toFixed(2)) + '</b></span>');

                        total_col6 = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pag_total_col6 = api
                            .column(6, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        total_col7 = api
                            .column(7)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                        pag_total_col7 = api
                            .column(7, { page: 'current' })
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);

                        porcj_cuadro = float_rounded((pag_total_col7 * 100) / pag_total_col6);
                        porcj_total = float_rounded((total_col7 * 100) / total_col6);

                        $(api.column(8).footer()).html('<span style="font-size:1.02em;"><b>Pantalla: ' +
                            porcj_cuadro + '%</b><br> <b>Total: ' +
                            porcj_total + '%</b></span>');
                    },
                    responsive: false,
                    iDisplayLength: -1,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: true,
                    bSort: false,
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    }
                });
                table.buttons().container().appendTo('#table-cuota_x_producto-data_wrapper .col-md-6:eq(0)');
                $(table.table().body()).css('font-size', '0.95em').css("font-family", "verdana").addClass("text-black");
                $("#table-cuota_x_producto-data_filter").addClass("pull-left");
                table.columns.adjust().draw();
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function prod_zona()
{
    var controller = __AJAX__ + "marketing-prod_zona",
        connect, postData;

    var periodo = $("#input_periodo").val();

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
                var info = connect.responseText;

                var info_explo = info.split('||');

                view.html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var columnsum = 4;

                // console.log(json_data);

                var table = $("#table-prod_zona-data").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'prod_zona',
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        footer: true,
                        className: 'btn btn-default waves-effect waves-light',
                    }],
                    columns: [
                        { "data": "reg_sup" },
                        { "data": "reg_sup_name" },
                        { "data": "prod_name", className: "text-left font-10" },
                        { "data": "cuota_uni" },
                        { "data": "venta_uni" },
                        { "data": "pvf_prom" },
                        { "data": "saldo" },
                        { "data": "venta_valor" },
                    ],
                    "columnDefs": [
                        { "visible": false, "targets": 1 },
                        { "visible": false, "targets": 7 }
                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var rows = api.rows({ page: 'current' }).nodes();
                        var last = null;

                        api.column(0, { page: 'current' }).data().each(function(group, i) {
                            val = api.row(api.row($(rows).eq(i)).index()).data();

                            if (last !== group) {
                                $(rows).eq(i).before(
                                    $("<tr style='background-color:#61B292;' class='group text-white font-weight-bold'></tr>", { "data-id": group }).append($("<td></td>", {
                                        "colspan": 2,
                                        "class": "pocell text-left",
                                        "html": '<b>' + val.reg_sup_name + '</b> &nbsp;&nbsp;' +
                                            '<button class="btn btn-inverse btn-sm waves-effect waves-light pull-right" onclick="return prod_zona_supervisor(' + group + ');">Detalle</button>'
                                    })).append($("<td></td>", {
                                        "id": "e" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "p" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "b" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "c" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "x" + group,
                                        "class": "noCount d-none",
                                        "text": "0"
                                    })).prop('outerHTML'));

                                last = group;
                            }

                            $("#e" + val.reg_sup).text(float_rounded(parseFloat($("#e" + val.reg_sup).text()) + parseFloat(val.cuota_uni)));
                            $("#p" + val.reg_sup).text(float_rounded(parseFloat($("#p" + val.reg_sup).text()) + parseFloat(val.venta_uni)));
                            $("#x" + val.reg_sup).text(float_rounded(parseFloat($("#x" + val.reg_sup).text()) + parseFloat(val.venta_valor)));
                            $("#b" + val.reg_sup).text(float_rounded((parseFloat($("#p" + val.reg_sup).text()) / (parseFloat($("#x" + val.reg_sup).text()))) * 1.18));
                            $("#c" + val.reg_sup).text(float_rounded(parseFloat($("#c" + val.reg_sup).text()) + parseFloat(val.saldo)));

                        });
                    },
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();
                        $(api.column(3).footer()).html('Pantalla: ' +
                            api.column(3, { page: 'current' }).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0) + ' - total: ' +
                            api.column(3).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0)

                        );
                        $(api.column(4).footer()).html('Pantalla: ' +
                            api.column(4, { page: 'current' }).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0) + ' - total: ' +
                            api.column(4).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0)

                        );
                        // $(api.column(5).footer()).html('Pantalla: ' +
                        //     api.column(5, { page: 'current' }).data().reduce(function(a, b) {
                        //         return float_rounded(parseFloat(a) + parseFloat(b));
                        //     }, 0) + ' - total: ' +
                        //     api.column(5).data().reduce(function(a, b) {
                        //         return float_rounded(parseFloat(a) + parseFloat(b));
                        //     }, 0)

                        // );
                        $(api.column(5).footer()).html('-');
                        $(api.column(6).footer()).html('Pantalla: ' +
                            api.column(6, { page: 'current' }).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0) + ' - total: ' +
                            api.column(6).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0)
                        );

                    },
                    responsive: true,
                    iDisplayLength: 100,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    bSort: false,
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    }
                });
                table.buttons().container().appendTo('#table-prod_zona-data_wrapper .col-md-6:eq(0)');
                $(table.table().body()).css('font-size', '0.85em').css("font-family", "verdana").addClass("text-black");
                $("#table-prod_zona-data_filter").addClass("pull-left");
                $("#table-prod_zona-data_filter").css("border-color", "#588D9C !important");
                $("#table-prod_zona-data_paginate").addClass("pull-left");


                // Order by the grouping
                $('#table-prod_zona-data tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 1 && currentOrder[1] === 'asc') {
                        table.order([1, 'desc']).draw();
                    } else {
                        table.order([1, 'asc']).draw();
                    }
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
function prod_zona_supervisor(region)
{
    var controller = __AJAX__ + "marketing-prod_zona_supervisor",
        connect, postData;

    var periodo = $("#input_periodo").val();

    postData = "periodo=" + periodo + "&region=" + region;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            // empty_div();
            // console.log(connect.responseText);

            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {
                $('html, body').animate({ scrollTop: view2.position().top }, 'slow');

                var info = connect.responseText;

                var info_explo = info.split('||');

                view2.html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                var columnsum = 4;

                // console.log(json_data);

                var table = $("#table-prod_zona_supervisor-data").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    buttons: [{
                        extend: 'excel',
                        title: 'prod_zona - ' + $("#reg_sup").text(),
                        text: '<span class="fa fa-file-excel-o"></span>&nbsp; Excel<br>',
                        footer: true,
                        className: 'btn btn-default waves-effect waves-light',
                        exportOptions: {
                            columns: '.xdddd'
                        }
                    }],
                    columns: [
                        { "data": "zona" },
                        { "data": "repre_cod" },
                        { "data": "repre_name" },
                        { "data": "prod_name", className: "text-left font-10" },
                        { "data": "cuota_uni" },
                        { "data": "venta_uni" },
                        { "data": "pvf_prom" },
                        { "data": "saldo" },
                        { "data": "venta_valor" }
                    ],
                    "columnDefs": [
                        { "visible": false, "targets": 1 },
                        { "visible": false, "targets": 2 },
                        { "visible": false, "targets": 8 }
                    ],
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var rows = api.rows({ page: 'current' }).nodes();
                        var last = null;

                        api.column(1, { page: 'current' }).data().each(function(group, i) {
                            val = api.row(api.row($(rows).eq(i)).index()).data();
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    $("<tr style='background-color:#61B292;' class='group text-white font-weight-bold'></tr>", { "data-id": group }).append($("<td></td>", {
                                        "colspan": 2,
                                        "class": "pocell text-left",
                                        "html": '<b>' + val.repre_name + '</b>'
                                    })).append($("<td></td>", {
                                        "id": "e" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "p" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "b" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "c" + group,
                                        "class": "noCount",
                                        "text": "0"
                                    })).append($("<td></td>", {
                                        "id": "z" + group,
                                        "class": "noCount d-none",
                                        "text": "0"
                                    })).prop('outerHTML'));

                                last = group;
                            }

                            $("#e" + val.repre_cod).text(float_rounded(parseFloat($("#e" + val.repre_cod).text()) + parseFloat(val.cuota_uni)));
                            $("#p" + val.repre_cod).text(float_rounded(parseFloat($("#p" + val.repre_cod).text()) + parseFloat(val.venta_uni)));
                            $("#z" + val.repre_cod).text(float_rounded(parseFloat($("#z" + val.repre_cod).text()) + parseFloat(val.venta_valor)));
                            $("#b" + val.repre_cod).text(float_rounded((parseFloat($("#p" + val.repre_cod).text()) / (parseFloat($("#z" + val.repre_cod).text()))) * 1.18));
                            $("#c" + val.repre_cod).text(float_rounded(parseFloat($("#c" + val.repre_cod).text()) + parseFloat(val.saldo)));
                        });
                    },
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api();

                        $(api.column(4).footer()).html('vista: ' +
                            api.column(4, { page: 'current' }).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0) + ' - total: ' +
                            api.column(4).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0)

                        );
                        $(api.column(5).footer()).html('vista: ' +
                            api.column(5, { page: 'current' }).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0) + ' - total: ' +
                            api.column(5).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0)

                        );
                        $(api.column(6).footer()).html('vista: ' +
                            api.column(6, { page: 'current' }).data().reduce(function(a, b) {
                                return float_rounded(parseFloat(a) + parseFloat(b));
                            }, 0) + ' - total: ' +
                            api.column(6).data().reduce(function(a, b) {
                                return float_rounded(parseFloat(a) + parseFloat(b));
                            }, 0)
                        );
                        $(api.column(7).footer()).html('vista: ' +
                            api.column(7, { page: 'current' }).data().reduce(function(a, b) {
                                return float_rounded(parseFloat(a) + parseFloat(b));
                            }, 0) + ' - total: ' +
                            api.column(7).data().reduce(function(a, b) {
                                return float_rounded(parseFloat(a) + parseFloat(b));
                            }, 0)
                        );
                    },
                    responsive: true,
                    iDisplayLength: 100,
                    bLengthChange: false, //show rows
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    bSort: false,
                    "language": {
                        "lengthMenu": "Registros _MENU_  &nbsp;&nbsp;&nbsp;"
                    }
                });
                table.buttons().container().appendTo('#table-prod_zona_supervisor-data_wrapper .col-md-6:eq(0)');
                $(table.table().body()).css('font-size', '0.85em').css("font-family", "verdana").addClass("text-black");
                $("#table-prod_zona_supervisor-data_filter").addClass("pull-left");
                $("#table-prod_zona_supervisor-data_filter").css("border-color", "#588D9C !important");
                $("#table-prod_zona_supervisor-data_paginate").addClass("pull-left");


                // Order by the grouping
                $('#table-prod_zona_supervisor-data tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 1 && currentOrder[1] === 'asc') {
                        table.order([1, 'desc']).draw();
                    } else {
                        table.order([1, 'asc']).draw();
                    }
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