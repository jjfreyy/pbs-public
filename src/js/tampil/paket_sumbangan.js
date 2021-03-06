var total_paket_sumbangan;
var display_per_page = 10;
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
        var id_paket_sumbangan = $(this).data("dialog");
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus paket sumbangan <i>" +convert_number_tocurrency(id_paket_sumbangan)+ "</i>?<br>\
            Jika <i>Paket Sumbangan</i> telah terdaftar pada <i>Souvenir Keluar</i><br>maka data tersebut juga akan terhapus.<br><br>\
            Data yang dihapus tidak dapat dikembalikan!",
            "delete_paket_sumbangan#" +id_paket_sumbangan
        ];
        $(this).after(create_dialog("confirm", data)).next().slideDown();
    });

    $("body").on("click", "a.link_pagination", function() {
        var filter = $(this).data("pagination").split("#")[1];
        var lunas = $(this).data("pagination").split("#")[2];
        var date = get_date_filter();
        var page = parseInt($(this).data("pagination").split("#")[0]);
        var data = {
            "filter": filter,
            "lunas": lunas,
            "tgl1": date.tgl1,
            "tgl2": date.tgl2,
            "page": page,
            "display_per_page": display_per_page
        };
        get_daftar_paket_sumbangan(data);
    });
});

function delete_paket_sumbangan(data) {
    var ajax = get_ajax_response("tampil/paket_sumbangan/delete", "post", "hapus_paket_sumbangan", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_paket_sumbangan(data) {
    var ajax = get_ajax_response("tampil/paket_sumbangan/get_paket_sumbangan_list", "get", "daftar_paket_sumbangan", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id_paket_sumbangan = response[i].id_paket_sumbangan;
            var kode_donatur = response[i].kode_donatur;
            var nama_donatur = response[i].nama_donatur;
            var kode_kolektor = response[i].kode_kolektor;
            var nama_kolektor = response[i].nama_kolektor;
            var kode_paket = response[i].kode_paket;
            var nama_paket = response[i].nama_paket;
            var nilai_paket = if_empty_then(response[i].nilai_paket, "number", false, "???");
            var jumlah_paket = response[i].jumlah_paket;
            var total_donasi = convert_number_tocurrency(response[i].total_donasi);
            var sisa = if_empty_then(response[i].sisa, "number", false, "???");;
            var ket = if_empty_then(response[i].ket);
            var tgl_jatuh_tempo = if_empty_then(response[i].tgl_jatuh_tempo, "date", true, "???");
            var biaowen_list = response[i].biaowen_list === null ? Array() : response[i].biaowen_list.split("#");
            var biaowen_list1 = "<select>";
            biaowen_list.forEach(biaowen => {biaowen_list1 += "<option>" +biaowen+ "</option>"});
            biaowen_list1 += "</select>";

            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "input/paket_sumbangan?id=" +id_paket_sumbangan+ "'>Edit</a> | \
                    " +(lev == 0 ? "" : "<a href='#' class='delete_link' data-dialog='" +id_paket_sumbangan+ "'>Hapus</a> | ")+ "\
                    <a target='_blank' href='" +base_url+ "tampil/paket_sumbangan/print_paket_sumbangan?id=" +id_paket_sumbangan+ "'>Print</a>\
                </td> \
                <td>" +kode_donatur+ "</td> \
                <td>" +nama_donatur+ "</td> \
                <td>" +kode_kolektor+ "</td> \
                <td>" +nama_kolektor+ "</td> \
                <td>" +kode_paket+ "</td> \
                <td>" +nama_paket+ "</td> \
                <td>" +nilai_paket+ "</td> \
                <td>" +jumlah_paket+ "</td> \
                <td>" +total_donasi+ "</td> \
                <td>" +sisa+ "</td> \
                <td>" +ket+ "</td> \
                <td>" +tgl_jatuh_tempo+ "</td> \
                <td>" +biaowen_list1+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, total_paket_sumbangan, display_per_page, "#" +data.filter+ "#" +data.lunas);
        });
    });
}

function get_total_paket_sumbangan(data) {
    var ajax = get_ajax_response("tampil/paket_sumbangan/get_paket_sumbangan_list", "get", "total_paket_sumbangan", data);
    $.when(ajax).done(function(response) {
        total_paket_sumbangan = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Menemukan " +convert_number_tocurrency(total_paket_sumbangan)+ " baris..").fadeIn()
        });
    });
}

function init_filter() {
    var date = get_date_filter();
    var data = {
        "filter": $("#filter").val(),
        "lunas": $("#lunas").val(),
        "tgl1": date.tgl1,
        "tgl2": date.tgl2
    }
    get_total_paket_sumbangan(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_paket_sumbangan(data);
}

function init_table_style() {
    var data = {
        "template_columns": "1fr 1fr",
        "padding_left": "110",
    };
    get_responsive_style("table", "table.tb_daftar", data);
    data = {
        "style": " \
        @media only screen and (max-width:835px) { \
            table.tb_daftar select { width: 250px !important; } \
        } \
        @media only screen and (max-width:730px) { \
            table.tb_daftar select { width: 200px !important; } \
        }"
    };
    get_responsive_style("custom", "", data)
}
