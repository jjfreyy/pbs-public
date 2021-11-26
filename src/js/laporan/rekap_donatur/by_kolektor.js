$(document).ready(function() {
    prepare_date_filter();

    $("#tgl1, #tgl2, #paket, #donatur, #kolektor").on("keypress", function(e) {
        if (e.keyCode === 13) print_laporan();
    });

    $(".search_icon").on("click", function() {
        print_laporan();
    });

    function print_laporan() {
        const date = get_date_filter();
        const paket = $("#paket").val()
        const donatur = $("#donatur").val();
        const kolektor = $("#kolektor").val();
        const lunas = $("#lunas").val();
        const url = `${base_url}laporan/rekap_donatur/by_kolektor/print_laporan?tgl1=${date.tgl1}&tgl2=${date.tgl2}&p=${paket}&d=${donatur}&k=${kolektor}&l=${lunas}`
        window.open(url, "_blank");
    }
});