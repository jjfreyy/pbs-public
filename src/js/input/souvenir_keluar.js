$(document).ready(function() {
    prepare_paket_sumbangan_list();
    prepare_souvenir_list("input/souvenir_keluar", "", $("#id").val());

    $("#kode_souvenir").on("change", function() {
        var filter = $(this).val().toLowerCase();
        var is_found = false;
        $.each($souvenir_arr, function(i, val) {
            var kode_souvenir = val.kode_souvenir;
            var nama = val.nama;
            if (kode_souvenir.toLowerCase() === filter || nama.toLowerCase() === filter) {
                is_found = true;
                var id_souvenir = val.id_souvenir;
                var stok_akhir = val.stok_akhir;

                $("#id_souvenir").val(id_souvenir);
                $("#kode_souvenir").val(kode_souvenir);
                $("#nama_souvenir").val(nama);
                $("#stok_tersedia_souvenir").val(convert_number_tocurrency(stok_akhir));

                return false;
            }
        });

        if (!is_found) {
            $("#id_souvenir").val("");
            $("#kode_souvenir").val("");
            $("#nama_souvenir").val("");
            $("#stok_tersedia_souvenir").val("");
        }
    });
});

function prepare_paket_sumbangan_list() {
    $.ajax({
        url: base_url+ "input/souvenir_keluar/get_paket_sumbangan_list",
        method: "get",
        data: {get_paket_sumbangan_list: true},
        dataType: "json",
        success: function(response) {
            $paket_arr = response;
        }
    });

    $("#nama_paket").on("focus keyup", function() {
        var filter = $(this).val().toLowerCase();
        var counter = 0;
        var paket_list = $("#paket_list").empty();
        $.each($paket_arr, function(i, val) {
            var paket = val.id_paket_sumbangan + "#" +val.nama_paket+ "#" +val.nama_donatur;
            if (paket.toLowerCase().includes(filter)) {
                paket_list.append("<option value='" +paket+ "'/>");
                if (++counter === 10) return false;
            }
        });
    });

    $("#nama_paket").on("change", function() {
        var paket = $(this).val().split("#");
        $("#id_paket_sumbangan").val("");
        $("#nama_paket").val("");
        $("#total_donasi").val("");
        $("#penerima_souvenir").val("");
        if (paket.length === 3) {
            var id_paket_sumbangan1 = paket[0].trim();
            var nama_paket1 = paket[1].trim().toLowerCase();
            var nama_donatur1 = paket[2].trim().toLowerCase();
            $.each($paket_arr, function(i, val) {
                var id_paket_sumbangan = val.id_paket_sumbangan;
                var nama_paket = val.nama_paket;
                var nama_donatur = val.nama_donatur;
                if (id_paket_sumbangan == id_paket_sumbangan1 && nama_paket.toLowerCase() === nama_paket1 && nama_donatur.toLowerCase() === nama_donatur1) {
                    var total_donasi = convert_number_tocurrency(val.total_donasi);
                    $("#id_paket_sumbangan").val(id_paket_sumbangan);
                    $("#nama_paket").val(id_paket_sumbangan+ "#" +nama_paket+ "#" +nama_donatur);
                    $("#total_donasi").val(total_donasi);
                    $("#penerima_souvenir").val(nama_donatur);
                }
            });
        }
    });
}