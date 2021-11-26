var jumlah_kolektor;
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
        var kode_kolektor = $(this).data("dialog").split("#")[1];
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus kolektor <i>" +kode_kolektor+ "</i>?<br>Data yang dihapus tidak dapat dikembalikan!", 
            "delete_kolektor#" +$(this).data("dialog")
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
        get_daftar_kolektor(data);
    });
});

function delete_kolektor(data) {
    var ajax = get_ajax_response("daftar/kolektor/delete", "post", "hapus_kolektor", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_kolektor(data) {
    var ajax = get_ajax_response("daftar/kolektor/get_kolektor_list", "get", "daftar_kolektor", data);
    $.when(ajax).done(function(response){
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1 + 1000;
            var id_kolektor = response[i].id_kolektor;
            var kode_kolektor = response[i].kode_kolektor;
            var nama = response[i].nama;
            var no_hp1 = if_empty_then(response[i].no_hp1);
            var no_hp2 = if_empty_then(response[i].no_hp2);
            var email = if_empty_then(response[i].email);
            var ket = if_empty_then(response[i].ket);
            var jmlh_paket = response[i].jmlh_paket;
            var total_nilai_paket = convert_number_tocurrency(response[i].total_nilai_paket);
            var jumlah_donatur = response[i].jumlah_donatur;
        
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "master/kolektor?id=" +id_kolektor+ "'>Edit</a>\
                    " +(lev == 0 ? "" : " | <a href='#' class='delete_link' data-dialog='" +id_kolektor+ "#" +kode_kolektor+ "'>Hapus</a>")+ " \
                </td> \
                <td>" +kode_kolektor+ "</td> \
                <td>" +nama+ "</td> \
                <td>" +no_hp1+ "</td> \
                <td>" +no_hp2+ "</td> \
                <td>" +email+ "</td> \
                <td>" +ket+ "</td> \
                <td>" +jmlh_paket+ "</td> \
                <td>" +total_nilai_paket+ "</td> \
                <td>" +jumlah_donatur+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, jumlah_kolektor, display_per_page, "#" +data.filter);
        });
    });
}

function get_total_kolektor(data) {
    var ajax = get_ajax_response("daftar/kolektor/get_kolektor_list", "get", "total_kolektor", data);
    $.when(ajax).done(function(response) {
        jumlah_kolektor = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Jumlah Kolektor: " +convert_number_tocurrency(jumlah_kolektor)).fadeIn()
        });
    });
}

function init_filter() {
    var data = {
        "filter": $("input.search_field").val()
    };
    get_total_kolektor(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_kolektor(data);
}

function init_table_style() {
    var data = {
        "template_columns": "1fr 1fr",
        "padding_left": "125",
    };
    get_responsive_style("table", "table.tb_daftar", data);
}
