$(document).ready(function () {
    $('.event-column').DataTable({
        "pageLength": 20
    });

    $(document).on("click", "#DataTables_Table_0 tbody .event-row", function () {
        var href = location.protocol + "//"+document.domain + $(this).attr("href");
        window.location.href = href;
    })
    $(document).on("click", ".list-icon a", function (event) {
        event.stopPropagation()
    })

    setTimeout(function () {
        $('.table-data').show();
    },500)

});

