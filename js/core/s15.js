function btn15_basket_print(xf_delivery, xid_invoice, xfullorder, xsn) {
    $("#b6prn_b").hide();
    var windowAttr = "location=no,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no,width=100";
    var window_z = window.open('m8_basket_print.php?f=' + xf_delivery + '&i=' + xid_invoice + '&fo=' + xfullorder + '&sn=' + xsn, '_blank', windowAttr);
    $("#b6prn_b").show();
}

 

function btn15_bill_click2(xid_invoice, xlg_buyer, xcredit, xsn) {
    $("#b6prn_i").hide();
    if (xlg_buyer == null) xlg_buyer = $("#i6_buyer").val();
    if (xcredit == null) xcredit = $("#i6credit").val();

    ajax_post("m15_bill.php?a=2", { bill: xid_invoice, byr: xlg_buyer, c: xcredit },
        function (data) {
            var rz = json_parse(data);
            s0_menu(2, '');
            var windowAttr = "location=no,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no,width=100";
            var window_bill = window.open('m15_bill.php?a=1&h=1&bill=' + xid_invoice + '&byr=' + xlg_buyer + '&sn=' + xsn, '_blank', windowAttr);
            $("#b6prn_i").show();
        });
}

//window_z.print();
//window_z.onfocus = function () { window_z.close(); }
// <body onload="window.print()" onfocus="window.close()">
