function prepare_bank_list(url = "ajax_repository", field_list = "", element_id = "nama_bank", datalist="bank_list") {
    $.ajax({
        url: base_url+url+ "/get_bank_list",
        method: "get",
        data: {get_bank_list: true, field_list: field_list},
        dataType: "json",
        success: function(response) {
            $bank_arr = response;
        }
    });

    $("#" +element_id).on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var bank_list = $("#"+datalist).empty();
        $.each($bank_arr, function(i, val) {
            var an = val.an;
            var no_rek = val.no_rek;
            if (an.toLowerCase().includes(filter) || no_rek.toLowerCase().includes(filter)) {
                bank_list.append("<option value='" +an+ " / " +no_rek+ "'/>");
                if (++counter === 10) return false;
            }
        });
    });
}

function prepare_donatur_list(url = "ajax_repository", field_list = "") {
    var s = $.ajax({
        url: base_url+url+ "/get_donatur_list",
        method: "get",
        data: {get_donatur_list: true, field_list: field_list},
        dataType: "json",
        // async: false,
        success: function(response) {
            $donatur_arr = response;
        }
    });

    $("#kode_donatur").on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var donatur_list = $("#donatur_list");
        donatur_list.empty();
        $.each($donatur_arr, function(i, val) {
            var kode_donatur = val.kode_donatur;
            var nama_id = val.nama_id;
            if (nama_id.toLowerCase().includes(filter)) {
                donatur_list.append("<option value='" +nama_id+ "'></option>");
                if (++counter === 10) return false;
            } else if (kode_donatur.toLowerCase().includes(filter)) {
                donatur_list.append("<option value='" +kode_donatur+ "'></option>");
                if (++counter === 10) return false;
            }
        });
    });

    return s;
}

function prepare_kolektor_list(field_list = "") {
    $.ajax({
        url: base_url+ "ajax_repository/get_kolektor_list",
        method: "get",
        data: {get_kolektor_list: true, field_list: field_list},
        dataType: "json",
        success: function(response) {
            $kolektor_arr = response;
        }
    });

    $("#kode_kolektor").on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var kolektor_list = $("#kolektor_list");
        kolektor_list.empty();
        $.each($kolektor_arr, function(i, val) {
            var kode_kolektor = val.kode_kolektor;
            var nama = val.nama;
            if (nama.toLowerCase().includes(filter)) {
                kolektor_list.append("<option value='" +nama+ "'></option>");
                if (++counter === 10) return false;
            } else if (kode_kolektor.toLowerCase().includes(filter)) {
                kolektor_list.append("<option value='" +kode_kolektor+ "'></option>");
                if (++counter === 10) return false;
            }
        });
    });
}

function prepare_kota_list() {
    $.ajax({
        url: base_url+ "ajax_repository/get_kota_list",
        method: "get",
        data: {get_kota_list: true},
        dataType: "json",
        success: function(response) {
            $kota_arr = response;
        }
    });

    $.each(arguments, function(i, val) {
        $(val[0]).on("focus keydown", function() {
            var filter = $(this).val().toLowerCase();
            var counter = 0;
            var datalist = $(val[1]);
            datalist.empty();
            $.each($kota_arr, function(i, kota) {
                var nama = kota.nama;
                if (nama.toLowerCase().includes(filter)) {
                    datalist.append("<option value='" +nama+ "'></option>");
                    if (++counter === 10) return false;
                }
            });
        });
    });
}

function prepare_paket_list(url = "ajax_repository", field_list = "") {
    $.ajax({
        url: base_url+url+ "/get_paket_list",
        method: "get",
        data: {get_paket_list: true, field_list: field_list},
        dataType: "json",
        success: function(response) {
            $paket_arr = response;
        }
    });

    $("#kode_paket").on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var paket_list = $("#paket_list").empty();
        $.each($paket_arr, function(i, val) {
            var kode_paket = val.kode_paket;
            var nama_paket = val.nama_paket;
            if (nama_paket.toLowerCase().includes(filter)) {
                paket_list.append("<option value='" +nama_paket+ "'/>");
                if (++counter === 10) return false;
            } else if (kode_paket.toLowerCase().includes(filter)) {
                paket_list.append("<option value='" +kode_paket+ "'/>");
                if (++counter === 10) return false;
            }
        });
    });
}

function prepare_souvenir_list(url = "ajax_repository", field_list = "", id = "") {
    $.ajax({
        url: base_url+url+ "/get_souvenir_list",
        method: "get",
        data: {get_souvenir_list: true, field_list: field_list, id: id},
        dataType: "json",
        success: function(response) {
            $souvenir_arr = response;
        }
    });

    $("#kode_souvenir").on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var souvenir_list = $("#souvenir_list").empty();
        $.each($souvenir_arr, function(i, val) {
            var kode_souvenir = val.kode_souvenir;
            var nama = val.nama;
            if (nama.toLowerCase().includes(filter)) {
                souvenir_list.append("<option value='" +nama+ "'/>");
                if (++counter === 10) return false;
            } else if (kode_souvenir.toLowerCase().includes(filter)) {
                souvenir_list.append("<option value='" +kode_souvenir+ "'/>");
                if (++counter === 10) return false;
            }
        });
    });
}

function process_ajax(ajax, function_call) {
    $.when(ajax).done(function(response) {
        $("div.simple_dialog").remove();
        $("body").append(create_dialog("simple", [response.status, response.message]));
        $("div.simple_dialog").slideDown();
        if (response.status == "success") {
            window[function_call]();
        }
    });
}
