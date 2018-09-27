//CODE
// ___INIT____





// ___ INIT___



function listar_data_detalle() {
    var controller = __AJAX__ + "recupero-listar_data_detalle",
        connect, postData;

    var periodo = $("#input_periodo").val();

    postData = "periodo=" + periodo;
    // console.log(postData);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;
            var split_data = result.split('|~|');

            var table_html = split_data[0];
            var json_data = split_data[1];

            if (parseInt(json_data) == 0) {
                // $("#detail_regional").empty();
                $("#div-content-data-1").html("Ocurrio un error al generar el grafico.");
            } else if (parseInt(json_data) == 1) {
                // $("#detail_regional").empty();
                swal("xd", "No perteneces a este portafolio", "warning");
            } else {

                $("#div-content-data-1").html(table_html);

                var info = JSON.parse(json_data);

                var table = $("#table_listado_data_detalle").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: info.data,
                    columns: [
                        { "data": "repre_cod", className: 'text-center' },
                        { "data": "repre_name", className: 'text-left' },
                        { "data": "dist_cod", className: 'text-center' },
                        { "data": "dist_name", className: 'text-center' },
                        { "data": "fecha", className: 'text-center' },
                        { "data": "client_cod", className: 'text-center' },
                        { "data": "client_ruc", className: 'text-center' },
                        { "data": "cliente_name", className: 'text-center' },
                        { "data": "prod_cod", className: 'text-center' },
                        { "data": "prod_name", className: 'text-left' },
                        { "data": "cantidad", className: 'text-center' },
                        { "data": "valor", className: 'text-center' },
                        { "data": "region", className: 'text-center' }
                    ],
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
                    responsive: true,
                    bLengthChange: false,
                    pageLength: 15,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,

                });
                table.buttons().container().appendTo('#table_listado_data_detalle_wrapper .col-md-6:eq(0)');
                $("#table_listado_data_detalle_filter").addClass("pull-left");
                $("#table_listado_data_detalle_paginate").addClass("pull-left");
                $("#table_listado_data_detalle tbody").css("font-size", "0.9em").css('color', 'black');
            }

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function procesar_recupero()
{
    var controller = __AJAX__ + "recupero-procesar_recupero", connect, postData;

    var periodo = $("#input_periodo").val();

    postData = "periodo=" + periodo;
    // console.log(postData);
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;
            var split_data = result.split('|~|');

            var table_html = split_data[0];
            var json_data = split_data[1];
            
            if (parseInt(json_data) == 0) {
                // $("#detail_regional").empty();
                $("#div-content-data-0").html("Ocurrio un error al generar el grafico.");
            } else if (parseInt(json_data) == 1) {
                // $("#detail_regional").empty();
                swal("xd", "No perteneces a este portafolio", "warning");
            } else {

                $("#div-content-data-0").html(table_html);

                var info = JSON.parse(json_data);

                var table = $("#table_procesado_recupero").DataTable({
                    dom: "Bfrtip",
                        lengthChange: false,
                        destroy: true,
                        data: info.data,
                    columns: [
                        { "data" : "client_ruc", className : 'text-center'},
                        { "data" : "cliente_name", className : 'text-center'},
                        { "data" : "prod_cod", className : 'text-center'},
                        { "data" : "prod_name", className : 'text-center'},
                        { "data" : "cantidad", className : 'text-center'},
                        { "data" : "valor", className : 'text-center'},
                        { "data" : "op1", className : 'text-center'},
                        { "data" : "op2", className : 'text-center'},
                        { "data" : "op3", className : 'text-center'},
                        { "data" : "op4", className : 'text-center'},
                        { "data" : "op5", className : 'text-center'},
                        { "data" : "op6", className : 'text-center'},
                        { "data" : "op7", className : 'text-center'},
                        { "data" : "op8", className : 'text-center'},
                        { "data" : "op9", className : 'text-center'},
                        { "data" : "op10", className : 'text-center'}
                    ],buttons: [{
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
                responsive: true,
                bLengthChange: false,
                pageLength: 15,
                bFilter: true,
                bInfo: false,
                bAutoWidth: false,
                 
                });
                table.buttons().container().appendTo('#table_procesado_recupero_wrapper .col-md-6:eq(0)');
                $("#table_procesado_recupero_filter").addClass("pull-left");
                $("#table_procesado_recupero_paginate").addClass("pull-left");
                $("#table_procesado_recupero tbody").css("font-size", "0.9em").css('color', 'black');
            }

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function procesar_escalas() {
    var controller = __AJAX__ + "recupero-procesar_escalas",
        connect, postData;

    var periodo = $("#input_periodo").val();

    postData = "periodo=" + periodo;
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var result = connect.responseText;
            var split_data = result.split('|~|');

            var table_html = split_data[0];
            var json_data = split_data[1];

            if (parseInt(json_data) == 0) {
                // $("#detail_regional").empty();
                $("#div-content-data-1").html("Ocurrio un error al generar el grafico.");
            } else if (parseInt(json_data) == 1) {
                // $("#detail_regional").empty();
                swal("xd", "No perteneces a este portafolio", "warning");
            } else {

                $("#div-content-data-1").html(table_html);
                
                var info = JSON.parse(json_data);
                
                var lastCat = '';
                var table = $("#table-data-escalas-1").DataTable({
                    dom: 'Bfrtip',
                    lengthChange: false,
                    destroy: true,
                    data: info.data,
                    columns: [
                        { "data": "region"},//0
                        { "data": "zonag"},//1
                        { "data": "repre"},//2
                        { "data": "dist_cod"},//3
                        { "data": "dist_name", className: 'text-left'},//4
                        { "data": "cliente_ruc"},//5
                        { "data": "cliente_name", className: 'text-left fnt-size-per'},//6
                        { "data": "prod_cod"},//7
                        { "data": "prod_name" , className: 'text-left fnt-size-per'},//8
                        { "data": "suma_cant"},//9
                        { "data": "suma_valor"},//10
                        { "data": "descuento" , className: 'text-left  fnt-size-per background-color_1'},//11
                        { "data": "descuento_sin_escala" , className: 'text-left  fnt-size-per background-color_1'},//12
                        { "data": "val_x_desc", className: 'background-color_1'},//13
                        { "data": "valor_uni", className: 'background-color_1'},//14
                        { "data": "p_list", className: 'background-color_1'},//15
                        { "data": "op1_", className: 'background-color_1'},//16
                        { "data": "p_list", className: 'background-color_2'},//17
                        { "data": "desc_dist", className: 'background-color_2'},//18
                        { "data": "op2_", className: 'background-color_2'},//19
                        { "data": "recupero"}//20
                    ],
                    columnDefs: [
                        { "visible": false, "targets": 1 },
                        { "visible": false, "targets": 3 },
                        { "visible": false, "targets": 7 },
                        { "visible": false, "targets": 12 },
                        { "visible": false, "targets": 13 },
                        //{ "Width": "10%", "targets": 8 },
                    ],buttons: [{
                        extend: 'print',
                        text: '<span class="fa fa-print"></span>',
                        tittle:'Recupero',
                        className: 'btn btn-inverse btn-custom waves-effect waves-light',
                        exportOptions: {
                            columns: '.this_events'
                        }
                    },
                    {
                        extend: 'excel',
                        tittle:'Recupero',
                        text: '<span class="fa fa-file-excel-o"></span>',
                        className: 'btn btn-default btn-custom waves-effect waves-light',
                        exportOptions: {
                            columns: '.this_events'
                        }
                    },
                    {
                        extend: 'pdf',
                        tittle:'Recupero',
                        text: '<span class="fa fa-file-pdf-o"></span>',
                        className: 'btn btn-primary btn-custom waves-effect waves-light',
                        exportOptions: {
                            columns: '.this_events'
                        }
                    }
                ],drawCallback: function()
                {
                    $("[data-toggle=popover]").popover();
                },initComplete: function ()
                {
                    this.api().columns(0).every( function () {
                      var column = this;
                      var select = $('<select class="form-control input-md" id="option_select_1" data-live-search="true" data-size="auto"><option value="">-</option></select>').appendTo( $("#div_option_1").empty() ).on( 'change', function () {
                          var val = $.fn.dataTable.util.escapeRegex($(this).val());
                          column.search( val ? '^'+val+'$' : '', true, false ).draw();
                        } );
                      column.data().unique().sort().each( function ( d, j ) {select.append( '<option value="'+d+'">'+d+'</option>' );
                      } );
                    } );
                    this.api().columns(2).every( function () {
                        var column = this;
                        var select = $('<select class="form-control input-md" id="option_select_2" data-live-search="true" data-size="auto"><option value="">-</option></select>').appendTo( $("#div_option_2").empty() ).on( 'change', function (){
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                          } );
                        column.data().unique().sort().each( function ( d, j ) {select.append( '<option value="'+d+'">'+d+'</option>' );
                        } );
                      } );
                      this.api().columns(4).every( function () {
                        var column = this;
                        var select = $('<select class="form-control input-md" id="option_select_4" data-live-search="true" data-size="auto"><option value="">-</option></select>').appendTo( $("#div_option_4").empty() ).on( 'change', function (){
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                          } );
                         column.data().unique().sort().each( function ( d, j ) {select.append( '<option value="'+d+'">'+d+'</option>' );
                        } );
                      } );
                      this.api().columns(6).every( function () {
                        var column = this;
                        var select = $('<select class="form-control input-md" id="option_select_6" data-live-search="true" data-size="auto"><option value="">-</option></select>').appendTo( $("#div_option_6").empty() ).on( 'change', function (){
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                        });
                        column.data().unique().sort().each( function ( d, j ) {select.append( '<option value="'+d+'">'+d+'</option>' );});
                      } );
                      this.api().columns(8).every( function () {
                        var column = this;
                        var select = $('<select class="form-control input-md" id="option_select_8" data-live-search="true" data-size="auto"><option value="">-</option></select>').appendTo( $("#div_option_8").empty() ).on( 'change', function (){
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                          });                
                            column.data().unique().sort().each( function ( d, j ) {select.append( '<option value="'+d+'">'+d+'</option>' );
                        } );
                      } );

                        $("#option_select_1").selectpicker('refresh');
                        $("#option_select_2").selectpicker('refresh');
                        $("#option_select_4").selectpicker('refresh');
                        $("#option_select_6").selectpicker('refresh');
                        $("#option_select_8").selectpicker('refresh');


                        $("#table-data-escalas-1_filter").addClass("pull-left");
                        $("#table-data-escalas-1_info").addClass("pull-right");
                        $("#table-data-escalas-1_paginate").addClass("pull-left");
                        $("#table-data-escalas-1 tbody").css("font-size", "0.9em").css('color', 'black');
                },
                footerCallback: function(row, data, start, end, display)
                {
                    var api = this.api(), data;

                    var intVal = function(i) { return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;};

                    var total_10 = api.column( 10, { search: 'applied' } ).data().reduce( function (a, b){  return intVal(a) + intVal(b);  }, 0 );
                    //$(api.column(10).footer()).html('<span style="font-size:1.02em;"><b>' + number_format_(total_10.toFixed(2)) +'</span>');

                    var total_17 = api.column( 17, { search: 'applied' } ).data().reduce( function (a, b){  return intVal(a) + intVal(b);  }, 0 );
                    //$(api.column(17).footer()).html('<span style="font-size:1.02em;"><b>' + number_format_(total_17.toFixed(2)) +'</span>');

                    var total_18 = api.column( 19, { search: 'applied' } ).data().reduce( function (a, b){  return intVal(a) + intVal(b);  }, 0 );
                    $("#total-recupero").html('<span>Total recupero: <b>' + number_format_(total_18.toFixed(2)) +'</b></span>'); 
                    //api.column(19).footer()
                },
                    responsive: true,
                    bLengthChange: false,
                    pageLength: 10,
                    bInfo: true,
                    language: {
                        info: "Mostrando del _START_ al _END_  de  _TOTAL_ registros.",
                        infoFiltered: "",
                        infoPostFix: "",
                        paginate: {
                            first:      "Primera",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Ultima"
                        },
                    },
                    order: [
                        [ 0, 'desc'],[ 1, 'asc'],[ 5, 'asc'],
                    ],
                });              
                /*posicion button tamaño*/
                table.buttons().container().appendTo('#table-data-escalas-1_wrapper .col-md-6:eq(0)');
                /*posicion button tamaño*/
                /*COLOR FOOTER*/
                $("#footer_11").css('background-color', "#4d7cae");
                $("#footer_12").css('background-color', "#4d7cae");
                $("#footer_13").css('background-color', "#4d7cae");
                $("#footer_14").css('background-color', "#4d7cae");
                $("#footer_15").css('background-color', "#4d7cae");
                $("#footer_16").css('background-color', "#4d7cae");
                $("#footer_17").css('background-color', "#4d7cae");
                $("#footer_18").css('background-color', "#4d7cae");
                /*COLOR FOOTER*/

                table.on('draw', function ()
                {
                    table.columns().indexes().each( function ( idx )
                    {
                    if(idx == 0)
                    {
                        var select = $("#div_option_1").find('select');
                        if ( select.val() === '')
                        {
                          select.empty().append('<option value=""Seleccione/>');
                          table.column(idx, {search:'applied'}).data().unique().sort().each( function(d, j){     select.append( '<option value="'+d+'">'+d+'</option>');      });
                        }
                        $("#option_select_1").selectpicker('refresh');
                    }
                    if(idx == 2)
                    {
                        var select = $("#div_option_2").find('select');
                        if ( select.val() === '')
                        {
                          select.empty().append('<option value=""/>');
                          table.column(idx, {search:'applied'}).data().unique().sort().each( function(d,j){     select.append( '<option value="'+d+'">'+d+'</option>');     });
                        }
                        $("#option_select_2").selectpicker('refresh');
                    }
                    if(idx == 4)
                    {
                        var select = $("#div_option_4").find('select');
                        if ( select.val() === '')
                        {
                          select.empty().append('<option value=""/>');
                          table.column(idx, {search:'applied'}).data().unique().sort().each( function(d, j){     select.append( '<option value="'+d+'">'+d+'</option>');     });
                        }
                        $("#option_select_4").selectpicker('refresh');
                    }
                    if(idx == 6)
                    {
                        var select = $("#div_option_6").find('select');
                        if ( select.val() === '')
                        {
                          select.empty().append('<option value=""/>');
                          table.column(idx, {search:'applied'}).data().unique().sort().each( function(d, j){    select.append( '<option value="'+d+'">'+d+'</option>');     });
                        }
                        $("#option_select_6").selectpicker('refresh');
                    }
                    if(idx == 8)
                    {
                        var select = $("#div_option_8").find('select');
                        if ( select.val() === '')
                        {
                          select.empty().append('<option value=""/>');
                          table.column(idx, {search:'applied'}).data().unique().sort().each( function(d, j){   select.append( '<option value="'+d+'">'+d+'</option>');      });
                        }
                        $("#option_select_8").selectpicker('refresh');
                    }
                    } );
                  } );
            }

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}
function registrar_paquete_descuentos()
{
    
}
