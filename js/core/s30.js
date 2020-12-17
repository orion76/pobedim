

function s30delete_confirm(event) {
    preventDefault(event);
    var t = event_target(event).parentNode;
    var sj = $("#i31sj").val();
    var sn = $("#i31sn").val();
    ajax_post("m/m30_subject.php?ax=3098", { sj: sj, sn: sn }, function (data) { $(t).html(data); });
}
function s30delete_NO(event) {
    preventDefault(event);
    var t = event_target(event).parentNode;
    $(t).html("");
}
function s30delete_YES(event) {
    preventDefault(event);
    var sj = $("#i31sj").val();
    var sn = $("#i31sn").val();
    ajax_post("m/m30_subject.php?ax=3099", { sj: sj, sn: sn }
        , function (data) {
            var r = data;
            if (r == '') { r = "C/у удалён!"; }
            $(".divSJ").html(r);
        });
}



/*

    preventDefault(event);
    var n = event_target(event);
    var np = n.parentNode;
    var id = $(np).attr('data-id');
    var z = $('#i4x' + id);
    z.html('<input type="text" value="'+z.html()+'" onblur="s4_re_sm_u(this,'+id+')" />');
}

function s4_re_sm_u(i, id)
{
    ajax_post("p4_feedback.php?a=402", {id:id, t:i.value}, function (data) { $(i.parentNode).html(i.value); });
}

function frm4_submit(event) {
    preventDefault(event);
    var f = $("#m4form");
    var xusubj = f.find('input[id="usubj"]').val();
    var xumsg = f.find('textarea[id="umsg"]').val();

    var w2d = $("#chk4w2dir:checked").val();
    if (w2d == 'on') { w2d = 0; } else { w2d = 1; }

    ajax_post("p4_feedback.php?a=401", { w2d: w2d, xumsgtype: w2d, xusubj: xusubj, xumsg: xumsg },
            function (data) {
                if (data.indexOf("ERR", 0) == -1) {

                    var sg = $("#m4sg").val();
                    var t = ' обращение к директору  '+ xumsg + ' ' + xumsg;
                    ajax_post('p81_saying.php?ax=8103', { t: t, u: '', sgq: sg, tsg: 3 }, function (data) { });


                    DoMsg("ok", "Сообщение отправлено");
                    f.find('textarea[id="umsg"]').val("");
                    f.find('input[id="usubj"]').val("");
                }
                else { DoMsg("err", "ошибка"); }
            }
        );
}
*/