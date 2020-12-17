
function btn17_newentry_click(event) {
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

var m17ei = [];

function s17_2csv() {
    var zr = null;
    var a1 = null;
    var aa = [];
    a1 = ['ID_ENTRY', 'ID_DOC', 'DATE_ENTRY', 'SUM_ENTRY', 'DIFF_EI', 'SUM_CREDIT', 'TEXT_ENTRY', 'ENTRYNAME'
        , 'T_ENTRY', 'LG_DELIVERY', 'LG_BUYER', 'USERS', 'SYS_T'];
    aa.push(a1);

    for (var i = 0; i < m16ei.length; i++) {
        zr = m17ei[i];
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



function s17ei_load() {
    var zst = $("#i3st").val();

    var zdate_cur = $("#i0d").val();
    var zprdb = $("#i0bp").val();
    var zprde = $("#i0ep").val();
    var zs = $("#i16sumfind").val();
    var zte = $("#t_entry").val();
    var ztte = $("#i17tte").val();
    var zopt = '';

    var zsj = $("#i17sj").val();
    ajax_post("m/m17_li.php?ax=1705"
        , { dc: zdate_cur, prdb:zprdb ,  prde:zprde, te:zte, s:zs , tte:ztte , opt:zopt , sj:zsj , st:zst}
        , function (data) {
            var rz = json_parse(data);
            m17ei = rz['DS'];
            s17ei_arrange(0);
        });
}



function s17ei_arrange(xso) {
    var zwoa = $("#chk16woa").prop('checked');
    var zsysa = $("#chk16sysa").prop('checked');

    var zz = $("#t16ei");
    zz.html('');
    for (var i = 0; i < m17ei.length; i++) {
        if (!zwoa || (zwoa && m17ei[i].CNT_A == "0")) {
            if (( !zsysa ) || (zsysa && m17ei[i].SYS_A == "0")) {
                var zr = m17ei_tr(m17ei[i]);
                zz.append(zr);
            }
        }
    }
    $("#t16ei tr").click(s16ei_click);
}



function s17he_toggle(event) {
    preventDefault(event);
    $("#t16ei a.txtmore").show();
    $("#t16ei span:visible").hide();
    var n = $(event_target(event).parentNode);
    n.find('span').show();
    //n.find('ul').css('background-color','lightyellow');
    n.find('a.txtmore').hide();
}


function s17_show_el(event) {
    s17ei_load();
}


function s17ei_click(event) {
    var et = event_target(event);
    if (et.nodeName == 'TD') {
        preventDefault(event);
        $("#t16ei tr").toggleClass("tr_selected", false);
        $(et.parentNode).toggleClass("tr_selected", true);
    }
}
