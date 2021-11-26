var jumlah_paket;
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
        var kode_paket = $(this).data("dialog").split("#")[1];
        data = [
            "warning",
            "Apakah anda yakin ingin menghapus paket <i>" +kode_paket+ "</i>?<br>Data yang dihapus tidak dapat dikembalikan!", 
            "delete_paket#" +$(this).data("dialog")
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
        get_daftar_paket(data);
    });
});

function delete_paket(data) {
    var ajax = get_ajax_response("daftar/paket/delete", "post", "hapus_paket", {"id": data[1]});
    process_ajax(ajax, "init_filter");
}

function get_daftar_paket(data) {
    var ajax = get_ajax_response("daftar/paket/get_paket_list", "get", "daftar_paket", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        for(var i = 0; i < length; i++) {
            var no = data.page * display_per_page + i + 1;
            var id_paket = response[i].id_paket;
            var kode_paket = response[i].kode_paket;
            var nama_paket = response[i].nama_paket;
            var nilai_paket = if_empty_then(response[i].nilai_paket, "number", false, "∞");
            var periode = response[i].periode === null ? "∞" : response[i].periode.split(" ");
            if (periode !== "∞") {
                switch (periode[1]) {   
                    case "H": periode = periode[0]+ " Hari"; break;
                    case "B": periode = periode[0]+ " Bulan"; break;
                    case "T": periode = periode[0]+ " Tahun"; break;
                }
            }
            var bank_list = response[i].bank_list === "" ? "-" : response[i].bank_list.split("#");
            var bank_list1 = "-";
            if (bank_list !== "-") {
                bank_list1 = "<select>";
                var length1 = bank_list.length;
                for(var j = 0; j < length1; j++) {
                    var an = bank_list[j].split("|")[1];
                    var no_rek = bank_list[j].split("|")[2];
                    bank_list1 += "<option>" +an+ " / " +no_rek+ "</option>";
                }
                bank_list1 += "</select>";
            }
            
            var tr = " \
            <tr class='border'> \
                <td>" +no+ "</td> \
                <td> \
                    <a href='" +base_url+ "master/paket?id=" +id_paket+ "'>Edit</a>\
                    " +(lev == 0 ? "" : " | <a href='#' class='delete_link' data-dialog='" +id_paket+ "#" +kode_paket+ "'>Hapus</a>")+ " \
                </td> \
                <td>" +kode_paket+ "</td> \
                <td>" +nama_paket+ "</td> \
                <td>" +nilai_paket+ "</td> \
                <td>" +periode+ "</td> \
                <td>" +bank_list1+ "</td> \
            </tr>";
            $tbody.append(tr);
        } 

        $tbody.fadeIn(400, function() {
            create_paginations(data.page, jumlah_paket, display_per_page, "#" +data.filter);
        });
    });
}

function get_total_paket(data) {
    var ajax = get_ajax_response("daftar/paket/get_paket_list", "get", "total_paket", data);
    $.when(ajax).done(function(response) {
        jumlah_paket = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Jumlah Paket: " +convert_number_tocurrency(jumlah_paket)).fadeIn();
        });
    });
}

function init_filter() {
    var data = { "filter": $("input.search_field").val() };
    get_total_paket(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_paket(data);
}

function init_table_style() {
    var data = {
        "padding_left": "95",
    };
    get_responsive_style("table", "table.tb_daftar", data);
}
