$(document).ready(function() {
    prepare_date_filter();

    $("#tgl1, #tgl2, #filter").on("keypress", function(e) {
        if (e.keyCode === 13) print_laporan();
    });

    $(".search_icon").on("click", function() {
        print_laporan();
    });

    function print_laporan() {
        var date = get_date_filter();
        var metode_pembayaran = $("#metode_pembayaran").val();
        var filter = $("#filter").val();
        var url = base_url+ "laporan/paket/rekapan/print_laporan?tgl1=" +date.tgl1+ "&tgl2=" +date.tgl2+ "&f=" +filter;
        window.open(url, "_blank");
    }
});