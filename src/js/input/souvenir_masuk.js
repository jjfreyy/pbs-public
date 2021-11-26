$(document).ready(function() {
    prepare_souvenir_list("ajax_repository", "id_souvenir, kode_souvenir, nama");

    $("#kode_souvenir").on("change", function() {
        var filter = $(this).val().toLowerCase();
        var is_found = false;
        $.each($souvenir_arr, function(i, val) {
            var kode_souvenir = val.kode_souvenir;
            var nama = val.nama;
            if (kode_souvenir.toLowerCase() === filter || nama.toLowerCase() === filter) {
                is_found = true;
                var id_souvenir = val.id_souvenir;

                $("#id_souvenir").val(id_souvenir);
                $("#kode_souvenir").val(kode_souvenir);
                $("#nama_souvenir").val(nama);

                return false;
            }
        });

        if (!is_found) {
            $("#id_souvenir").val("");
            $("#kode_souvenir").val("");
            $("#nama_souvenir").val("");
        }
    });
});