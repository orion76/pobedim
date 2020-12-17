function s6bookingset_show(xe) {
    $("#bookingset").html(s0_msg_waiting());
    ajax_post("m/p7_entry.php?ax=712", { e: xe }
        , function (data) {
            var rz = json_parse(data);
            var zbs = rz['ID_DOC'];
            var zbyr = rz['LG_SUBJECT'];
            var zub = rz['LG_USER_BOOKING'];
            var zcl = rz['BASKET'];
            ajax_post("m6_bookingset.php", { bs: zbs, cl: zcl, ub: zub, byr: zbyr }
                , function (data) {
                    var rz = json_parse(data);
                    $("#bookingset").html(rz['HTML']);
                }
        );
            location.hash = "#eot_bs";
        });
}



function i6byr_submit(event) {
    preventDefault(event);
    var zbuyer = $("#i6_buyer").val();
    var zau = $("#i6au").val();
    var zinv = $("#i6inv").val();
    var zmsg = $("#i6msg").val();
    ajax_post("m/p_basket.php?a=814", { byr: zbuyer, au: zau, i: zinv, t: zmsg }
        , function (data) {
            var rz = json_parse(data);
            msg0_setclass("#i6discount", '')
            $("#i6_buyer").val(rz.LG_SUBJECT);
            $("#i6discount").html(rz.LG_DISCOUNT);
            $("#i6_byr_contact").val(rz.TEXT_CONTACT);
        //    $("#i6_byr_address").val(rz.TEXT_ADDRESS);
            $("#i6_byr_saldo").val(rz.SALDO_SUBJECT);
            if (rz.R.indexOf('OK') >= 0) {
                $("#i6_buyer").toggleClass("ok", true);
                if (rz.R.indexOf('DEFAULT') > 0) { $("#i6discount").toggleClass("wrn", true); } else { $("#i6discount").toggleClass("ok", true); }
            } else {
                $("#i6_buyer").toggleClass("ok", false);
            }
            $("#m6byr_edit").show();
            //$("#i6msg").focus();
        });
}

function btn6bookings_upd() {
    var xid_invoice_new = $("#select6_invoice").val();
    var xlg_client_new = $("#select6_client").val();
    ajax_post("m/p8_booking.php?a=22", { xid_invoice_new: xid_invoice_new, xlg_client_new: xlg_client_new }, function (data) { s0_menu(5, ''); });
}

function m6div6editbill_show(event) {
    $("#div6bill_edit").show();
    $("#a6editbill").hide();
}

function btn6bookings_status(xlg_status_new) {
    ajax_post("m/p8_booking.php?a=23", { xlg_status_new: xlg_status_new }, function (data) { s0_menu(5, ''); });
}

function btn6invoice_newblank(xid_invoice){
    ajax_post("m/p_basket.php?a=67", { i:xid_invoice } , function (data) {  } );
}




function btn6dvry_upd(xid_invoice){
    var zdvry = $("#i6dvry").val();
    ajax_post("m/p_basket.php?a=65", { i: xid_invoice, dy: zdvry }, function (data) { });
}

function chg6qty_booking(xid_booking, xqnty) {
    xqnty = toNum(xqnty);
    ajax_post("m/p_basket.php?a=68", { b: xid_booking, q: xqnty }, function (data) { });
}

function chg6price_booking(xid_booking, xprice) {
    ajax_post("m/p_basket.php?a=69", { b: xid_booking, p: xprice }, function (data) { });
}

function chg6splr_booking(xid_booking, xlg_supplier) {
    ajax_post("m/p_basket.php?a=606", { b: xid_booking, s: xlg_supplier }, function (data) { });
}

function chg6o_booking(xid_booking, xid_offer, x) {
    ajax_post("m/p_basket.php?a=610", { b: xid_booking, o: xid_offer }
        , function (data) {
            var rz = json_parse(data);
            if (rz.ERR != '') { x.value = rz.ERR; }
        });
}
