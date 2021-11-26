$(document).ready(function() {
    prepare_kolektor_list();
    $("#kode_kolektor").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_kolektor").val("");
        $.each($kolektor_arr, function(i, kolektor) {
            var kode_kolektor = kolektor.kode_kolektor;
            var nama = kolektor.nama;
            if (kode_kolektor.toLowerCase() === filter || nama.toLowerCase() === filter) {
                var id_kolektor = kolektor.id_kolektor;
                var no_hp1 = kolektor.no_hp1;
                var no_hp2 = kolektor.no_hp2;
                var email = kolektor.email;
                var ket = kolektor.ket;

                $("#id_kolektor").val(id_kolektor);
                $("#kode_kolektor").val(kode_kolektor.split("-")[1]);
                $("#nama_kolektor").val(nama);
                $("#no_hp1_kolektor").val(no_hp1);
                $("#no_hp2_kolektor").val(no_hp2);
                $("#email_kolektor").val(email);
                $("#ket_kolektor").val(ket);

                return false;
            }
        });
    });
});