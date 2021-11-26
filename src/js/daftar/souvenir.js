var jumlah_souvenir;
var display_per_page = 20;
var lev = $("#key").val();
$tbody = $("table.tb_daftar tbody");

$(document).ready(function() {
    init_table_style();
    init_filter();

    $("span.search_icon").on("click", function() {
        init_filter();
    });

    $("input.search_field").on("keyup", function(e) {
        if (e.keyCode === 13) {
            init_filter();
        }
    });

    $("body").on("click", "a.delete_link", function() {
        var kode_souvenir = $(this).data("dialog").split("#")[1];
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus souvenir <i>" +kode_souvenir+ "</i>?<br>Data yang dihapus tidak dapat dikembalikan!", 
            "delete_souvenir#" +$(this).data("dialog")
        ];
        $(this).after(create_dialog("confirm", data)).next().slideDown();
    });

    $("body").on("click", "a.link_pagination", function() {
        var filter = $(this).data("pagination").split("#")[1];
        var page = parseInt($(this).data("pagination").split("#")[0]);
        var data = {
            "filter": filter,
            "page": page,
            "display_per_page": display_per_page
        };
        get_daftar_souvenir(data);
    });
});

function delete_souvenir(data) {
    var ajax = get_ajax_response("daftar/souvenir/delete", "post", "hapus_souvenir", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_souvenir(data) {
    var ajax = get_ajax_response("daftar/souvenir/get_souvenir_list", "get", "daftar_souvenir", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id_souvenir = response[i].id_souvenir;
            var kode_souvenir = response[i].kode_souvenir;
            var nama = response[i].nama;
            var stok_awal = convert_number_tocurrency(response[i].stok_awal);
            var stok_masuk = convert_number_tocurrency(response[i].stok_masuk);
            var stok_keluar = convert_number_tocurrency(response[i].stok_keluar);
            var stok_akhir = convert_number_tocurrency(response[i].stok_akhir);
            
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "master/souvenir?id=" +id_souvenir+ "'>Edit</a>\
                    " +(lev == 0 ? "" : " | <a href='#' class='delete_link' data-dialog='" +id_souvenir+ "#" +kode_souvenir+ "'>Hapus</a>")+ " \
                </td> \
                <td>" +kode_souvenir+ "</td> \
                <td>" +nama+ "</td> \
                <td>" +stok_awal+ "</td> \
                <td>" +stok_masuk+ "</td> \
                <td>" +stok_keluar+ "</td> \
                <td>" +stok_akhir+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, jumlah_souvenir, display_per_page, "#" +data.filter);
        });
    });
}

function get_total_souvenir(data) {
    var ajax = get_ajax_response("daftar/souvenir/get_souvenir_list", "get", "total_souvenir", data);
    $.when(ajax).done(function(response) {
        jumlah_souvenir = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Jumlah Souvenir: " +convert_number_tocurrency(jumlah_souvenir)).fadeIn();
        });
    });
}

function init_filter() {
    var data = { "filter": $("input.search_field").val() };
    get_total_souvenir(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_souvenir(data);
}

function init_table_style() {
    var data = {
        "padding_left": "120",
    };
    get_responsive_style("table", "table.tb_daftar", data);
}
