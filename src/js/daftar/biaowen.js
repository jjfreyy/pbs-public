var jumlah_biaowen;
var display_per_page = 20;
$tbody = $("table.tb_daftar tbody");

$(document).ready(function() {
    init_table_style();
    init_filter();

    $("span.search_icon").on("click", function() {
        init_filter();
    });

    $("#nama_paket, #biaowen").on("keyup", function(e) {
        if (e.keyCode === 13) init_filter();
    });

    $("button.i_btn").on("click", function() {
        var jumlah_bakar_biaowen = $(".cb_bakar:checked").length;
        if (jumlah_bakar_biaowen > 0) {
            data = [
                "info",
                +convert_number_tocurrency($(".cb_bakar:checked").length)+ " biaowen akan dibakar.<br>Apakah anda yakin?",
                "bakar_biaowen"
            ];
            $(this).after(create_dialog("confirm", data, "left: unset;top:40px")).next().slideDown();
        }
        else {
            $("body").append(create_dialog("simple", ["error", "Silakan centang biaowen yang akan dibakar."]));
            $("div.simple_dialog").slideDown();
        }
    });

    $("body").on("click", "a.link_pagination", function() {
        var filter = $(this).data("pagination").split("#")[1].split(";");
        var page = parseInt($(this).data("pagination").split("#")[0]);
        var data = {
            "nama_paket": filter[0],
            "biaowen": filter[1],
            "lunas": filter[2],
            "bakar": filter[3],
            "page": page,
            "display_per_page": display_per_page
        };
        get_daftar_biaowen(data);
    });
});

function bakar_biaowen(data) {
    var data = [];
    $(".cb_bakar:checked").each(function() {
        var biaowen = $(this).data("biaowen").split("#");
        data.push({"no":biaowen[0], "id_sumbangan":biaowen[1], "biaowen": biaowen[2]});
    });
    var ajax = get_ajax_response("daftar/biaowen/bakar_biaowen", "post", "bakar_biaowen", data); 
    process_ajax(ajax, "init_filter");
}

function get_daftar_biaowen(data) {
    var ajax = get_ajax_response("daftar/biaowen/get_biaowen_list", "get", "daftar_biaowen", data);
    $.when(ajax).done(function(response) {
        $("div.pagination").empty();
        $tbody.empty().css("display", "none");
        var length = response.length;
        if (length > 0) {
            var c = 0;
            for(var i = 0; i < length; i++) {
                var no = data.page * display_per_page + i + 1;
                var id_sumbangan = response[i].id_sumbangan;
                var id_paket_sumbangan = response[i].id_paket_sumbangan;
                var nama_donatur = response[i].nama_donatur;
                var nama_paket = response[i].nama_paket;
                var biaowen = response[i].biaowen;
                var lunas = response[i].lunas == 1 ? "&#10003" : "&#215;";
                var bakar = response[i].bakar == 1 ? "&#10003" : "&#215";
                var tgl_bakar = response[i].tgl_bakar === null ? "-" : format_date(response[i].tgl_bakar);
                var total_donasi = convert_number_tocurrency(response[i].total_donasi);
                
                var add_border = response[i+1] == undefined || id_paket_sumbangan != response[i+1].id_paket_sumbangan ? " class='border'" : ""; 
                var opacity = response[i-1] === undefined || id_paket_sumbangan != response[i-1].id_paket_sumbangan ? 1 : 0;
                var cb_bakar = response[i].lunas == 0 || response[i].bakar == 1 ? "&#215;" : 
                "<input type='checkbox' class='cb_bakar' data-biaowen='" +no+ "#" +id_sumbangan+ "#" +biaowen+ "'/>";

                var tr = " \
                <tr" +add_border+ "> \
                    <td>" +no+ "</td> \
                    <td style='opacity:" +opacity+ "'>" +id_paket_sumbangan+ "#" +nama_paket+ "</td> \
                    <td style='opacity:" +opacity+ "'>" +nama_donatur+ "</td> \
                    <td>" +biaowen+ "</td> \
                    <td>" +lunas+ "</td> \
                    <td>" +bakar+ "</td> \
                    <td>" +tgl_bakar+ "</td> \
                    <td>" +total_donasi+ "</td> \
                    <td>" +cb_bakar+ "</td> \
                </tr>";
                $tbody.append(tr);
            } 

            $tbody.fadeIn(400, function() {
                create_paginations(
                    data.page, 
                    jumlah_biaowen, 
                    display_per_page, "#" +data.nama_paket+ ";" +data.biaowen+ ";" +data.lunas+ ";" +data.bakar
                );
            });
        }
    });
}

function get_total_biaowen(data) {
    var ajax = get_ajax_response("daftar/biaowen/get_biaowen_list", "get", "total_biaowen", data);
    $.when(ajax).done(function(response) {
        jumlah_biaowen = response;
        $("caption").fadeOut(400, function() {
            $(this).html("Jumlah Biaowen: " +convert_number_tocurrency(jumlah_biaowen)).fadeIn();
        });
    });
}

function init_filter() {
    var data = {
        "nama_paket": $("#nama_paket").val(),
        "biaowen": $("#biaowen").val(),
        "lunas": $("#lunas").val(),
        "bakar": $("#bakar").val()
    };
    get_total_biaowen(data);

    data.page = 0;
    data.display_per_page = display_per_page;
    get_daftar_biaowen(data);
}

function init_table_style() {
    var data = {
        "padding_left": "110"
    };
    get_responsive_style("table", "table.tb_daftar", data);
}