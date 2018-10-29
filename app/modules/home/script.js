//CODE

function _listar_ruteo()
{
    var controller = __AJAX__ + "ruteo-_listar_ruteo", postData = new FormData();

				var fecha = __day_now__();

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
            $("#content-div").html(response);

            var table = $("#table_listado_ruteos").DataTable({
                dom: "rtip",
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