//CODE
listar_avance_visitas();

function listar_avance_visitas()
{
    var controller = __AJAX__ + "avancevisitas-listar_avance_visitas",
        connect, postData;

    var periodo = $("#input_periodo").val();
    var region = $("#input_region").val();

    postData = "periodo=" + periodo + "&region=" + region;
    //console.log(postData);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;

            $("#div-content-data-1").html(result);

            var table = $("#table_regional_").DataTable({
                dom: 'Bfrtip',
                lengthChange: false,
                buttons: [{
                        extend: 'print',
                        text: '<span class="fa fa-print"></span>',
                        className: 'btn btn-inverse btn-custom waves-effect waves-light',
                        exportOptions: {
                            columns: '.export-col-x-regional_'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<span class="fa fa-file-excel-o"></span>',
                        className: 'btn btn-default btn-custom waves-effect waves-light',
                        exportOptions: {
                            columns: '.export-col-x-regional_'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<span class="fa fa-file-pdf-o"></span>',
                        className: 'btn btn-primary btn-custom waves-effect waves-light',
                        exportOptions: {
                            columns: '.export-col-x-regional_'
                        }
                    }
                ],
                responsive: {
                    breakpoints: [
                        { name: 'desktop', width: 1366 },
                        { name: 'tablet', width: 1024 },
                        { name: 'fablet', width: 768 },
                        { name: 'phone', width: 480 }
                    ]
                },
                bLengthChange: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: false,
                order: [
                    [$('th.order_rg').index(), 'desc']
                ]
            });
            table.buttons().container().appendTo('#table_regional__wrapper .col-md-6:eq(0)');
            $("#table_regional__filter").addClass("pull-left");
            $("#table_regional__paginate").addClass("pull-left");
            // }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function detail_clientes_visita(zona_view, periodo, prod_port, region_venta)
{
    $("#loader").empty();

    var controller = __AJAX__ + "avancevisitas-detail_clientes_visita",
        connect, form, explode;

    form = "zona_view=" + zona_view + "&periodo=" + periodo + "&prod_port=" + prod_port + "&region_venta=" + region_venta;
    // console.log(form);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $('html, body').animate({ scrollTop: '+=200px' }, 1000);

            $("#loader").empty();

            var result = connect.responseText;
            var split_data = result.split('|~|');

            var table_html = split_data[0];
            var json_data = split_data[1];
            // console.log(table_html);
            if (parseInt(json_data) == 0) {
                // $("#detail_regional").empty();
                $("#div-content-data-2").html("Ocurrio un error al generar el grafico.");
            } else if (parseInt(json_data) == 1) {
                // $("#detail_regional").empty();
                swal("Portafolio", "No perteneces a este portafolio", "warning");
            } else {

                $("#div-content-data-2").html(table_html);

                var info = JSON.parse(json_data);
                // console.log(info);

                var table = $("#table_clientes").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: info.data,
                    columns: [
                        { "data": "dist_name" },
                        { "data": "cliente_ruc" },
                        { "data": "cliente_cod" },
                        { "data": "cliente_name" },
                        { "data": "sumaxdistri" },
                        { "data": "button" }
                    ],
                    buttons: [{
                            extend: 'print',
                            text: '<span class="fa fa-print"></span>',
                            className: 'btn btn-inverse btn-custom waves-effect waves-light',
                            exportOptions: {
                                columns: '.export-col-x-clientes_'
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<span class="fa fa-file-excel-o"></span>',
                            className: 'btn btn-default btn-custom waves-effect waves-light',
                            exportOptions: {
                                columns: '.export-col-x-clientes_'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<span class="fa fa-file-pdf-o"></span>',
                            className: 'btn btn-primary btn-custom waves-effect waves-light',
                            exportOptions: {
                                columns: '.export-col-x-clientes_'
                            }
                        }
                    ],
                    responsive: {
                        breakpoints: [
                            { name: 'desktop', width: 1366 },
                            { name: 'tablet', width: 1024 },
                            { name: 'fablet', width: 768 },
                            { name: 'phone', width: 480 }
                        ]
                    },
                    bLengthChange: false,
                    bLengthChange: false,
                    pageLength: 15,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    order: [
                        [$('th.this_order_reg').index(), 'desc']
                    ]
                });
                table.buttons().container().appendTo('#table_clientes_wrapper .col-md-6:eq(0)');
                $("#table_clientes_filter").addClass("pull-left");
                $("#table_clientes_paginate").addClass("pull-left");
                $("#table_clientes tbody").css("font-size", "0.85em");
            }

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}
function detail_total_ventas_visita(zona_view, periodo, prod_port, region_venta)
{
    $("#loader").empty();

    var controller = __AJAX__ + "avancevisitas-detail_total_ventas_visita",
        connect, form, explode;

    form = "zona_view=" + zona_view + "&periodo=" + periodo + "&prod_port=" + prod_port + "&region_venta=" + region_venta;
// console.log(form);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $('html, body').animate({ scrollTop: '+=200px' }, 1000);

            $("#loader").empty();

            var result = connect.responseText;
            var split_data = result.split('|~|');

            var table_html = split_data[0];
            var json_data = split_data[1];

            if (parseInt(json_data) == 0) {
                // $("#detail_regional").empty();
                $("#detail_regional").html("Ocurrio un error al generar el grafico.");
            } else if (parseInt(json_data) == 1) {
                // $("#detail_regional").empty();
                swal("Portafolio", "No perteneces a este portafolio", "warning");
            } else {
                $("#div-content-data-2").html(table_html);

                var info = JSON.parse(json_data);
                // console.log(info);

                var table = $("#table_total_ventas").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: info.data,
                    columns: [
                        { "data": "dist_name" },
                        { "data": "cliente_ruc" },
                        { "data": "cliente_cod" },
                        { "data": "cliente_name" },
                        {"data": "localidad"},
                        { "data": "cod_prod" },
                        { "data": "desc_prod" },
                        { "data": "cantidad" },
                        { "data": "valor" },
                        { "data": "fecha"}
                        
                    ],
                    buttons: [{
                            extend: 'print',
                            text: '<span class="fa fa-print"></span>',
                            className: 'btn btn-inverse btn-custom waves-effect waves-light',
                            exportOptions: {
                                columns: '.export-col-x-clientes_2'
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<span class="fa fa-file-excel-o"></span>',
                            className: 'btn btn-default btn-custom waves-effect waves-light',
                            exportOptions: {
                                columns: '.export-col-x-clientes_2'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<span class="fa fa-file-pdf-o"></span>',
                            className: 'btn btn-primary btn-custom waves-effect waves-light',
                            exportOptions: {
                                columns: '.export-col-x-clientes_2'
                            }
                        }
                    ],
                    responsive: {
                        breakpoints: [
                            { name: 'desktop', width: 1366 },
                            { name: 'tablet', width: 1024 },
                            { name: 'fablet', width: 768 },
                            { name: 'phone', width: 480 }
                        ]
                    },
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    pageLength: 20,
                    bInfo: false,
                    bAutoWidth: false,
                    //order: [[$('th.this_order_reg').index(), 'desc']]
                });
                table.buttons().container().appendTo('#table_total_ventas_wrapper .col-md-6:eq(0)');
                $("#table_total_ventas_filter").addClass("pull-left");
                $("#table_total_ventas_paginate").addClass("pull-left");
                $("#table_total_ventas tbody").css("font-size", "0.85em");
            }

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}
function detail_productos_visita(vendedor, region_venta, cliente_cod, dist_cod, periodo)
{
    $("#loader").empty();

    var controller = __AJAX__ + "avancevisitas-detail_productos_visita",
        connect, form, explode;

    form = "vendedor=" + vendedor + 
            "&region_venta=" + region_venta +
            "&periodo=" + periodo +
            "&cliente_cod=" + cliente_cod +
            "&dist_cod=" + dist_cod;


    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            if (parseInt(connect.responseText) == 0) {
                swal("error", connect.responseText, "error");
            } else {
                $("#detail_productos").html(connect.responseText);
                $("#detail_productos_modal").modal();
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}
function reporte()
{
    $("#loader").empty();

    var controller = __AJAX__ + "avancevisitas-reporte",
        connect, form, explode;

    var periodo = $("#input_periodo").val();

    form = "&periodo=" + periodo;


    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;
            var split_data = result.split('|~|');

            var table_html = split_data[0];
            var json_data = split_data[1];

            $("#div-content-data-2").html(table_html);

            var info = JSON.parse(json_data);

            var table = $("#reporte_test").DataTable({

                dom: 'Bfrtip',
                lengthChange: false,
                destroy: true,
                data: info.data,
                buttons: [{
                        extend: 'print',
                        text: '<span class="fa fa-print"></span>',
                        className: 'btn btn-inverse btn-custom waves-effect waves-light'
                    },
                    {
                        extend: 'excel',
                        text: '<span class="fa fa-file-excel-o"></span>',
                        className: 'btn btn-default btn-custom waves-effect waves-light'
                    },
                    {
                        extend: 'pdf',
                        text: '<span class="fa fa-file-pdf-o"></span>',
                        className: 'btn btn-primary btn-custom waves-effect waves-light'
                    }
                ],
                columns: [
                    { "data": "cod_ven", className: 'text-center' },
                    { "data": "zona" },
                    { "data": "name_vendedor" },
                    { "data": "ubi_dsc" },
                    { "data": "ruc_cliente" },
                    { "data": "name_cliente" },
                    { "data": "venta_x_dis", className: 'text-center' }
                ],
                columnDefs: [
                    { "visible": false, "targets": 0 }

                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;

                    api.column(0, { page: 'current' }).data().each(function(group, i) {
                        val = api.row(api.row($(rows).eq(i)).index()).data();

                        if (last !== group) {
                            $(rows).eq(i).before(
                                $("<tr style='background-color:#61B292 !important;' class='group text-white font-weight-bold'></tr>", { "data-id": group }).append($("<td></td>", {
                                    "colspan": 3,
                                    "class": "pocell text-center",
                                    "html": '<b>' + val.name_vendedor
                                })).append($("<td></td>", {
                                    "id": "k" + group,
                                    "class": "noCount text-center",
                                    "text": ""
                                })).append($("<td></td>", {
                                    "id": "g" + group,
                                    "class": "noCount text-center",
                                    "text": ""
                                })).append($("<td></td>", {
                                    "id": "m" + group,
                                    "class": "noCount text-center",
                                    "text": "0"
                                })).prop('outerHTML'));

                            last = group;
                        }

                        $("#m" + val.cod_ven).text(float_rounded(parseFloat($("#m" + val.cod_ven).text()) + parseFloat(val.venta_x_dis)));
                        // $("#c" + val.region_cod).text(float_rounded(parseFloat($("#c" + val.region_cod).text()) + parseFloat(val.cantidad_vendida)));
                        // $("#d" + val.region_cod).text(float_rounded(parseFloat($("#d" + val.region_cod).text()) + parseFloat(val.venta_uni)));
                        // $("#e" + val.region_cod).text(float_rounded(parseFloat($("#e" + val.region_cod).text()) + parseFloat(val.valor_vendida)));
                        // $("#f" + val.region_cod).text(float_rounded((parseFloat($("#e" + val.region_cod).text()) * 100) / (parseFloat($("#d" + val.region_cod).text()))) + '%');
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
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    pageTotal4 = api
                        .column(6, { page: 'current' })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // $(api.column(4).footer()).html('<span style="font-size:1.02em;">Pantalla<b>: ' +
                    //     number_format_(pageTotal4.toFixed(2)) + '<br> Total: ' +
                    //     number_format_(total4.toFixed(2)) + '</b></span>');
                    $(api.column(6).footer()).html('<span style="font-size:1.02em;"> Total: ' +
                        number_format_(total4.toFixed(2)) + '</b></span>');
                    
                },
                
            bLengthChange: false,
            bFilter: true,
            pageLength: -1,
            bInfo: false,
            bAutoWidth: false,
            order: [[1, 'asc']],
            bSort: false
            
        });
        table.buttons().container().appendTo('#reporte_test_wrapper .col-md-6:eq(0)');
        $("#reporte_test_filter").addClass("pull-left");
        $("#reporte_test_paginate").addClass("pull-left");
        $("#reporte_test tbody").css("font-size", "0.85em");

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(form);
}

