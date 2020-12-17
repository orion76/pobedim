function s25_object_m(event)
{
    preventDefault(event);
	var i25toj = $("#i25toj").val();
	var i25oj = $("#i25oj").val();
	var i25ab = $("#i25ab").val();
    var i25oname = $("#i25oname").val();
    var i25otxt = $("#i25otxt").val();

    ajax_post('m/m25_object.php?ax=2502', { toj: i25toj, oj: i25oj, ab:i25ab, ojname: i25oname, ojtxt: i25otxt}
        , function (data) {
            var rz = json_parse(data);
        }
    );

}

function s25_new_sj_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var href = $(t).attr('href');
    ajax_post(href, {}, function (data) {
        s25oj_show_click(event);
    });
}

function s25oj_show_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var href = $(t).attr('href');
    var href = href.replace('ax=', 'ax=2500&xx=');
    ajax_post(href, {}, function (data) { $("#divOBJECT").html(data); $("#divOBJECT").show(); });
}

function s25sj_click(event) {
    preventDefault(event);
    var n = event_target(event);
    var zhref = n.attributes['href'].value;
    ajax_post(zhref, { on: 0 }, s31subjects_data);
}

function s25delete_confirm(event) {
    preventDefault(event);
    var o = event_target(event).parentNode;
    var oj = $("#i25oj").val();
    var ab = $("#i25ab").val();
    var sn = $("#i25sn").val();
    ajax_post("m/m25_object.php?ax=2598", { oj: oj, ab: ab, sn: sn }, function (data) { $(o).html(data); });
}
function s25delete_NO(event) {
    preventDefault(event);
    var o = event_target(event).parentNode;
    $(o).html("");
}
function s25delete_YES(event) {
    preventDefault(event);
    var oj = $("#i25oj").val();
    var ab = $("#i25ab").val();
    var sn = $("#i25sn").val();
    ajax_post("m/m25_object.php?ax=2599", { oj: oj, ab: ab, sn: sn }
        , function (data) {
            var r = data;
            if (r == '') { r = "О/у удалён!";}
            $(".divOJ").html(r);
        });
}

