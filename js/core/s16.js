
function btn16_newdoc_onclick(event) {
    var bn = event_target(event);
    $(bn).hide();
    var st = $("#i3st").val();
    ajax_post("x/p36.php?ax=3606", { st: st }, function (data) {
        var rz = json_parse(data);
        s36_show2(rz['DS'][0]['ID_DOC'], null);
    });
}


function btn16_newentry_click(event) {
    var st = $("#i3st").val();
    var ds = $("#i0d").val();
    var bn = event_target(event);
    $(bn).hide();

    ajax_post("x/p36.php?ax=3606", { st: st, ds: ds }, function (data) {
        var rz = json_parse(data);
        var d = rz['DS'][0]['ID_DOC'];
        s36_show2(d, null);
    });

}

var m16ei = [];
//var m16bp = "";
//var m16ep = "";


function s16_2csv() {
    var zr = null;
    var a1 = null;
    var aa = [];
    a1 = ['ID_ENTRY', 'ID_DOC', 'DATE_ENTRY', 'SUM_ENTRY', 'DIFF_EI', 'SUM_CREDIT', 'TEXT_ENTRY', 'ENTRYNAME'
        , 'T_ENTRY', 'LG_DELIVERY', 'LG_BUYER', 'USERS', 'SYS_T'];
    aa.push(a1);

    for (var i = 0; i < m16ei.length; i++) {
        zr = m16ei[i];
        a1 = [
          zr['ID_ENTRY']
        , zr['ID_DOC']
        , zr['DATE_ENTRY']
        , zr['SUM_ENTRY']
        , zr['DIFF_EI']
        , zr['SUM_CREDIT']
        , zr['TEXT_ENTRY']
        , zr['ENTRYNAME']
        , zr['T_ENTRY']
        , zr['LG_DELIVERY']
        , zr['LG_BUYER']
        , zr['USERS']
        , zr['SYS_T']
        ];
        aa.push(a1);
    }

    fs_SaveToFileCSV('', aa);
}


/*
function s16ei_loaded(xbp, xep) {
    //if (m16bp != xbp || m16ep != xep)
    {
        s16ei_arrange(0);
        m16bp = xbp;
        m16ep = xep;
    }
}
*/

function s16ei_load() {
    var zst = $("#i3st").val();

    var zdate_cur = $("#i0d").val();
    var zprdb = $("#i0bp").val();
    var zprde = $("#i0ep").val();
    var zs = $("#i16sumfind").val();
    var zte = $("#t_entry").val();
    var ztte = $("#i16tte").val();
    var zopt = '';

    var zsj = $("#i16sj").val();
    ajax_post("m/m16_entry.php?ax=1605"
        , { dc: zdate_cur, prdb:zprdb ,  prde:zprde, te:zte, s:zs , tte:ztte , opt:zopt , sj:zsj , st:zst}
        , function (data) {
            var rz = json_parse(data);
            m16ei = rz['DS'];
            s16ei_arrange(0);
        });
}




function s16ei_arrange(xso) {
    var zwoa = $("#chk16woa").prop('checked');
    var zsysa = $("#chk16sysa").prop('checked');

    var zz = $("#t16ei");
    zz.html('');
    for (var i = 0; i < m16ei.length; i++) {
        if (!zwoa || (zwoa && m16ei[i].CNT_A == "0")) {
            if (( !zsysa ) || (zsysa && m16ei[i].SYS_A == "0")) {
                var zr = m16ei_tr(m16ei[i]);
                zz.append(zr);
            }
        }
    }
    $("#t16ei tr").click(s16ei_click);
}

function s16reaccount() {
    var zz = $("#t16ei");
    DoMsg('', '');
    for (var i = m16ei.length-1; i >= 0; i--) {
        var ze = m16ei[i]['ID_ENTRY'];
        ajax_post("x/p36.php?ax=3610", { d: ze }
            , function (data) {
                var rz = json_parse(data);
                var zd = rz['ID_DOC'];
                var zr = zz.find("[data-id='" + zd + "']");
                var s = GetMsg();
                if (rz.ERR != '') {
                    s = s + '<br/>' + zd + ':' + rz.ERR + '<br/>'; DoMsg('err', s);
                    zr.attr('style', 'background-color: red');
                }
                else { zr.attr('style', 'background-color: lightblue'); }
            });
    }
}



function s16he_toggle(event) {
    preventDefault(event);
    $("#t16ei a.txtmore").show();
    $("#t16ei span:visible").hide();
    var n = $(event_target(event).parentNode);
    n.find('span').show();
    //n.find('ul').css('background-color','lightyellow');
    n.find('a.txtmore').hide();
}


function s16_new51_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var zhref = t.attributes['href'].value;
    var n = getParentNode('LI', t); // $(n).hide();
    ajax_post(zhref, {}, function (data) {
        var rz = json_parse(data);
        var zd = rz['ID_DOC']; //rz['DS'][0]['ID_DOC'];
        s36_show2(zd, '');
        s16ei_load();
    });
    
}

function s16_show_el(event) {
    s16ei_load();
}


function s16_entry_select(xselect) {  
    var ztte = $("#i16tte").val();
    if (xselect > 0) {
        $("#sum_entry").val('');
        $("#cnt_entry").val('');
        $("#LG_SUBACCOUNT_ENTRY").val('');
        $("#text_entry").val('');
        ztte = ztte + ',' + $("#t_entry").val();
        $("#i16tte").val(ztte);
    } else {  $("#t_entry").val(-1); }
    s16ei_load();
}


function s16ei_click(event) {
    var et = event_target(event);
    if (et.nodeName == 'TD') {
        preventDefault(event);
        $("#t16ei tr").toggleClass("tr_selected", false);
        $(et.parentNode).toggleClass("tr_selected", true);
    }
}
