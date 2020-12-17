function s54(event) {
    preventDefault(event);
}


function s54s_booking(event) {
    preventDefault(event);
    var tr = $("#t54o").find('tr');

    tr.each(function (i) {
        var td = $(this).find('td');
        var tdq = $(this).find('input');

        var o = td.eq(0).html();
        var q = tdq.val();
        var sj = null;
        var tb = 's';
        var bt = $("#i54bt").val();
        var dy = $("#i54dy").val();
        var p = null;

        ajax_post("m/p_basket.php?a=5401", { dy: dy, q: q, o: o, bt: bt, tb: tb, p: p, sj: sj }, function (data) { });
    }
    );
    window.close();
}
