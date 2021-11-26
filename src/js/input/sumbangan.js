var default_biaowen_list = new Array();
var bank_arr = new Array();

$(document).ready(function() {
    $.when(prepare_donatur_list("input/sumbangan")).done(function() {
        console.log("done");
        reinit_metode_pembayaran();
        var filter = $("#kode_donatur").val().toLowerCase();
        var index = $("#index").val();
        var id_paket_sumbangan = $("#id_paket_sumbangan").val();
        if (filter !== "" && index === "") {
            $.each($donatur_arr, function(i, donatur) {
                var kode_donatur = donatur.kode_donatur;
                if (kode_donatur.toLowerCase() === filter) {
                    $("#index").prop("defaultValue", i);
                    var bank_list = donatur.detail_paket.split("#").find(paket => paket.split("|")[0] == id_paket_sumbangan).split("|")[3];
                    if (bank_list !== "-") {
                        bank_list = bank_list.split(";");
                        var length = bank_list.length;
                        for(var i = 0; i < length; i++) {
                            var id_bank = bank_list[i].split("~")[0];
                            var an = bank_list[i].split("~")[1];
                            var no_rek = bank_list[i].split("~")[2];
                            bank_arr[i] = { "id_bank": id_bank, "an": an, "no_rek": no_rek };
                        }
                    }
                }
            });
        }
    });

    $("input[name='biaowen[]']").each(function(i) {
        default_biaowen_list[i] = this.value;
    });

    $("#kode_donatur").on("change", function() {
        var filter = $("#kode_donatur").val().toLowerCase();
        $("#index").val("");
        $("#kode_donatur").val("");
        $("#nama_donatur").val("");
        $("#id_paket_sumbangan").val("");
        $("#nama_paket").val("");
        $("#paket_list").empty();
        $("#sisa_nilai_paket").val("");
        $("#sisa_nilai_paket1").val("");
        $("#metode_pembayaran").val("Tunai");
        reinit_metode_pembayaran();
        $("#id_bank, #bank").val("");
        bank_arr = [];
        $("tbody").empty();
        
        if (filter !== "") {
            $.each($donatur_arr, function(i, donatur) {
                var kode_donatur = donatur.kode_donatur;
                var nama_donatur = donatur.nama_id;
                if (kode_donatur.toLowerCase() === filter || nama_donatur.toLowerCase() === filter) {
                    $("#index").val(i);
                    $("#kode_donatur").val(kode_donatur);
                    $("#nama_donatur").val(nama_donatur);
                    $("#nama_penyumbang").val(nama_donatur);
    
                    return false;
                }
            });
        }
    });

    $("#nama_paket").on("focus keyup", function() {
        var index = $("#index").val();
        if (!is_nan(index)) {
            var filter = $(this).val().toLowerCase();
            var counter = 0;
            var paket_list = $("#paket_list").empty();
            var paket_arr = $donatur_arr[index].detail_paket.split("#");
            var length = paket_arr.length;
            for(var i = 0; i < length; i++) {
                var nama_paket = paket_arr[i].split("|")[0] + "#" + paket_arr[i].split("|")[1];
                if (nama_paket.toLowerCase().includes(filter)) {
                    paket_list.append("<option value='" +nama_paket+ "'/>");
                    if (++counter === 10) break;
                }
            }
        }
    });
    
    $("#nama_paket").on("change", function() {
        var index = $("#index").val();
        $("#id_paket_sumbangan").val("");
        $("#sisa_nilai_paket").val("");
        $("#sisa_nilai_paket1").val("");
        $("#id_bank, #bank").val("");
        bank_arr = [];
        var tbody = $("table.tb_input tbody").empty();
        if (!is_nan(index)) {
            var nama_paket = $(this).val().split("#");
            if(nama_paket.length !== 2) return;
            var detail_paket = $donatur_arr[index].detail_paket.split("#").find(paket => paket.split("|")[0] === nama_paket[0].trim() && 
            paket.split("|")[1] === nama_paket[1].trim());
            if (detail_paket !== undefined) {
                var jumlah_donasi = convert_currency_tonumber($("#jumlah_donasi").val());
                var id_paket_sumbangan = detail_paket.split("|")[0];
                var sisa_nilai_paket = detail_paket.split("|")[2];
                var bank_list = detail_paket.split("|")[3];
                var biaowen_list = detail_paket.split("|")[4];
                
                if ($("#id_sumbangan").prop("defaultValue") !== "" && $("#id_paket_sumbangan").prop("defaultValue") == id_paket_sumbangan) {
                    sisa_nilai_paket = parseFloat($("#sisa_nilai_paket1").prop("defaultValue"));
                }

                $("#id_paket_sumbangan").val(id_paket_sumbangan);
                $("#sisa_nilai_paket1").val(sisa_nilai_paket);
                if (sisa_nilai_paket === "-") $("#sisa_nilai_paket").val("∞");
                else {
                    if (!is_nan(jumlah_donasi)) sisa_nilai_paket -= jumlah_donasi;
                    $("#sisa_nilai_paket").val(convert_number_tocurrency(sisa_nilai_paket));
                } 
                
                if (bank_list !== "-") {
                    bank_list = bank_list.split(";");
                    var length = bank_list.length;
                    for(var i = 0; i < length; i++) {
                        var id_bank = bank_list[i].split("~")[0];
                        var an = bank_list[i].split("~")[1];
                        var no_rek = bank_list[i].split("~")[2];
                        bank_arr[i] = { "id_bank": id_bank, "an": an, "no_rek": no_rek };
                    }
                }
               
                if (biaowen_list !== undefined) {
                    biaowen_list = biaowen_list.split(";");
                    biaowen_list.forEach((biaowen, i) => {
                        var tr_input = "<tr>";
                        tr_input += "<td>" +(i+1)+ "</td>";
                        tr_input += "<td class='centered'><input type='text' class='hidden' name='biaowen[]' value='0|"+biaowen+"'/>";
                        tr_input += "<input type='checkbox'/></td>";
                        tr_input += "<td><div class='name_box2'><input type='text' readonly value='" +biaowen+ "' /></div></td>";
                        tr_input += "<td class='centered'><a href='#' class='hapus_biaowen'>Hapus</a></td>";
                        tr_input += "</tr>";
                        tbody.append(tr_input);
                    });
                }
            }
        }
    })

    $("#jumlah_donasi").on("keyup changed", function() {
        var jumlah_donasi = convert_currency_tonumber($(this).val());
        var sisa_nilai_paket1 = $("#sisa_nilai_paket1").val();
        if (!is_nan(sisa_nilai_paket1)) {
            if (is_nan(jumlah_donasi)) {
                $("#sisa_nilai_paket").val(convert_number_tocurrency(sisa_nilai_paket1));
            } else {
                var sisa = sisa_nilai_paket1 - jumlah_donasi;
                $("#sisa_nilai_paket").val(convert_number_tocurrency(sisa));
            }
        }
        $(this).val(convert_number_tocurrency(jumlah_donasi));
    });

    $("#metode_pembayaran").on("change", function() {
        reinit_metode_pembayaran();
    });

    $("#bank").on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var bank_list = $("#bank_list").empty();
        var length = bank_arr.length;
        for(var i = 0; i < length; i++) {
            var an = bank_arr[i].an;
            var no_rek = bank_arr[i].no_rek;
            if (an.toLowerCase().includes(filter) || no_rek.toLowerCase().includes(filter)) {
                bank_list.append("<option value='" +an+ " / " +no_rek+ "'/>");
                if (++counter === 10) return false;
            }
        }
    });

    $("#bank").on("change", function() {
        var bank = $(this).val().split("/");
        $("#id_bank").val("");
        if (bank.length === 2) {
            var an1 = bank[0].trim().toLowerCase();
            var no_rek1 = bank[1].trim().toLowerCase();
            var length = bank_arr.length;
            for(var i = 0; i < length; i++) {
                var an = bank_arr[i].an;
                var no_rek = bank_arr[i].no_rek;
                if (an.toLowerCase() === an1 && no_rek.toLowerCase() === no_rek1) {
                    var id_bank = bank_arr[i].id_bank;
                    $("#id_bank").val(id_bank);
                    return false;
                }
            }
        }
    });

    $("body").on("click", "input[type='checkbox']", function() {
        var biaowen = $(this).prev().val().split("|")[1];
        if ($(this).prop("checked")) $(this).prev().val("1|"+biaowen);
        else $(this).prev().val("0|"+biaowen);
    });
    
    $("#tambah_biaowen").on("click", function() {
        tambah_biaowen();
    });
    
    $("#nama_biaowen, #tambah_biaowen").on("keypress", function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            tambah_biaowen();
        }
    });
    
    $("table").on("click", "a.hapus_biaowen", function() {
        hapus_biaowen($(this));
    });

    $("table").on("keypress", "a.hapus_biaowen", function(e) {
        if (e.keyCode === 13) hapus_biaowen($(this));
    });

    $("#reset_btn").on("click", function(e) {
        $("tbody").empty();
        var length = default_biaowen_list.length;
        for (var i = 0; i < length; i++) {
            var biaowen_detail = default_biaowen_list[i].split("|");
            var checked = biaowen_detail[0] == 0 ? "" : "checked";
            var tr_input = "<tr>";
            tr_input += "<td>" +(i+1)+ "</td>";
            tr_input += "<td class='centered'><input type='text' class='hidden' name='biaowen[]' value='" +default_biaowen_list[i]+ "'/>";
            tr_input += "<input type='checkbox' " +checked+ "/></td>";
            tr_input += "<td><div class='name_box2'><input type='text' readonly value='" +biaowen_detail[1]+ "'></div></td>";
            tr_input += "<td class='centered'><a href='#' class='hapus_biaowen'>Hapus</a></td>";
            tr_input += "</tr>";
            $("tbody").append(tr_input);
        }
    });
});

function reinit_metode_pembayaran() {
    var metode_pembayaran = $("#metode_pembayaran").val();
    if (metode_pembayaran === "Transfer") {
        $(".accordion .nav_icon1").css("background", "url(" +src_base_url+ "img/arrow-up2.png) center / contain no-repeat");
        $(".search_box2").slideDown();
        $("#id_bank, #bank, #rek_pengirim").prop("disabled", false);
    } else {
        $(".accordion .nav_icon1").css("background", "url(" +src_base_url+ "img/arrow-down2.png) center / contain no-repeat");
        $(".search_box2").slideUp();
        $("#id_bank, #bank, #rek_pengirim").prop("disabled", true);
    }
}

function tambah_biaowen() {
    var biaowen = $("#nama_biaowen").val().trim();
    if (biaowen !== "") {
        var tr_input = "<tr>";
        tr_input += "<td>" +($("tbody").children().length+1)+ "</td>";
        tr_input += "<td class='centered'><input type='text' class='hidden' name='biaowen[]' value='0|" +biaowen+ "'/>";
        tr_input += "<input type='checkbox'/></td>";
        tr_input += "<td><div class='name_box2'><input type='text' readonly value='" +biaowen+ "'></div></td>";
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