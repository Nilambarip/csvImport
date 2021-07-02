$(document).ready(function() {
    $("#CSVImportForm").on("submit", function () {
        let response = $("#response");
        response.attr("class", "");
        response.html("");
        let fileType = ".csv";
        let regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
            response.addClass("error");
            response.addClass("display-block");
            response.html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});