var total_sumbangan;
var display_per_page = 20;
var lev = $("#key").val();
$tbody = $("table.tb_daftar tbody");

$(document).ready(function() {
    init_table_style();
    prepare_date_filter();
    init_filter();
    prepare_biaowen_dialog();

    $("span.search_icon").on("click", function() {
        init_filter();
    });

    $("input#filter").on("keyup", function(e) {
        if (e.keyCode === 13) {
            init_filter();
        }
    });

    $("body").on("click", "a.delete_link", function() {
        var id_sumbangan = $(this).data("dialog");
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus sumbangan <i>" +convert_number_tocurrency(id_sumbangan)+ "</i>?<br>Data yang dihapus tidak dapat dikembalikan!",
            "delete_sumbangan#" +id_sumbangan
        ];
        $(this).after(create_dialog("confirm", data)).next().slideDown();
    });

    $("body").on("click", "a.link_pagination", function() {
        var filter = $(this).data("pagination").split("#")[1];
        var date = get_date_filter();
        var page = parseInt($(this).data("pagination").split("#")[0]);
        var data = {
            "filter": filter,
            "page": page,
            "tgl1": date.tgl1,
            "tgl2": date.tgl2,
            "display_per_page": display_per_page
        };
        get_daftar_sumbangan(data);
    });
});

function delete_sumbangan(data) {
    var ajax = get_ajax_response("tampil/sumbangan/delete", "post", "hapus_sumbangan", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_sumbangan(data) {
    var ajax = get_ajax_response("tampil/sumbangan/get_sumbangan_list", "get", "daftar_sumbangan", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id_sumbangan = response[i].id_sumbangan;
            var no_kwitansi = response[i].no_kwitansi;
            var nama_donatur = response[i].nama_donatur;
            var tgl_donasi = format_date(response[i].tgl_donasi);
            var nama_paket = response[i].nama_paket;
            var jumlah_donasi = convert_number_tocurrency(response[i].jumlah_donasi);
            var metode_pembayaran = response[i].metode_pembayaran;
            var ket = if_empty_then(response[i].ket);
        
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "input/sumbangan?id=" +id_sumbangan+ "'>Edit</a> | \
                    " +(lev == 0 ? "" : "<a href='#' class='delete_link' data-dialog='" +id_sumbangan+ "'>Hapus</a> | ")+ "\
                    <a target='_blank' href='" +base_url+ "tampil/sumbangan/print_sumbangan?id=" +id_sumbangan+ "'>Print</a> \
                </td> \
                <td data-id-sumbangan='" +id_sumbangan+ "' class='link_clickable'>" +no_kwitansi+ "</td> \
                <td>" +nama_donatur+ "</td> \
                <td>" +tgl_donasi+ "</td> \
                <td>" +nama_paket+ "</td> \
                <td>" +jumlah_donasi+ "</td> \
                <td>" +metode_pembayaran+ "</td> \
                <td>" +ket+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, total_sumbangan, display_per_page, "#" +data.filter);
        });
    });
}

function get_total_sumbangan(data) {
    var ajax = get_ajax_response("tampil/sumbangan/get_sumbangan_list", "get", "total_sumbangan", data);
    $.when(ajax).done(function(response) {
        total_sumbangan = response;
        $("table.tb_daftar caption").fadeOut(400, function() {
            $(this).html("Menemukan " +convert_number_tocurrency(total_sumbangan)+ " baris..").fadeIn();
        });
    });
}

function init_filter() {
    var date = get_date_filter();
    var data = {
        "filter": $("#filter").val(),
        "tgl1": date.tgl1,
        "tgl2": date.tgl2,
    };
    get_total_sumbangan(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_sumbangan(data);
}

function init_table_style() {
    var data = {
        "template_columns": "1fr 1fr",
        "padding_left": "120"
    };
    get_responsive_style("table", "table.tb_main", data);
    data = {
        "padding_left": "115"
    }
    get_responsive_style("table", "table.tb_dialog", data);
    $("div.dialog_background").remove();
}

function prepare_biaowen_dialog() {
    $("body").on("click", ".link_clickable", function() {
        var data = {
            "id": $(this).data("id-sumbangan")
        };
        var ajax = get_ajax_response("tampil/sumbangan/get_sumbangan1_list", "get", "daftar_sumbangan1", data);
        $.when(ajax).done(function(response) {
            $("div.dialog_background").remove();
            var dialog = " \
            <div class='dialog_background'> \
                <div class='dialog' style='display:none'> \
                    <div class='dialog_header'> \
                        <span class='dialog_close_btn' title='Tutup Dialog'></span> \
                    </div> \
                    <div class='dialog_body'>";
            
            var length = response.length;
            if (length > 0) {
                dialog += " \
                <table class='tb_daftar tb_dialog'> \
                    <colgroup> \
                        <col span='1' width='50px'> \
                        <col span='1' width='150px'> \
                        <col span='1' width='50px'> \
                        <col span='1' width='50px'> \
                        <col span='1' width='100px'> \
                    </colgroup> \
                    <thead> \
                        <tr> \
                            <th>No.</th> \
                            <th>Biaowen</th> \
                            <th>Lunas</th> \
                            <th>Bakar</th> \
                            <th>Tanggal Bakar</th> \
                        </tr> \
                    </thead> \
                    <tbody>";

                for (var i = 0; i < length; i++) {
                    var no = i + 1;
                    var biaowen = response[i].biaowen;
                    var lunas = response[i].lunas == 1 ? "&#10003" : "&#215;";
                    var bakar = response[i].bakar == 1 ? "&#10003" : "&#215;";
                    var tgl_bakar = response[i].tgl_bakar === null ? "-" : format_date(response[i].tgl_bakar);

                    dialog += " \
                    <tr class='border'> \
                        <td>" +no+ "</td> \
                        <td>" +biaowen+ "</td> \
                        <td>" +lunas+ "</td> \
                        <td>" +bakar+ "</td> \
                        <td>" +tgl_bakar+ "</td> \
                    </tr>";
                }
                dialog += " \
                    </tbody> \
                </table>";
            } else {
                dialog += "<h2 style='text-align:center;display:grid;align-content:center;'>Data biaowen tidak dapat ditemukan.</h2>";
            }

            dialog += "</div></div></div>";
            $("body").append(dialog);
            $("div.dialog").slideDown(400);
        });
    });

    prepare_close_btn_dialog();
}