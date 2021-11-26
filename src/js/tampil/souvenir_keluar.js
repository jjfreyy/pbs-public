var total_souvenir2;
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
            "Apakah anda yakin ingin menghapus souvenir keluar <i>" +convert_number_tocurrency(id)+ "</i>?<br>\
            Jika <i>Paket Sumbangan</i> telah terdaftar pada <i>Souvenir Keluar</i> ini,<br>maka akan dianggap <i>Paket Sumbangan</i> tersebut tidak \
            pernah menerima <i>Souvenir Keluar</i>.<br><br>\
            Data yang dihapus tidak dapat dikembalikan!",
            "delete_souvenir_keluar#" +id
        ];
        $(this).after(create_dialog("confirm", data)).next().slideDown();
    });

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
        get_daftar_souvenir2(data);
    });
});

function delete_souvenir_keluar(data) {
    var ajax = get_ajax_response("tampil/souvenir_keluar/delete", "post", "hapus_souvenir2", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_souvenir2(data) {
    var ajax = get_ajax_response("tampil/souvenir_keluar/get_souvenir2_list", "get", "daftar_souvenir2", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id = response[i].id;
            var kode_souvenir = response[i].kode_souvenir;
            var nama = response[i].nama;
            var penerima_souvenir = response[i].penerima_souvenir;
            var stok_keluar = convert_number_tocurrency(response[i].stok_keluar);
            var ket = if_empty_then(response[i].ket);
            var tgl_serah = format_date(response[i].tgl_serah);
            
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "input/souvenir_keluar?id=" +id+ "'>Edit</a>\
                    " +(lev == 0 ? "" : " | <a href='#' class='delete_link' data-dialog='" +id+ "'>Hapus</a>")+ " \
                </td> \
                <td>" +kode_souvenir+ "</td> \
                <td>" +nama+ "</td> \
                <td>" +penerima_souvenir+ "</td> \
                <td>" +stok_keluar+ "</td> \
                <td>" +ket+ "</td> \
                <td>" +tgl_serah+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, total_souvenir2, display_per_page, "#" +data.filter);
        });
    });
}

function get_total_souvenir2(data) {
    var ajax = get_ajax_response("tampil/souvenir_keluar/get_souvenir2_list", "get", "total_souvenir2", data);
    $.when(ajax).done(function(response) {
        total_souvenir2 = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Menemukan " +convert_number_tocurrency(total_souvenir2)+ " baris..").fadeIn();
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
    get_total_souvenir2(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_souvenir2(data);
}

function init_table_style() {
    var data = {
        "padding_left": "145"
    };
    get_responsive_style("table", "table.tb_daftar", data);
}
