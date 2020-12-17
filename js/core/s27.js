
function s27_find_blur(event) {
    preventDefault(event);

    var i27find = $("#i27find").val();
    var d = $("#i27d").val();

    $("#t27found").html("...........................");
    ajax_post('p27_room_accepting.php?a=2701', { d: d, f: i27find }
     , function (data) {
         var rz = json_parse(data);
         $("#t27found").html(rz.TR);
     }
    );
}


function s27_ei_use(xid_entryitem) {
    $("#div27accepting").hide();
    var d = $("#i27d").val();
    ajax_post('p27_room_accepting.php?a=2702', { d:d, ei: xid_entryitem }
     , function (data) {
         var rz = json_parse(data);
         $("#div27accepting").html(rz.HTM);
         $("#div27accepting").show();
         location.hash = '#a27accepting';
     }
    );

}

function s27_accept_submit(event) {
    preventDefault(event);
    var q = $("#i27q").val();
    var ei = $("#i27ei").val();
    var d = $("#i27d").val();
    var bc = $("#i27bc").val();

    ajax_post('p27_room_accepting.php?a=2703', { ei: ei, q: q, d: d, bc: bc }
        , function (data) {
            var rz = json_parse(data);
            $("#div27accepting").hide();
        }
      );

}

function s27_articles_show() { }





