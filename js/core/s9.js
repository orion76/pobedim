function p9_pass_chg(event) {
    preventDefault(event);
    var t = event_target(event);
    var pw0 = $("#pw0").val();
    var pw1 = $("#pw1").val();
    var pw2 = $("#pw2").val();
    var lemail = $('input[name=lemail]').val();
    $("#m9errpwchg").html("");
    if (pw1 != pw2) {
        $("#m9errpwchg").html("Ошибка при вводе нового пароля");
    } else {
        ajax_post("m/m9_setting.php?ax=901", { xpw0: pw0, xpw1: pw1, xpw2: pw2, lemail: lemail }
                , function (data) {

                    var rz = json_parse(data);
                    if (rz['ERR'] == '') {
                        if (rz['DS'][0]['R'] == 'OK') {
                            $(t).html('Пароль изменён');
                        } else { $("#m9errpwchg").html("пароль не изменён, повторите операцию"); }
                    } else {
                        $("#m9errpwchg").html(rz['ERR']);
                    }
                }
            );
    }
}



function s9_st_update(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var t = event_target(event);
    var v = $(t).val();
    var f = $(t).attr('data-field');
    var zhref = "m/m9_st.php?ax=0";
    ajax_post("m/m9_st.php?ax=935", { f: f, v: v, st: st, sn: sn }, function (data) { s3menu_exec(zhref); });
}

function s9_stu_update(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var t = event_target(event);
    var v = $(t).val();
    var f = $(t).attr('data-field');

    t = getParentNode('LI', t);
    var n = $(t).find(".i9_u");
    var u = $(n).html();

    var zhref = "m/m9_st.php?ax=0";
    ajax_post("m/m9_st.php?ax=936", { f: f, v: v, st: st, u:u, sn: sn }, function (data) { s3menu_exec(zhref); });
}




function i9_lur_change(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var t = event_target(event);
    var v = $(t).val();
    t = getParentNode('LI',t);
    var n = $(t).find(".i9_u");
    var u = $(n).html();
    
    ajax_post("m/m9_st.php?ax=933", { st: st, v: v, u: u, sn: sn }, function (data) { });
}

function i9_ldy_change(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var t = event_target(event);
    var v = $(t).val();

    t = getParentNode('LI', t);
    var n = $(t).find(".i9_u");
    var u = $(n).html();
    ajax_post("m/m9_st.php?ax=934", { st: st, v: v, u: u, sn: sn }, function (data) { });
}

function i9_lau_change(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var t = event_target(event);
    var lau = $(t).val();
    var zhref = "m/m9_st.php?ax=0";
    ajax_post("m/m9_st.php?ax=928", { st: st, lau: lau, sn: sn }, function (data) { s3menu_exec(zhref); });
}

function btn9_u_add_click(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var v = $("#i9_u_").val();
    var zhref = "m/m9_st.php?ax=0";
    ajax_post("m/m9_st.php?ax=930", { st: st, v: v, sn: sn }, function (data) { s3menu_exec(zhref); });
}

function btn9_u_del_click(event) {
    var sn = $("#i9sn").val();
    var st = $("#i9st").val();

    var v = $("#i9_u_").val();
    var zhref = "m/m9_st.php?ax=0";
    ajax_post("m/m9_st.php?ax=931", { st: st, v: v, sn: sn }, function (data) { s3menu_exec(zhref); });
}