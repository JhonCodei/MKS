//CODE
list_usuarios();
list_menu();

function mantenimiento_modal(go) {

    if (go == 1) {
        // list_menu();
        $("#btn_modal_events").removeAttr("onclick").attr("onclick", "insert_usuarios();").text("Registrar");
        $("#users_modal").modal();
    } else if (go == 2) {
        $("#btn_modal_events").removeAttr("onclick").attr("onclick", "update_usuarios();").text("Actualizar");
        $("#users_modal").modal();
        $("#usuario").attr("readonly", true);

    } else if (go == 0) {
        empty_input();
    }
}

function list_menu() {
    var controller = __AJAX__ + "usuarios-list_menu",
        connect, postData;


    postData = "c=x";
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            $("#menu_array").html(connect.responseText);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function search_user(usuario) {
    var controller = __AJAX__ + "usuarios-search_user",
        connect, postData;


    postData = "usuario=" + usuario;
    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            var data = connect.responseText

            var explode = data.split('~~');

            $("#nombres").val(explode[2]);
            $("#root").val(explode[3]).change();
            $("#usuario").val(explode[1]);
            $("#password").val(explode[7]);
            $("#codigo").val(explode[0]);
            $("#portafolio").val(explode[5]);
            $("#region").val(explode[4]).change();
            $("#menu_array").val(explode[6]).change();
            $("#tipo_view").val(explode[8]).change();
            mantenimiento_modal(2);

        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function update_usuarios() {
    var controller = __AJAX__ + "usuarios-update_usuarios",
        connect, postData;

    var nombres = $("#nombres").val();
    var root = $("#root").val();
    var usuario = $("#usuario").val();
    var password = $("#password").val();
    var codigo = $("#codigo").val();
    var portafolio = $("#portafolio").val();
    var region = $("#region").val();
    var menu_array = $("#menu_array").val();
    var tipo_view = $("#tipo_view").val();

    postData = "nombres=" + nombres + "&root=" + root + "&usuario=" + usuario +
        "&password=" + password + "&codigo=" + codigo + "&portafolio=" + portafolio +
        "&region=" + region + "&menu_array=" + menu_array + "&tipo_view=" + tipo_view;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            if (parseInt(connect.responseText) == 1) //ok
            {
                swal("Correcto", "Usuario " + usuario + " actualizado correctamente.", "success");
                list_usuarios();
                empty_input();
            } else {
                swal("Error", connect.responseText, "error");
                $("#users_modal").modal();
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function list_usuarios() {
    var controller = __AJAX__ + "usuarios-list_usuarios",
        connect, postData;

    postData = "x=x";

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();
            //console.log(connect.responseText);
            if (parseInt(connect.responseText) == 0) //error
            {
                swal("Error", ' . . . ', "error");
            } else {

                var info = connect.responseText;

                var info_explo = info.split('||');

                $("#div_table_usuarios").html(info_explo[0]);

                var json_data = JSON.parse(info_explo[1]);

                //console.log(json_data);

                var table = $("#table-list-usuarios").DataTable({
                    dom: 'frtip',
                    lengthChange: false,
                    destroy: true,
                    data: json_data.data,
                    columns: [
                        { "data": "codigo", className: "text-center" },
                        { "data": "usuario", className: "text-center" },
                        { "data": "nombre", className: "text-left" },
                        { "data": "tipo_user", className: "text-center" },
                        { "data": "region", className: "text-center" },
                        { "data": "region_visita", className: "text-center" },
                        { "data": "portafolio", className: "text-center" },
                        { "data": "menu", className: "text-center" },
                        { "data": "tipo_view", className: "text-center" },
                        { "data": "estado", className: "text-center" },
                        { "data": "btn1", className: "text-center" }
                    ],
                    responsive: true,
                    bLengthChange: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: false,
                    //order: [[$('th.this_order_reg').index(), 'desc']]
                });
                $("#table-list-usuarios_filter").addClass("pull-left");
                $("#table-list-usuarios_paginate").addClass("pull-left");
                $("#table-list-usuarios tbody").css("font-size", "1em").css("color", "black");
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function insert_usuarios() {
    var controller = __AJAX__ + "usuarios-insert_usuarios",
        connect, postData;

    var nombres = $("#nombres").val();
    var root = $("#root").val();
    var usuario = $("#usuario").val();
    var password = $("#password").val();
    var codigo = $("#codigo").val();
    var portafolio = $("#portafolio").val();
    var region = $("#region").val();
    var menu_array = $("#menu_array").val();
    var tipo_view = $("#tipo_view").val();

    postData = "nombres=" + nombres + "&root=" + root + "&usuario=" + usuario +
        "&password=" + password + "&codigo=" + codigo + "&portafolio=" + portafolio +
        "&region=" + region + "&menu_array=" + menu_array + "&tipo_view=" + tipo_view;

    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    connect.onreadystatechange = function() {
        if (connect.readyState == 4 && connect.status == 200) {
            $("#loader").empty();

            if (parseInt(connect.responseText) == 1) //ok
            {
                swal("Correcto", "Usuario " + usuario + " registrado correctamente.", "success");
                list_usuarios();
                empty_input();
            } else {
                swal("Error", connect.responseText, "error");
                $("#users_modal").modal();
            }
        } else if (connect.readyState != 4) {
            $("#loader").html(loader());
        }
    }
    connect.open('POST', controller, true);
    connect.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    connect.send(postData);
}

function empty_input() {
    $("#nombres").val('');
    $("#root").prop('selectedIndex', 0);
    $("#usuario").val('');
    $("#password").val('');
    $("#codigo").val('');
    $("#portafolio").val('');
    $("#region").val('');
    //$("#menu_array").val('');
}