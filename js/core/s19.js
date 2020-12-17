function s19turnslist3_submit(event) {
    preventDefault(event);
    var n = event_target(event);
    var zpb = $("#i0bp").val();
    var zpe = $("#i0ep").val();
    var zsj = $("#i19sj").val();
    s19_load2div2(1908, "#m3center", zpe, null, zpb, zpe, '', zsj);
}




var m19aa = [];

function m19aa_tr(x) { return x["HTM"]; }
function s19aa_loaded()
{
    var t19aa = $("#t19aa");
    for (var i = 0; i < m19aa.length; i++) {
        var zo = m19aa_tr(m19aa[i]);
        t19aa.append(zo);
    }
}


function s19_2csv() {
    var aa = [[  'LG_OBJECT', 'ACC_B', 'ACC_C', 'SUM_DB', 'SUM_KB', 'SUM_D', 'SUM_K', 'CNT_T', 'LG_USER', 'SUM_T', 'SUM_DE', 'SUM_KE', 'DATE_B', 'DATE_E', 'SUM_DS', 'SUM_KS', 'DATE_S', 'CNT_S', 'NAME_OBJECT', 'NAME_ACC_B', 'NAME_ACC_C']];
    for (var i = 0; i < m19aa.length; i++) {
        var zo = m19aa[i];
        var a1 = [   zo['LG_OBJECT'], zo['ACC_B'], zo['ACC_C'], zo['SUM_DB'], zo['SUM_KB'], zo['SUM_D'], zo['SUM_K'], zo['CNT_T'], zo['LG_USER'], zo['SUM_T'], zo['SUM_DE'], zo['SUM_KE'], zo['DATE_B'], zo['DATE_E'], zo['SUM_DS'], zo['SUM_KS'], zo['DATE_S'], zo['CNT_S'], zo['NAME_OBJECT'], zo['NAME_ACC_B'], zo['NAME_ACC_C']];
        aa.push(a1);
    }
    fs_SaveToFileCSV('', aa);
}



function s19_doc_show(xid_doc)
{
    s23_doc_show('#div19doc', xid_doc);
    location.hash = "#div19doc";
}

function s19_chk_e_click(event) {
    var xe = $("#chk19e:checked").val();
    if (xe == 'on') { $("#p19e").show(); } else { $("#p19e").hide(); $("#chk19d:checked").removeAttr('checked'); $("#chk19k:checked").removeAttr('checked'); }
}

function s19_chk_vk_click(event) {
    var vd = $("#chk19d:checked").val();
    if (vd == 'on') { $("#chk19d:checked").removeAttr('checked'); }
}

function s19_chk_vd_click(event) {
    var vk = $("#chk19k:checked").val();
    if (vk == 'on') { $("#chk19k:checked").removeAttr('checked'); }
}

function chk19t_chk(chk, o, au, b) {
    ajax_post('p19_turns.php?ax=1901', { chk: chk.checked, o: o,  b: b }, function (data) { });
}

function s19_make_entry_checked() {
    ajax_post('p19_turns.php?ax=1902', {}, function (data) { });
}


function s19_sum_prd2(xopt, xacc_b, xacc_c, xdb, xde, xo, xu) {
    s19_load_div19period(xopt, xacc_b, xacc_c, '', '', xdb, xde, xo, xu);
}

function s19_sum_prd(xopt,xacc_b,xacc_c,xdb, xde, xo)
{
    s19_load_div19period(xopt, xacc_b, xacc_c, '', '', xdb, xde, xo,'');
}

function s19_cnt_be_dbl(event) { s19_load_div19('', ''); }

function s19_load_div19(vk, vd)
{
    var db = $("#i0bp").val();
    var de = $("#i0ep").val();
    s19_load_div19period('=','','',vk, vd, db, de ,'');
}

function s19_load_div19period(opt,xacc_b, xacc_c, vk, vd, db, de,o,u)
{
    var au = '';
    var dc = $("#i0d").val();
        
    o = trim(o.toLowerCase());
    if (o == null) { o = ''; }
    if (o == '' ) { o = $("#i19o").val(); }
    opt = opt + s19opt("#chk19xu", 'xu');

    $("#div19").html(s0_msg_waiting());
    ajax_post(ts("m/m19_turns_docs.php"
        + "?dc=" + dc + "&au=" + au + "&prdb=" + db + "&prde=" + de + "&o=" + o + "&xe=on" + "&b=" + xacc_b + "&c=" + xacc_c + "&vd=" + vd + "&vk=" + vk+ "&opt=" + opt)
        , {u:u} // , { dc:dc, au:au, prdb:db, prde:de, vo:vo, o:o, vb:vb, vc:vc, xe:xe, e:e, b:xacc_b, c:xacc_c, vd:vd, vk:vk, opt:xopt}
        , function (data) {
            $("#div19").html(data);
            location.hash = "#div19eod";
        });
}



function s19_load_div19prd3(x , xopt, xacc_b, xacc_c, o, u, db, de) {
    if (o == null) { o = ''; }
    if (o == '') { o = $("#i19o").val(); } //else { o = o.oj; }
    xopt = xopt + s19opt("#chk19xu", 'xu');

    var st = $('#i3st').val();

    $("#div19").html(s0_msg_waiting());
    ajax_post(ts("m/m19_turns_docs.php?ax=1920&prdb=" + db + "&prde=" + de  + "&xe=on" + "&b=" + xacc_b + "&c=" + xacc_c + "&opt=" + xopt)
        , { u: u, st:st , o:o  } 
        , function (data) {
            $("#div19").html(data);
            location.hash = "#div19eod";
        });
}



function s19opt(xs, xv)
{
    var z = $(xs).prop('checked');
    if (z) {return xv;} else {return '';}
}

function s19opt2(xs, xv) {
    var z = $(xs).prop('checked');
    if (z == undefined || z) { return xv; } else { return ''; }
}


function s19_load7(xdc, xau, xprdb, xprde) { s19_load7_2(xdc, null, xprdb, xprde, ''); }

function s19_load7_2(xdc, xau, xprdb, xprde, xopt)
{
    var sj = $("#i19sj").val();
    s19_load2div("#div7body", xdc, null, xprdb, xprde, xopt, sj);
}


function s19_load2div(xdiv, xdc, xau, xprdb, xprde, xopt, xsj) {    s19_load2div2(1907,xdiv, xdc, xau, xprdb, xprde, xopt, xsj);  }


function s19_load2div2(xax,xdiv, xdc, xau, xprdb, xprde, xopt, xsj) {
    var pp = $("#i19pp").val();
    if (pp == undefined) { pp = 'YY'; }

    var ro = $("#i19ro").val();
    var opt = s19opt2("#chk19o", 'vo')
            + s19opt("#chk19b", 'vb')
            + s19opt("#chk19c", 'vc')
            + s19opt("#chk19e", 'xe')
            + s19opt("#chk19d", 'vd')
            + s19opt("#chk19k", 'vk')
            + s19opt("#chk19xu", 'xu')
            + s19opt("#chk19s", 'vs')
            + xopt + pp;

    if (opt == '') { opt = 'vovb'; }

    var b = encodeURI($("#i19b").val());
    var c = encodeURI($("#i19c").val());
    var e = $("#i19e").val();
    var o = $("#i19o").val();
    var loj = encodeURI(o);
    var sp = $("#i19sp").val();
    var st = $("#i3st").val();
    $(xdiv).html(s0_msg_waiting());
    if (xax == 1908) {

      //  $("#m3center").load(ts("m/m19_turns.php?ax=" + xax + "&dc=" + xdc + "&st=" + st + "&pb=" + xprdb + "&pe=" + xprde + "&o=" + loj + "&e=" + e + "&b=" + b + "&c=" + c + "&opt=" + opt + "&ro=" + ro+"&pp="+pp+"&sj="+xsj));
        ajax_post(ts("m/m19_turns.php?ax=" + xax + "&dc=" + xdc + "&st=" + st + "&pb=" + xprdb + "&pe=" + xprde + "&o=" + loj + "&e=" + e + "&b=" + b + "&c=" + c + "&opt=" + opt + "&ro=" + ro + "&pp=" + pp + "&sj=" + xsj)
            , {}
            , function (data) {
                $("#m3center").html(data);
            });
    } else {
        ajax_post(ts("m/m19_turns.php?ax=" + xax + "&dc=" + xdc + "&au=" + xau + "&prdb=" + xprdb + "&prde=" + xprde + "&o=" + loj + "&e=" + e + "&b=" + b + "&c=" + c + "&opt=" + opt + "&ro=" + ro)
        , { sj: xsj, sp: sp, pp: pp }
        , function (data) {
            var rz = json_parse(data);
            $(xdiv).html(rz['HTML']);
            m19aa = rz['DS'];
            s19aa_loaded();
            s19_chk_e_click(null);
        }
        );
    }
}




function s19turnslist_refresh()
{
    var db = $("#i19prdb").val();
    if (db == undefined) {
         var zdate_cur = $("#i0d").val();
        var zprdb = $("#i0bp").val();
        var zprde = $("#i0ep").val();
        s19_load7(zdate_cur, null, zprdb, zprde);
    }
    else { 
        var de = $("#i19prde").val();
        var ro = $("#i19ro").val();
        var opt = 'pg';
        s19_load7( de, null, db, de);
    }
}



function s19show_entryitems(xid_entry, xid_doc) {
    //$("#id_entry_new").val(xid_entry);
    $("#div19entry").html(s0_msg_waiting());
    ajax_post("m16_entryitems.php", { e: xid_entry }
        , function (data) {
            $("#div19entry").html(data);
            $("#div19entry").show();
              var m16uploader = m7uploader(xid_entry, null);
            s19_doc_show(xid_doc);
        }
    );
}

function s19_doc_show(xid_doc) {
    s23_doc_show('#div19doc', xid_doc);
}