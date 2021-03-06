$(document).ready(function() {
    prepare_bank_list();

    $("#nama_bank").on("change", function() {
        var filter = $(this).val().split("/");
        $("#id_bank").val("");
        if (filter.length === 2) {
            var an1 = filter[0].trim().toLowerCase();
            var no_rek1 = filter[1].trim().toLowerCase();
            $.each($bank_arr, function(i, val) {
                var an = val.an;
                var no_rek = val.no_rek;
                if (an.toLowerCase() === an1 && no_rek.toLowerCase() === no_rek1) {
                    var id_bank = val.id_bank;
                    var nama_bank = val.nama_bank;

                    $("#id_bank").val(id_bank);
                    $("#nama_bank").val(nama_bank);
                    $("#an").val(an);
                    $("#no_rek").val(no_rek);

                    return false;
                }
            });
        }
    });
});