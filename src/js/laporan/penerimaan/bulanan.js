$(document).ready(function() {
    $(".search_icon").on("click", function() {
        print_laporan();
    });

    function print_laporan() {
        var date = new Date($("#bln2").val().split("-")[0], $("#bln2").val().split("-")[1], 0);
        
        var tgl1 = $("#bln1").val()+ "-01";
        var tgl2 = $("#bln2").val()+ "-" +("0"+date.getDate()).substr(-2);
        var bank = $("#bank").val();
        var url = base_url+ "laporan/penerimaan/bulanan/print_laporan?tgl1=" +tgl1+ "&tgl2=" +tgl2+ "&idb=" +bank;
        window.open(url, "_blank");
    }
});