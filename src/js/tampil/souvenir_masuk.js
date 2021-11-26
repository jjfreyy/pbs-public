var total_souvenir1;
var display_per_page = 20;
var lev = $("#key").val();
$tbody = $("table.tb_daftar tbody");

$(document).ready(function() {
    init_table_style();
    prepare_date_filter();
    init_filter();

    $("span.search_icon").on("click", function() {
        init_filter();
    });

    $("input#filter").on("keyup", function(e) {
        if (e.keyCode === 13) {
            init_filter();
        }
    });

    $("body").on("click", "a.delete_link", function() {
        var id = $(this).data("dialog");
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus souvenir masuk <i>" +convert_number_tocurrency(id)+ "</i>?<br>Data yang dihapus tidak dapat dikembalikan!",
            "delete_souvenir_masuk#" +id
        ];
        $(this).after(create_dialog("confirm", data)).next().slideDown();
    })

    $("body").on("click", "a.link_pagination", function() {
        var filter = $(this).data("pagination").split("#")[1];
        var date = get_date_filter();
        var page = parseInt($(this).data("pagination").split("#")[0]);
        var data = {
            "filter": filter,
            "tgl1": date.tgl1,
            "tgl2": date.tgl2,
            "page": page,
            "display_per_page": display_per_page
        };
        get_daftar_souvenir1(data);
    });
});

function delete_souvenir_masuk(data) {
    var ajax = get_ajax_response("tampil/souvenir_masuk/delete", "post", "hapus_souvenir1", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_souvenir1(data) {
    var ajax = get_ajax_response("tampil/souvenir_masuk/get_souvenir1_list", "get", "daftar_souvenir1", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id = response[i].id;
            var kode_souvenir = response[i].kode_souvenir;
            var nama = response[i].nama;
            var stok_masuk = convert_number_tocurrency(response[i].stok_masuk);
            var ket = if_empty_then(response[i].ket);
            var tgl_input = format_date(response[i].tgl_input);
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "input/souvenir_masuk?id=" +id+ "'>Edit</a>\
                    " +(lev == 0 ? "" : " | <a href='#' class='delete_link' data-dialog='" +id+ "'>Hapus</a>")+ " \
                </td> \
                <td>" +kode_souvenir+ "</td> \
                <td>" +nama+ "</td> \
                <td>" +stok_masuk+ "</td> \
                <td>" +ket+ "</td> \
                <td>" +tgl_input+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, total_souvenir1, display_per_page, "#" +data.filter);
        });
    });
}

function get_total_souvenir1(data) {
    var ajax = get_ajax_response("tampil/souvenir_masuk/get_souvenir1_list", "get", "total_souvenir1", data);
    $.when(ajax).done(function(response) {
        total_souvenir1 = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Menemukan " +convert_number_tocurrency(total_souvenir1)+ " baris..").fadeIn();
        });
    });
}

function init_filter() {
    var date = get_date_filter();
    var data = {
        "filter": $("#filter").val(),
        "tgl1": date.tgl1,
        "tgl2": date.tgl2
    };
    get_total_souvenir1(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_souvenir1(data);
}

function init_table_style() {
    var data = {
        "padding_left": "120"
    }
    get_responsive_style("table", "table.tb_daftar", data);
}
