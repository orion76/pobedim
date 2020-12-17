function m11courier_select(xselect)
{
    var f = $("#form_courier");
    if (xselect != '' ) { f.show(); } else { f.hide(); $("#select_courier").val(-1); }
}

function m11courier_submit(event)
{
    preventDefault(event);
    var zlg_courier = $("#select_courier").val();
    ajax_post("p11_booking_delivery.php?a=1", { xlg_courier: zlg_courier },
               function (data) {  }
          );

    alert('ok');
}


function chk_click11(xid_booking, xchecked) {
    ajax_post("p11_booking_delivery.php?a=1", { xid_booking: xid_booking, xchecked: xchecked },
                function (data) { }
           );
}