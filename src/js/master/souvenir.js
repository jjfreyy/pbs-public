$(document).ready(function() {
    prepare_souvenir_list("ajax_repository", "id_souvenir, kode_souvenir, nama, stok_awal, jenis, satuan, ket");
    prepare_jenis_souvenir_list();
    prepare_satuan_list();

    $("#kode_souvenir").on("change", function() {
        var filter = $(this).val().toLowerCase();
        $("#id_souvenir").val("");
        $.each($souvenir_arr, function(i, val) {
            var kode_souvenir = val.kode_souvenir;
            var nama = val.nama;
            if (kode_souvenir.toLowerCase() === filter || nama.toLowerCase() === filter) {
                var id_souvenir = val.id_souvenir;
                var stok_awal = convert_number_tocurrency(val.stok_awal);
                var jenis = val.jenis;
                var satuan = val.satuan;
                var ket = val.ket;

                $("#id_souvenir").val(id_souvenir);
                $("#kode_souvenir").val(kode_souvenir);
                $("#nama_souvenir").val(nama);
                $("#stok_awal_souvenir").val(stok_awal);
                $("#jenis_souvenir").val(jenis);
                $("#satuan_souvenir").val(satuan);
                $("#ket_souvenir").val(ket);

                return false;
            }
        });
    });

    $("#stok_awal_souvenir").on("keyup change", function() {
        var stok_awal_souvenir = convert_currency_tonumber($(this).val());
        $(this).val(convert_number_tocurrency(stok_awal_souvenir));
    });    

    function prepare_jenis_souvenir_list() {
        $.ajax({
            url: base_url+ "ajax_repository/get_jenis_souvenir_list",
            method: "get",
            data: {get_jenis_souvenir_list: true},
            dataType: "json",
            success: function(response) {
                $jenis_souvenir_arr = response;
            }
        });
    
        $("#jenis_souvenir").on("focus keyup", function() {
            var filter = $(this).val().toLowerCase();
            var counter = 0;
            var jenis_souvenir_list = $("#jenis_souvenir_list").empty();
            $.each($jenis_souvenir_arr, function(i, val) {
                var nama = val.nama;
                if (nama.toLowerCase().includes(filter)) {
                    jenis_souvenir_list.append("<option value='" +nama+ "'></option>");
                    if (++counter === 10) return false;
                }
            });
        });
    }
    
    function prepare_satuan_list() {
        $.ajax({
            url: base_url+ "ajax_repository/get_satuan_list",
            method: "get",
            data: {get_satuan_list: true},
            dataType: "json",
            success: function(response) {
                $satuan_arr = response;
            }
        });
    
        $("#satuan_souvenir").on("focus keyup", function() {
            var filter = $(this).val().toLowerCase();
            var counter = 0;
            var satuan_list = $("#satuan_list").empty();
            $.each($satuan_arr, function(i, val) {
                var nama = val.nama;
                if (nama.toLowerCase().includes(filter)) {
                    satuan_list.append("<option value='" +nama+ "'></option>");
                    if (++counter === 10) return false;
                }
            });
        });
    }
});