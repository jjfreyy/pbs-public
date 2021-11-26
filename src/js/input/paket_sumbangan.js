var biaowen_list = new Array();

$(document).ready(function() {
    prepare_donatur_list("ajax_repository", "id_donatur, kode_donatur, nama_id");
    prepare_kolektor_list("id_kolektor, kode_kolektor, nama");
    prepare_paket_list("ajax_repository", "tp.id_paket, tp.kode_paket, tp.nama_paket, tp.nilai_paket, tp.periode");

    $("input[name='biaowen[]']").each(function(i) {
        biaowen_list[i] = this.value;
    });

    $("#kode_donatur").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_donatur").val("");
        $("#kode_donatur").val("");
        $("#nama_donatur").val("");
        $.each($donatur_arr, function(i, donatur) {
            var kode_donatur = donatur.kode_donatur;
            var nama_donatur = donatur.nama_id;
            if (kode_donatur.toLowerCase() === filter || nama_donatur.toLowerCase() === filter) {
                var id_donatur = donatur.id_donatur;

                $("#id_donatur").val(id_donatur);
                $("#kode_donatur").val(kode_donatur);
                $("#nama_donatur").val(nama_donatur);
                
                return false;
            }
        });
    });
    
    $("#kode_kolektor").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_kolektor").val("");
        $("#kode_kolektor").val("");
        $("#nama_kolektor").val("");
        $.each($kolektor_arr, function(i, kolektor) {
            var kode_kolektor = kolektor.kode_kolektor;
            var nama = kolektor.nama;
            if (kode_kolektor.toLowerCase() === filter || nama.toLowerCase() === filter) {
                var id_kolektor = kolektor.id_kolektor;

                $("#id_kolektor").val(id_kolektor);
                $("#kode_kolektor").val(kode_kolektor);
                $("#nama_kolektor").val(nama);

                return false;
            }
        });
    });
    
    $("#kode_paket").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_paket").val("");
        $("#kode_paket").val("");
        $("#nama_paket").val("");
        $("#nilai_paket").val("");
        $("#tgl_jatuh_tempo").val("");
        $.each($paket_arr, function(i, paket) {
            var kode_paket = paket.kode_paket;
            var nama_paket = paket.nama_paket;
            if (kode_paket.toLowerCase() === filter || nama_paket.toLowerCase() === filter) {
                var id_paket = paket.id_paket;
                var nilai_paket = paket.nilai_paket === null ? "∞" : convert_number_tocurrency(paket.nilai_paket);
                var periode = paket.periode === null ? null : paket.periode.split(" ");
                if (periode !== null) {
                    var date = new Date();
                    switch (periode[1]) {
                        case "H":
                            date.setDate(date.getDate() + parseInt(periode[0]));
                            break;
                        case "B":
                            date.setMonth(date.getMonth() + parseInt(periode[0]));
                            break;
                        case "T":
                            date.setFullYear(date.getFullYear() + parseInt(periode[0]));
                            break;
                    }
                    periode = date.getFullYear()+ "-" +("0" +date.getMonth()).substr(-2)+ "-" +("0" +date.getDate()).substr(-2);
                }

                $("#id_paket").val(id_paket);
                $("#kode_paket").val(kode_paket);
                $("#nama_paket").val(nama_paket);
                $("#nilai_paket").val(nilai_paket);
                $("#tgl_jatuh_tempo").val(periode);

                return false;
            }
        });
    });

    $("#nama_biaowen, #tambah_biaowen").on("keypress", function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            tambah_biaowen();
        }
    });

    $("#tambah_biaowen").on("click", function() {
        tambah_biaowen();
    });

    $("table").on("click", "a.hapus_biaowen", function() {
        hapus_biaowen($(this));
    });

    $("table").on("keypress", "a.hapus_biaowen", function(e) {
        if (e.keyCode === 13) hapus_biaowen($(this));
    });

    $("#reset_btn").on("click", function() {
        $("tbody").empty();
        var length = biaowen_list.length;
        for (var i = 0; i < length; i++) {
            var tr_input = "<tr>";
            tr_input += "<td>" +(i+1)+ "</td>";
            tr_input += "<td><div class='name_box2'><input type='text' name='biaowen[]' value='" +biaowen_list[i]+ "'></div></td>";
            tr_input += "<td class='centered'><a href='#' class='hapus_biaowen'>Hapus</a></td>";
            tr_input += "</tr>";
            $("tbody").append(tr_input);
        }
    });
});

function tambah_biaowen() {
    var biaowen = $("#nama_biaowen").val().trim();
    if (biaowen !== "") {
        var tr_input = "<tr>";
        tr_input += "<td>" +($("tbody").children().length+1)+ "</td>";
        tr_input += "<td><div class='name_box2'><input type='text' name='biaowen[]' value='" +biaowen+ "'></div></td>";
        tr_input += "<td class='centered'><a href='#' class='hapus_biaowen'>Hapus</a></td>";
        tr_input += "</tr>";
        $("tbody").append(tr_input);
        $("#nama_biaowen").val("").focus();
    }
}

function hapus_biaowen(element) {
    var index = element.parent().parent().index();
    var tr_arr = $("tbody").children();
    var length = tr_arr.length;

    for (var i = index + 1; i < length; i++) {
        tr_arr.eq(i).children().first().first().html(i);
    }

    tr_arr.eq(index).remove();
}