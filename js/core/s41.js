var m41ii = [];
var m41i0 = 0;

function s41(event) {
    preventDefault(event);
}

function s41view(x) {
    $("#m41img").html('<img src="' + x + '" />');
}

function s41view2(x) {
    m41i0 = toInt(x);
    if (m41ii.length <= m41i0) { m41i0 = 1; }
    if (m41ii[m41i0] != '')
    {
        $("#m41img").html('<img src="' + m41ii[m41i0] + '"  />');
    }
}

function s41timer() {
    if (m41ii.length > 0) {
        var z = $("#chk41sh").prop("checked");
        if (z == undefined || z == "undefined") { z = false; }
        if (z) { s41view2(m41i0 + 1); }
    }
}
