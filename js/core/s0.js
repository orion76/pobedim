var zMilisec = 0;
var v_state = { cmd:'', filter: '', user: '', ismngr: '', menu: 0, menusrc: '', sn: '', DY: [], href:'' };
var v_Timer = { cnt:0, cnt50:0, cnt20:0, runner:null };

var CMD = [];




function RunTimer(xMilisec) { zMilisec = xMilisec; RunOnTimer(); }

function RunOnTimer() {

    if (v_Timer.runner != null) {
        clearInterval(v_Timer.runner);
        v_Timer.runner = null;
        var s = ''+ $("#timertick").html();
        if (v_state.user == '') {
            v_Timer.cnt = 1;
            $("#timertick").html("");
            s00timer_scrolling();

            var s = $("#display_statistics");
            if (s != null) {
                s = trim(s.html());
                if (s == '') {
                    ajax_post("m/p9_user.php?ax=903", {}, function (data) { var rz = json_parse(data); $("#display_statistics").html(rz['CNT_VISITERS']); });
                }
            }
        } else {
            v_Timer.cnt++;
            if (s.length > 50) { s = "!"; }
            $("#timertick").html(s + "*");
            if (v_Timer.cnt20++ > 20) { v_Timer.cnt20 = 0; ChkEvents(); }
            if (v_Timer.cnt50++ > 50) { v_Timer.cnt50 = 0; msg0_setclass("#sysmsg", ""); }
        }
        s41timer();
    }
    if (v_Timer.runner == null) {
        v_Timer.runner = setInterval(RunOnTimer, zMilisec);
    }
}


function s00timer_scrolling() {
    var lo = $('#scroll_img').find('img');
    var o1 = $('#scroll_img').find('img:visible'); 
    lo.hide();
    var i1 = o1.index();
    var o2 = lo.eq(i1+1);
    if (o2.length == 0) { o2 = lo.first(); }
    o2.show();
}

function s00click_scrollimg(event) {
    var o = event_target(event);
    var lo = $(o).parent().find('img');
    lo.hide();
    var o2 = $(o).next();
    if (o2.length == 0) { o2 = lo.first(); }
    o2.show();
}




function ChkEvents() {
    ajax_post("m/p9_user.php?ax=902", {}
        , function (data) {
            var rz = json_parse(data);
            DoMsg('wrn', rz.MSG);
        });
}


function i0bp_dblclick_v(x){ $('#i0ep').val(x);}
function i0ep_dblclick_v(x){ $('#i0bp').val(x); $('#i0d').val(x); }

function msg0_setclass(id, cls) {
    var o = $(id);
    o.removeClass("wrn");
    o.removeClass("ok");
    o.removeClass("err");
    o.removeClass("msg");
    if (cls != '') { o.addClass(cls, false); }
}

function s0_yesterday_a(k) {
    var d = new Date();
    var ds = new Date();
    ds.setDate(d.getDate() + k);
    var s = fmtDDMMYYYY(ds);
    $("#i0d").val(s);
    $("#i0bp").val(s);
    $("#i0ep").val(s);
}

function s0_yesterday_m(k) {
    var d = new Date();
    var m = d.getMonth();
    d.setMonth(m, 1);
    m = m + k;
    if (m < 1) { m = m + 12; }
    if (m > 12) { m = m - 12; d.setYear(d.getYear()-1);}
    d.setMonth(m, 1);
    var s = fmtDDMMYYYY(d);
    $("#i0bp").val(s);
    if (m == 12) { d.setMonth(12,31); } else { d.setMonth(m + 1,1); d.setDate(d.getDate() - 1); }
    s = fmtDDMMYYYY(d);
    $("#i0ep").val(s);
}

function s0_curdate_change(e) {
    var sd = $('#i0d').val();
    ajax_post('m0_cmdline.php?ax=3', {sd:sd}
        , function (data) {
            var rz = json_parse(data);
            $('#i0d').val(rz['DATE_SESSION']);
            // alert($('#i0d').val());
        });
}


function s0fmt_acc(x)
{
    if (x == '' || x == null) { return ''; }
    var f = x.match(/([0-9]+)(?:[,`\.-])([0-9]+)/i);
    if (f == null) {
        f = x.match(/(?:[,`\.-])([0-9]+)/i);
        if (f == null) {
            f = x.match(/([0-9]+)(?:[,`\.-])$/i);
            if (f == null) { return ''; }
            else { return f[1] + '`'; }
        } else { return '`' + f[1]; }
    }
    if (f.length == 2) { return f[1]+'`'; }
    if (f.length > 2) { return f[1]+'`'+f[2]; }
}

function s0fmt_acc2(t)
{
    var z = $(t);
    var x = s0fmt_acc(z.val());
    if (x != '') { z.val(x); z.toggleClass('red', false); }
    else { z.toggleClass('red', true); }
}



function s0lru(l) {
    if (l == null) {
        l = '';
    }
    for (var i = 1; i <= 9; i++) {
        var li = 'M0' + i;
        if (l.indexOf(li) == -1) { $("." + li).hide(); } else { $("." + li).show(); }
    }
    //if (v_state.lur.indexOf('Q7')) { $("#submenu7").show(); } else { $("#submenu7").hide(); }

    for (var i = 1; i <= 9; i++) {
        var li = 'N0' + i;
        var v = true;
        if (l.indexOf(li) == -1) { v = true; } else { v = false; }
        $("." + li).prop('disabled',v); 
    }
}


function btn00enter_focus(event){ $("#btn00enter").show();}
function btn00enter_blur(event) { $("#btn00enter").hide(); }


    
function s0_popstate(event) {
    // v_state == event.state
}


function s0_menu_showtext(x_div_t) {
    $("#t001").hide();
    $("#t002").hide();
    $("#t003").hide();
    if (x_div_t > 0) {
        $("#t00" + x_div_t).show();
        $(".menu0").show();
        $("#menu00" + x_div_t).hide();
        s0lru('');
    }
}

 


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}



function s0_waiting_show() {
    $("#msg_waiting").show();
}
function s0_waiting_hide() {
    $("#msg_waiting").hide();
}

function s0_msg_waiting() {
    return '<img src="loading.gif" alt="Запрос выполняется"  height="30px" width="30px" />';
}

function ClearMsg() {
    DoMsg('', '');
}

function DoMsg(cls, msg) {
    $("#sysmsg").html(msg);
    msg0_setclass('#sysmsg', cls);
}

function GetMsg() { return $("#sysmsg").html(); }