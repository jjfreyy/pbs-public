$(document).ready(function() {
    prepare_paket_list();
    prepare_bank_list("ajax_repository", "id_bank, an, no_rek");

    $("#kode_paket").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_paket").val("");
        $.each($paket_arr, function(i, val) {
            var kode_paket = val.kode_paket;
            var nama_paket = val.nama_paket;
            if (kode_paket.toLowerCase() === filter || nama_paket.toLowerCase() === filter) {
                var id_paket = val.id_paket;
                var nama_perusahaan = val.nama_perusahaan;
                var nilai_paket = val.nilai_paket === null ? "" : convert_number_tocurrency(val.nilai_paket);
                var periode = val.periode;
                if (periode === null) periode = ["", $("#periode1").val()];
                else {
                    periode = periode.split(" ");
                }
                
                $("#id_paket").val(id_paket);
                $("#nama_perusahaan").val(nama_perusahaan);
                $("#kode_paket").val(kode_paket);
                $("#nama_paket").val(nama_paket);
                $("#nilai_paket").val(nilai_paket);
                $("#periode").val(periode[0]);
                $("#periode1").val(periode[1]);

                var bank_list = val.bank_list;
                if (bank_list !== "") {
                    $("tbody").empty();
                    bank_list = bank_list.split("#");
                    var length = bank_list.length;
                    for (var i = 0; i < length; i++) {
                        var bank = bank_list[i].split("|");
                        var tr_input = "<tr>";
                        tr_input += "<td>" +(i+1)+ "</td>";
                        tr_input += "<td><input type='text' class='hidden' name='bank_list[]' value='" +bank_list[i]+ "'/>";
                        tr_input += "<div class='name_box2'><input type='text' readonly value='"+bank[1]+" / "+bank[2]+"'/></div></td>";
                        tr_input += "<td class='centered'><a href='#' class='hapus_bank'>Hapus</a></td>";
                        tr_input += "</tr>";
                        $("tbody").append(tr_input);
                    }
                }
                
                return false;
            }
        });
    });

    $("#nilai_paket").on("keyup change", function() {
        var nilai_paket = convert_currency_tonumber($(this).val());
        $(this).val(convert_number_tocurrency(nilai_paket));
    });

    $("#periode").on("keyup change", function() {
        var periode = $(this).val();
        if (periode !== "" && !isNaN(periode)) $(this).val(Math.floor(periode));
    });

    $("#tambah_bank").on("click", function() {
        tambah_bank();
    });

    $("#nama_bank, #tambah_bank").on("keypress", function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            tambah_bank();
        }
    });

    $("table").on("click", "a.hapus_bank", function() {
        hapus_bank($(this));
    });

    $("table").on("keypress", "a.hapus_biaowen", function(e) {
        if (e.keyCode === 13) hapus_bank($(this));
    });
    
    $("#reset_btn").on("click", function() {
        var id_paket1 = $("#id_paket").prop("defaultValue");
        $("tbody").empty();
        if (id_paket1 !== "") {
            $.each($paket_arr, function(i, val) {
                var id_paket = val.id_paket;
                if (id_paket == id_paket1) {
                    var bank_list = val.bank_list;
                    if (bank_list !== "") {
                        $("tbody").empty();
                        bank_list = bank_list.split("#");
                        var length = bank_list.length;
                        for (var i = 0; i < length; i++) {
                            var bank = bank_list[i].split("|");
                            var tr_input = "<tr>";
                            tr_input += "<td>" +(i+1)+ "</td>";
                            tr_input += "<td><input type='text' class='hidden' name='bank_list[]' value='" +bank_list[i]+ "'/>";
                            tr_input += "<div class='name_box2'><input type='text' readonly value='"+bank[1]+" / "+bank[2]+"'/></div></td>";
                            tr_input += "<td class='centered'><a href='#' class='hapus_bank'>Hapus</a></td>";
                            tr_input += "</tr>";
                            $("tbody").append(tr_input);
                        }
                    }
                }
            });
        }
    });
});

function tambah_bank() {
    var bank = $("#nama_bank").val().split("/");
    if (bank.length === 2) {
        var an1 = bank[0].trim().toLowerCase();
        var no_rek1 = bank[1].trim().toLowerCase();
        $.each($bank_arr, function(i, val) {
            var an = val.an;
            var no_rek = val.no_rek;
            if (an.toLowerCase() === an1 && no_rek.toLowerCase() === no_rek1) {
                var id_bank = val.id_bank;

                var tr_input = "<tr>";
                tr_input += "<td>" +($("tbody").children().length+1)+ "</td>";
                tr_input += "<td><input type='text' class='hidden' name='bank_list[]' value='" +(id_bank+"|"+an+"|"+no_rek)+ "'/>";
                tr_input += "<div class='name_box2'><input type='text' readonly value='"+an+" / "+no_rek+"'/></div></td>";
                tr_input += "<td class='centered'><a href='#' class='hapus_bank'>Hapus</a></td>";
                tr_input += "</tr>";
                $("tbody").append(tr_input);
                $("#nama_bank").val("").focus();

                return false;
            }
        });
    }
}

function hapus_bank(element) {
    var index = element.parent().parent().index();
    var tr_arr = $("tbody").children();
    var length = tr_arr.length;

    for (var i = index + 1; i < length; i++) {
        tr_arr.eq(i).children().first().first().html(i);
    }

    tr_arr.eq(index).remove();
}