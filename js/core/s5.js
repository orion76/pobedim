var m5bb = [];


function s5bb_loaded() {
    var t5bb = $("#t5bb");
    for (var i = 0; i < m5bb.length; i++) {
        var zo = m5bb_tr(m5bb[i]);
        t5bb.append(zo);
    }
}





function m5_invoice_del(event) {
    preventDefault(event);
    var o = event_target(event).parentNode.parentNode;
    var zid_invoice = o.getAttribute("data-id");
    ajax_post('m/p8_booking.php?a=3', { i: zid_invoice },
        function (data) {
            var rz = json_parse(data);
            if (rz.SYS_F == -1) { $(o).toggleClass("deleted", true); }
            else { $(o).removeClass("deleted"); }
        });
}

function s5check_mode0(event) {
    preventDefault(event);

    var ax = 552;
    var zm = $("#cb5m").prop('checked');
    if (zm) { ax = 551; } else { ax = 552; }

    var pb = $("#i0bp").val();
    var pe = $("#i0ep").val();
    var zhref = 'm/m5_li.php?ax=' + ax;
    s3menu_exec(zhref);

    /*
    var dv = event_target(event).parentNode;
    var ax = 552;
    var zm = $("#cb5m").prop('checked');
    if (zm) { m = 0; } else { m = 1; }

    var pb = $("#i0bp").val();
    var pe = $("#i0ep").val();
    ajax_post("m5_booking.php", { m: m, pb: pb, pe: pe }
        , function (data) { $("#" + dv.id).html(data); }
    );
    */
}


function m5row_click(event) {
    var src = event_target(event); //
    if (src.nodeName != 'TD') return;
    var o = src.parentNode;
    var zbs = o.getAttribute("data-id");
    if (zbs != null) {
        $("#t5bb tr").toggleClass("tr_selected", false);
        $(o).toggleClass("tr_selected", true);
        var zcl = o.getAttribute("data-client");
        var zub = o.getAttribute("data-user");
        var zbyr = o.getAttribute("data-buyer");

        var rzbs = '';

        for (var i = 0; i < m5bb.length; i++) {
            var zi = m5bb[i]['ID_INVOICE'];
            if (zi == zbs) {
                rzbs = m5bb[i]['BS'];
                break;
            }
        }

        if (rzbs == '') {

            $("#bookingset").html(s0_msg_waiting());
            ajax_post("m6_bookingset.php", { bs: zbs, cl: zcl, ub: zub, byr: zbyr }
                , function (data) {
                    var rz = json_parse(data);
                    $("#bookingset").html(rz['HTML']);

                    for (var i = 0; i < m5bb.length; i++) {
                        var zi = m5bb[i]['ID_INVOICE'];
                        if (zi == rz['ID_INVOICE']) {
                            m5bb[i]['BS'] = rz;
                            break;
                        }
                    }
                    location.hash = "#eot_bookingset";
                });
        }
        else {
            $("#bookingset").html(rzbs['HTML']);
            location.hash = "#eot_bookingset";
        }
        
    }
}