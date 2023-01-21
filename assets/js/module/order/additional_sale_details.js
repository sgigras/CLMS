
alert("hello");
function addNewRow() {
    var template = $("tr.trow:first");
    $(".no_entries_row").css("display", "none");
    var newRow = template.clone();
    console.log(newRow);
    var lastRow = $("tr.trow:last").after(newRow);

    $(".list_cancel").on("click", function (event) {
        event.stopPropagation();
        event.stopImmediatePropagation();
        $(this).closest("tr").remove();
        if ($(".list_cancel").length === 1) {
            $(".no_entries_row")
                .css("display", "inline-block");
        }
        console.log($(".list_cancel").length
        );
    });

    console.log($(".list_cancel").length);
    $("select.label").on("change", function (event) {
        event.stopPropagation();
        event.stopImmediatePropagation();
        $(this).css("background-color", $(this).val());
    });
}

$("a.list_add").on("click", addNewRow);