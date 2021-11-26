$(document).ready(function() {
    prepare_donatur_list();
    $("#kode_donatur").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_donatur").val("");
        $.each($donatur_arr, function(i, donatur) {
            var kode_donatur = donatur.kode_donatur;
            var nama_id = donatur.nama_id;
            if (kode_donatur.toLowerCase() === filter || nama_id.toLowerCase() === filter) {
                var id_donatur = donatur.id_donatur;
                var nama_cn = donatur.nama_cn;
                var alamat = donatur.alamat;
                var kota_lahir = donatur.kota_lahir;
                var tgl_lahir = donatur.tgl_lahir;
                var kota_domisili = donatur.kota_domisili;
                var no_hp1 = donatur.no_hp1;
                var no_hp2 = donatur.no_hp2;
                var email = donatur.email;
                var ket = donatur.ket;
                var tgl_gabung = donatur.tgl_gabung;

                $("#id_donatur").val(id_donatur);
                $("#kode_donatur").val(kode_donatur.split("-")[1]);
                $("#nama_id_donatur").val(nama_id);
                $("#nama_cn_donatur").val(nama_cn);
                $("#alamat_donatur").val(alamat);
                $("#kota_lahir_donatur").val(kota_lahir);
                $("#tgl_lahir_donatur").val(tgl_lahir);
                $("#kota_domisili_donatur").val(kota_domisili);
                $("#no_hp1_donatur").val(no_hp1);
                $("#no_hp2_donatur").val(no_hp2);
                $("#email_donatur").val(email);
                $("#ket_donatur").val(ket);
                $("#tgl_gabung_donatur").val(tgl_gabung);
                
                return false;
            }
        });
    });

    prepare_kota_list(["#kota_lahir_donatur", "#kota_lahir_list"], ["#kota_domisili_donatur", "#kota_domisili_list"]);
});