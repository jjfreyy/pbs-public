var jumlah_donatur;
var display_per_page = 10;
var lev = $("#key").val();
$tbody = $("table.tb_daftar tbody");

$(document).ready(function() {
    init_table_style();
    init_filter();

    $("#donatur, #tgl_terakhir").on("keyup", function(e) {
        if (e.keyCode === 13) {
            init_filter();
        }
    })

    $("span.search_icon").on("click", function() {
        init_filter();
    });

    $("body").on("click", "a.delete_link", function() {
        var kode_donatur = $(this).data("dialog").split("#")[1];
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus donatur <i>" +kode_donatur+ "</i>?<br>Data yang dihapus tidak dapat dikembalikan!", 
            "delete_donatur#" +$(this).data("dialog")
        ];
        $(this).after(create_dialog("confirm", data)).next().slideDown();
    });

    $("body").on("click", "a.link_pagination", function() {
        var filter = $(this).data("pagination").split("#")[1].split(";");
        var page = parseInt($(this).data("pagination").split("#")[0]);
        var data = {
            "donatur": filter[0],
            "tgl_terakhir": filter[1],
            "page": page,
            "display_per_page": display_per_page
        };
        get_daftar_donatur(data);
    });
});

function delete_donatur(data) {
    var ajax = get_ajax_response("daftar/donatur/delete", "post", "hapus_donatur", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_donatur(data) {
    var ajax = get_ajax_response("daftar/donatur/get_donatur_list", "get", "daftar_donatur", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id_donatur = response[i].id_donatur;
            var kode_donatur = response[i].kode_donatur;
            var nama_id = response[i].nama_id;
            var nama_cn = if_empty_then(response[i].nama_cn);
            var alamat = if_empty_then(response[i].alamat);
            var kota_lahir = if_empty_then(response[i].kota_lahir);
            var tgl_lahir = if_empty_then(response[i].tgl_lahir, "date");
            var kota_domisili = if_empty_then(response[i].kota_domisili);
            var no_hp1 = if_empty_then(response[i].no_hp1);
            var no_hp2 = if_empty_then(response[i].no_hp2);
            var email = if_empty_then(response[i].email);
            var ket = if_empty_then(response[i].ket);
            var tgl_gabung = if_empty_then(response[i].tgl_gabung, "date");
            var jmlh_paket = convert_number_tocurrency(response[i].jmlh_paket);
            var total_nilai_paket = convert_number_tocurrency(response[i].total_nilai_paket);
            var jumlah_biaowen = convert_number_tocurrency(response[i].jumlah_biaowen);
            var pembayaran_terakhir = format_date(response[i].pembayaran_terakhir);
        
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "master/donatur?id="+id_donatur+"'>Edit</a>\
                    " +(lev == 0 ? "" : " | <a href='#' class='delete_link' data-dialog='" +id_donatur+ "#" +kode_donatur+ "'>Hapus</a>")+ " \
                </td> \
                <td>" +kode_donatur+ "</td> \
                <td>" +nama_id+ "</td><td>" +nama_cn+ "</td><td>" +alamat+ "</td> \
                <td>" +kota_lahir+ "</td><td>" +tgl_lahir+ "</td> \
                <td>" +kota_domisili+ "</td> \
                <td>" +no_hp1+ "</td> \
                <td>" +no_hp2+ "</td> \
                <td>" +email+ "</td> \
                <td>" +ket+ "</td> \
                <td>" +tgl_gabung+ "</td> \
                <td>" +jmlh_paket+ "</td> \
                <td>" +total_nilai_paket+ "</td> \
                <td>" +jumlah_biaowen+ "</td> \
                <td>" +pembayaran_terakhir+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, jumlah_donatur, display_per_page, "#" +data.donatur+ ";" +data.tgl_terakhir);
        });
    });
}

function get_total_donatur(data) {
    var ajax = get_ajax_response("daftar/donatur/get_donatur_list", "get", "total_donatur", data);
    $.when(ajax).done(function(response) {
        jumlah_donatur = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Jumlah Donatur: " +convert_number_tocurrency(jumlah_donatur)).fadeIn()
        });
    });
}

function init_filter() {
    var data = {
        "donatur": $("#donatur").val(),
        "tgl_terakhir": $("#tgl_terakhir").val()
    };
    get_total_donatur(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_donatur(data);
}

function init_table_style() {
    var data = {
        "template_columns": "1fr 1fr",
        "padding_left": "155"
    };
    get_responsive_style("table", "table.tb_daftar", data);
}
