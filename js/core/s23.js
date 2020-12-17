
function s23dr_blur(event) {
    var v = event_target(event).value;
    if (v != '') {
        ajax_post("p31_subjects.php?ax=3101", { s: v }
                , function (data) {
                    var r = json_parse(data);
                    var rz = r['DS'][0];
                    if (rz['LG_SUBJECT'] == null) {
                        ajax_post("m/m31_subjects.php", { f: v }, function (data) {
                            $("#m23objects").html(data);
                            $("#t31subjects tr").click(s31subjects_click);
                            $("#btn31slct").click(s23dr_slct_click);
                            $("#btn31slct").show();
                            $("#m23objects").show();
                        }
                        );
                    }
                });
    }
}


function s23cr_blur(event) {
    var v = event_target(event).value;
    if (v != '') {
        ajax_post("p31_subjects.php?ax=3101", { s: v }
                , function (data) {
                    var r = json_parse(data);
                    var rz = r['DS'][0];
                    if (rz['LG_SUBJECT'] == null) {
                        ajax_post("m/m31_subjects.php", { f: v }, function (data) {
                            $("#m23objects").html(data);
                            $("#t31subjects tr").click(s31subjects_click);
                            $("#btn31slct").click(s23cr_slct_click);
                            $("#btn31slct").show();
                            $("#m23objects").show();
                        }
                        );
                    }
                });
    }
}


function s23dr_slct_click(event) {
    preventDefault(event);
    var v = $("#i31_LG_SUBJECT").val();
    $("#m23drdoc").val(v);
    $("#m23objects").hide();
}

function s23cr_slct_click(event) {
    preventDefault(event);
    var v = $("#i31_LG_SUBJECT").val();
    $("#m23crdoc").val(v);
    $("#m23objects").hide();
}



function s23_find_o() {
    var au = $("#s7_AU_L").val();
    var ox = $("#m23objdoc").val();
    ajax_post("m/m24_objects.php?a=1", { au: au, ox:ox },
        function (data) {
            $("#m23objects").html(data);
            $("#m23objects").show();
            $("#a23hideo").show();
            $("#a23showo").hide();
            if (ox != '') { $("#a24newo").show(); }
    });
}
function s23_hide_find_o() { $("#a23showo").show(); $("#a23hideo").hide(); $("#m23objects").hide();}



function s23_doc_submit(event) {
    preventDefault(event);
    var d = $("#m23doc").val();
    var dd = $("#m23datedoc").val();
    var nd = $("#m23ndxdoc").val();
    var sd = $("#m23sumdoc").val();
    var cy = $("#m23cydoc").val();
    var o = $("#m23objdoc").val();
    var au = $("#m23audoc").val();
    var r = $("#m23rdoc").val();
    var te = $("#m23tedoc").val();
    var dr = $("#m23drdoc").val();
    var cr = $("#m23crdoc").val();
    ajax_post("p23_doc.php?a=235", { d: d, dd: dd, nd: nd, sd: sd, cy: cy, o: o, au: au, r: r, te: te, cr: cr, dr: dr }
        , function (data) { s23_hide_find_o(); });
}


var ph23_doc = '';
/*
function s23_doc_show(xdiv, xid_doc) {
    preventDefault(event);
    ph23_doc = xdiv;
    $(xdiv).html(s0_msg_waiting());
    ajax_post('m23_doc.php', { d: xid_doc }, function (data) {
        $(xdiv).html(data);
        var e = $("#m23entry").val();
        s7show_entryitems2(e, xid_doc);
        $(xdiv).show();
    });
}
*/

function s23_accounting_add( xid_doc)
{
    ajax_post("m/p23_accounting.php?a=2301", {  d: xid_doc }, function (data) { s23_doc_show(ph23_doc, xid_doc); });
}

function s23_docaccounting(xid_doc) {
    var au = $("#s7_AU_L").val();
    ajax_post("p_docaccounting.php", { d: xid_doc, au: au }, function (data) { s23_doc_show(ph23_doc, xid_doc); });
}

function s23_accounting_del(xid_accounting) {
    ajax_post("m/p23_accounting.php?a=2303", { ia: xid_accounting }, function (data) {
        var zid_entry = $("#m23entry").val();
        var zid_doc = $("#m23doc").val();
        s16_doc_show(zid_doc);
    });
}

function s23_object_use(xlg_object, xau)
{
    $("#m23objdoc").val(xlg_object);
    $("#m23audoc").val(xau);
    $("#f23a").hide();
}

function s23_accounting_edit(xid_accounting) {
    $("#f23a").show();
    ajax_post("m/p23_accounting.php?a=2304", { ia: xid_accounting }
        , function (data) {
            var rz = json_parse(data);
            $("#s23a").html(xid_accounting);
            $("#i23d").val(rz['DATE_ACCOUNTING']);
            $("#i23b").val(rz['ACC_B']);
            $("#i23c").val(rz['ACC_C']);
            $("#i23sd").val(rz['SUM_D']);
            $("#i23sk").val(rz['SUM_K']);
            $("#i23doc").val(rz['ID_DOC']);
        }
     );
}

function s23_accounting_save(event) {
    preventDefault(event);
    var ia = $("#s23a").html();
    var b = $("#i23b").val();
    var c = $("#i23c").val();
    var sd = $("#i23sd").val();
    var sk = $("#i23sk").val();
    var doc = $("#i23doc").val();
    var d = $("#i23d").val();
    ajax_post("m/p23_accounting.php?a=2305", { ia: ia , b:b, c:c, sd:sd,sk:sk, doc:doc, d:d }
        , function (data) {
            s23_doc_show(ph23_doc, doc);
            $("#f23a").hide();
        }
     );
}

 

function s23_accounting_change(event)
{
    preventDefault(event);
    var n = event_target(event);
    while (n.tagName != 'TD') { n = n.parentNode; }
    var td = $(n);
    var tr = $(n.parentNode);
    var id = tr.attr('data-id');
    var p = td.attr('data-fld');
    var v = td.find('input').val();
    var t = td.attr('data-type');
    var ci = checkit(v, t);
    if (ci.valid)
    {
        td.removeClass('err');
        ajax_post("m/p23_accounting.php?a=2302", { ia: id, p: p, v: v }
            , function (data)
            {
                var rz = json_parse(data);
                if (rz.R != undefined){
                    if (rz.R.indexOf('ERR') > -1) { td.toggleClass('err', true); }
                } else
                {
                    tr.find('TD').each(function (i) {
                        var a = $(this).attr('data-fld');
                        if (a != undefined) { $(this).html(rz[a]); }
                    });
                }
            });
    } else { td.toggleClass('err', true); }
}
