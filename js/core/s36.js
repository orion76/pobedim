var m36di = [];
var m36a = [];

function m36a_tr(xai) { return xai["HTM"]; }
function m36di_tr(xdi) { return xdi["HTM"]; }

function s36_bodyonload(event) {
    preventDefault(event);
    var ie = $("#m36entry").val();
    //var au = $("#m36audoc").val();
    //var m36uploader = s36uploader(ie, au, "file-m36uploader");
    m36init();
    s36a_loaded();
    s36di_loaded();
    s36sg_load();
}

function s36sg_load() {
var d = $("#m36doc").val();
    var zhref = 'm/m81_saying.php?ax=&sg='+$('#i36sgd').val()+'&d='+d; // n.attributes['href'].value
    $("#m36sg").load(zhref);
}

function s36sg_click(event) {
    preventDefault(event);
    var n = event_target(event);
    $("#m36sg").load(n.attributes['href'].value);
}



function s36state_change(event) {
    preventDefault(event);
    var d = $("#m36doc").val();
    var e = $("#m36entry").val();
    var ss = $("#m36ss").val();
    s36_doc_submit(event, d, e);
}


function s36a_loaded() {
    var zz = $("#t36a");
    for (var i = 0; i < m36a.length; i++) {
        var zr = m36a_tr(m36a[i]);
        zz.append(zr);
    }
}


/*

function s36_submit_new_topic(event) {
    preventDefault(event);
    var d = $("#m36doc").val();
    var t = $("#m36s81t").val();
    ajax_post('m/p81_saying.php?ax=8101', { t: t, d: d }, function (data) { $("#m36lsg").load('m/p81_saying.php?ax=8106&d=' + d); });
}

*/



function s36di_loaded() {
    var zz = $("#t36di");
    for (var i = 0; i<m36di.length; i++) {
        var zr = m36di_tr(m36di[i]);
        zz.append(zr);
    }
}



function s36_2csv() {
    var zr = null;
    var a1 = null;
    var aa = [];

    a1 = ['ID_DOC','AU','LG_OBJECT','DATE_ACCOUNTING','ACC_B','SUM_D','SUM_K','ACC_C','ID_ACCOUNTING'];
    aa.push(a1);

    for (var i = 0; i < m36a.length; i++) {
        zr = m36a[i];
        a1 = [
          zr['ID_DOC']
        , zr['AU']
        , zr['LG_OBJECT']
        , zr['DATE_ACCOUNTING']
        , zr['ACC_B']
        , zr['SUM_D']
        , zr['SUM_K']
        , zr['ACC_C']
        , zr['ID_ACCOUNTING']
        ];
        aa.push(a1);
    }

    aa.push([]);
    aa.push([]);
    aa.push([]);
    a1 = ['QNTY_DOCITEM', 'QNTY_STOCKING', 'PRICE_STOCKING', 'SUM_DOCITEM', 'MZ', 'CODE_DOCITEM', 'ID_OFFER', 'TEXT_ENTRYITEM', 'LIST_DOC'];
    aa.push(a1);
    for (var i = 0; i < m36di.length; i++) {
        zr = m36di[i];
        a1 = [
          zr['QNTY_DOCITEM']
        , zr['QNTY_STOCKING']
        , zr['PRICE_STOCKING']
        , zr['SUM_DOCITEM']
        , zr['MZ']
        , zr['CODE_DOCITEM']
        , zr['ID_OFFER']
        , zr['TEXT_ENTRYITEM']
        , zr['LIST_DOC']
        ];
        aa.push(a1);
    }

    fs_SaveToFileCSV('', aa);
}

       


/*
function s36uploader(xid_entry, xau, xelement) {
    var elmnt = document.getElementById(xelement);
    var uploader = null;
    if (elmnt != null) {
        uploader = new qq.FileUploader({
            element: elmnt,
            action: "p7_entry.php?a=5&e=" + xid_entry + "&au=" + xau,
            sizeLimit: 5500000,
            minSizeLimit: 0,
            debug: false,
            multiple: true,
            onComplete: function (id, fileName, responseJSON) {
                //$("#m22_H_IMGSRC").val(fileName);
                //$("#m22imgoffer").html('<img style="max-width:80;max-height:80" src="tmp/' + v_state.user + '/' + fileName + '" />');
                alert(1);
            }
        });
    }
    return (uploader);
}
*/


function s36_parse_filedoc2(xid_file, xfmt, xid_doc)
{
    
    if (xfmt == 'BANK-TXT1C') {
        $("#p16parsing" + xid_file).html('Процесс обработки файла запущен ');
        ajax_post("p23_doc_BNK1S.php?a=1", { f: xid_file }
            , function (data) { $("#p16parsing" + xid_file).html('Документ создан'); s36_show2(xid_doc, ''); }
            );
    }
    else {
        $("#p16parsing" + xid_file).html('Процесс обработки файла запущен ');
        ajax_post("p23_doc.php?a=161", { fmt: xfmt, idf: xid_file }
          , function (data) {
              var rz = json_parse(data);
              $("#p16parsing" + xid_file).html('Создаётся документ на основе данных ID ' + rz.ID);
              ajax_post("p23_doc.php?a=162", { idf: xid_file, id: rz.ID }
                      , function (data) {
                          $("#p16filedocs").html(data);
                          $("#p16parsing" + xid_file).html('Документ создан');
                          s36_show2(xid_doc, '');
                      });
          });
    }
 
}


function s36_sj_show(xd) {
    window.open('m/m31_subjects.php?px=1&d=' + xd);
}

function s36_sj_find(xst) {
    preventDefault(event);
    $("#m36sjfind").load('m/m31_subjects.php?ax=3101&px=0&m=m36&st=' + xst);
    $("#m36sjfind").show();
    $("#m36sjfindX").show();

}
function s36sjfindX_click(event) { preventDefault(event); $('#m36sjfind').hide(); $('#m36sjfindX').hide(); }


function s36_sj_btn31select(event) {
    preventDefault(event);
    var sj = $("#i31sj").val();
    $("#m36sjdoc").val(sj);
    $("#m36objdoc").val($("#i31_LG_OBJECT").val());
    $("#m36sjfind").empty();
}



function s36_edit_offer(xid_offer) {
    ajax_post('m22_edt_offer.php?a=1'
        , { o: xid_offer, g: '' }
        , function (data) { $("#d36o").html(data); }
        );
}

function s36_doc_del(event, xid_doc) {
    preventDefault(event);
    var n = event_target(event);
    n = getParentNode('TR', n);
    ajax_post("x/p36.php?ax=3602", { d: xid_doc }, function (data) { $(n).hide(); });
}

function s36_show(xid_doc) {
    s36_show2(xid_doc, null);
}

function s36_show2(xid_doc, xerr) {
    if (xerr == null) { xerr = '';}
    var url = "m/m36_doc.php?d=" + xid_doc + "&err=" + xerr;
    var tgt = "m36_" + xid_doc;
    if (xid_doc != '' && xid_doc != null) {
        w = window.open(url, tgt, '');
        
        return w;
    }
}

function s36_select_te(te) {
    $("#a36te_hlp").html('?');
}

function s36_te_help(event) {
    preventDefault(event);
    var te = $("#m36tedoc").val();
    ajax_post("x/p36.php?ax=3601", { te: te }
        , function (data) {
            var rz = json_parse(data);
            $("#a36te_hlp").html(rz['DS'][0]['TEXT_ENTRY_T']);
        }
    );
}



function s36dr_slct_click(event) {
    preventDefault(event);
    var v = $("#i31_LG_SUBJECT").val();
    $("#m36sjdoc").val(v);
    $("#m36find").hide();
}

function s36dr_blur(event) {
    var v = event_target(event).value;
    if (v != '') {
        ajax_post("x/p31_subjects.php?ax=3101", { s: v }
                , function (data) {
                    var r = json_parse(data);
                    var rz = r['DS'][0];
                    if (rz['LG_SUBJECT'] == null) {
                        ajax_post("m/m31_subjects.php", { f: v }, function (data) {
                            $("#m36find").html(data);
                            $("#t31subjects tr").click(s31subjects_click);
                            $("#btn31slct").click(s36dr_slct_click);
                            $("#btn31slct").show();
                            $("#m36find").show();
                        }
                        );
                    }
                });
    }
}

function s36_new_di(xd)
{
    $("#id_ei_new").val(0);
    $("#sum_ei_new").val('');
    $("#qnty_ei_new").val('');
    $("#code_ei_new").val('');
    $("#id_offer_new").val('');
    $("#text_ei_new").val('');
    $("#sumtax_ei_new").val('');
    $("#lgtax_ei_new").val('');
    $("#cntry_ei_new").val('');
    $("#ncustn_ei_new").val('');
    $("#f36ei").show();
}

function s36_edit_di(xei, xd) {
    ajax_post('m/m16_entry.php?ax=1604', { ei: xei, d: xd }
        , function (data) {
            var r = json_parse(data);
            var ds = r['DS'][0];
            $("#id_ei_new").val(xei);
            $("#sum_ei_new").val(ds.SUM_ENTRYITEM);
            $("#qnty_ei_new").val(ds.QNTY_ENTRYITEM);
            $("#code_ei_new").val(ds.CODE_ENTRYITEM);
            $("#id_offer_new").val(ds.ID_OFFER);
            $("#text_ei_new").val(ds.TEXT_ENTRYITEM);
            $("#sumtax_ei_new").val(ds.SUM_TAX);
            $("#lgtax_ei_new").val(ds.LG_TAX);
            $("#cntry_ei_new").val(ds.LG_COUNTRY);
            $("#ncustn_ei_new").val(ds.NCUSTOM_ENTRYITEM);
            $("#f36ei").show();
        });
}

function s36price_blur(xv) {
    xv = toNum(xv);
    var zq = toNum($("#qnty_ei_new").val());
    if (xv > 0) {
        $("#sum_ei_new").val(zq * xv);
    }
}

function s36_edit_di_submit(event) {
    preventDefault(event);
    var id_entryitem = toInt($("#id_ei_new").val());
    var sum_entryitem = toNum($("#sum_ei_new").val());
    var qnty_entryitem = toNum($("#qnty_ei_new").val());
    var code_entryitem = $("#code_ei_new").val();
    var text_entryitem = $("#text_ei_new").val();
    var id_offer = toInt($("#id_offer_new").val());

    var sumtax = $("#sumtax_ei_new").val();
    var lgtax = $("#lgtax_ei_new").val();
    var cntry = $("#cntry_ei_new").val();
    var ncustn = $("#ncustn_ei_new").val();

    var id_entry = toInt($("#m36entry").val());
    var id_doc = toInt($("#m36doc").val());

    ajax_post("m/p7_entry.php?a=702", {
        d: id_doc,
        e: id_entry,
        ei: id_entryitem,
        sum_entryitem: sum_entryitem,
        qnty_entryitem: qnty_entryitem,
        id_offer: id_offer,
        text_entryitem: text_entryitem,
        code_entryitem: code_entryitem,
        sumtax: sumtax,
        lgtax: lgtax,
        cntry: cntry,
        ncustn: ncustn
    }, function (data) {
        s36_show( id_doc);
    }
   );
}



function s36_docaccounting(xid_doc) {
    $("#b36accounting").hide();
    ajax_post("m/m36_doc.php?ax=3610", { d: xid_doc }
        , function (data) {
            var rz = json_parse(data); s36_show2(xid_doc, '.' + rz.ERR);
        });
}

function s36_accounting_del(xid_accounting) {
    ajax_post("m/p23_accounting.php?a=2303", { ia: xid_accounting }, function (data) {
        var d = $("#m36doc").val();
        s36_show(d);
    });
}

function s36chk1_click(event) {
    preventDefault(event);
    var z = 0;
    var chk = event_target(event);
    var n = $(chk.parentNode.parentNode.parentNode.parentNode);
    n.find('TR').each(function (i) {
        var ei = $(this).attr('data-id');
        if (ei != undefined) {
            var o = $(this).find('input[type="checkbox"]');

            ajax_post("m/p7_entry.php?a=78", { ei: ei, v: chk.checked }
                    , function (data) {
                        var rz = json_parse(data);
                        if (rz.R == 'CHECKED') { o[0].checked = true; }
                        else { o[0].checked = false; }
                    });
        }
    });

}

function s36btn_stock_it(xd, xt) {
    ajax_post("m/p7_entry.php?a=711", { d: xd, t: xt }
        , function (data) {
            var rz = json_parse(data);
            s36_show(xd);
        });
}

function s36btn_di(xd, xt) {
    ajax_post("m/p7_entry.php?a=710", { e: xd, t: xt }
        , function (data) {
            var rz = json_parse(data);
            s36_show(xd);
        });
}

function s36_doc_add(xid_entry, xt_entry) {
    ajax_post('m/m36_doc.php?ax=3670', { e: xid_entry, te: xt_entry }, function (data) {
        var rz = json_parse(data);
        s36_show(rz.ID_DOC);
    });
}


function s36_hide_find_o() { $("#a36showo").show(); $("#a36hideo").hide(); $("#m36find").hide(); }

function s36_doc_submit(event,d,e) {
    preventDefault(event);

    var ss = $("#m36ss").val();
    var d_ct = $("#fm36d input[name=d_ct]").val();

    //var d = $("#m36doc").val();
    var dd = $("#m36datedoc").val();
    var nd = $("#m36ndxdoc").val();
    var sd = $("#m36sumdoc").val();
    var cd = $("#m36creditdoc").val();
    var md = $("#m36matdoc").val();
    var cy = $("#m36cydoc").val();
    var r = $("#m36rdoc").val();
    var te = $("#m36tedoc").val();
    var sj = $("#m36sjdoc").val();
    var sj_ = $("#m36sj_").val();

    var drg = $("#i36drg").val();
    var drd = $("#i36drd").val();

    var sa = $("#c36sa").prop('checked');
    ajax_post("m/m36_doc.php?ax=3635", { d: d, drd: drd, drg: drg, dd: dd, nd: nd, sd: sd, cy: cy, r: r, te: te, sj: sj, sa: sa, cd: cd, md: md, d_ct: d_ct, ss:ss ,sj_:sj_ }
        , function (data)
        {
            var rz = json_parse(data);
            if (d != e) { s36_show2(e, rz.ERR); }
             s36_show2(d,rz.ERR); //
           /* w = s36_show2(e, rz.ERR);
            if (d != e) {
                window.close();
                w.bringToFront();
            } */
        });
}

function s36_doc_file(xid_doc) {
    ajax_post("m/p23_accounting.php?ax=2307", { d: xid_doc }, function (data) {   } );
}

function s36_accounting_add(xid_doc) {
    ajax_post("m/p23_accounting.php?ax=2301", { d: xid_doc }, function (data) { s36_show( xid_doc); } );
}
function s36_accounting_clear(xid_entry) {
    ajax_post("m/p23_accounting.php?ax=2306", { e: xid_entry }, function (data) { s36_show(xid_entry); });
}

function s36_accounting_edit(xid_accounting) {
    $("#f36a").show();
    ajax_post("m/p23_accounting.php?a=2304", { ia: xid_accounting }
        , function (data) {
            var rz = json_parse(data);
            var rs = rz['DS'];
            $("#s36a").html(xid_accounting);
            $("#i36d").val(rs[0]['DATE_ACCOUNTING']);
            $("#i36b").val(rs[0]['ACC_B']);
            $("#i36c").val(rs[0]['ACC_C']);
            $("#i36sd").val(rs[0]['SUM_D']);
            $("#i36sk").val(rs[0]['SUM_K']);
            $("#i36doc").val(rs[0]['ID_DOC']);
        }
     );
}

function s36_accounting_save(event) {
    preventDefault(event);
    var ia = $("#s36a").html();
    var b = $("#i36b").val();
    var c = $("#i36c").val();
    var sd = $("#i36sd").val();
    var sk = $("#i36sk").val();
    var doc = $("#i36doc").val();
    var d = $("#i36d").val();
    ajax_post("m/p23_accounting.php?a=2305", { ia: ia, b: b, c: c, sd: sd, sk: sk, doc: doc, d: d }
        , function (data) {
            s36_show(doc);
            $("#f36a").hide();
        }
     );
}

function s36_accounting_del(xid_accounting) {
    ajax_post("m/p23_accounting.php?a=2303", { ia: xid_accounting }, function (data) { var zid_doc = $("#m36doc").val(); s36_show(zid_doc); });
}


function btn36invoice_recall(xid_invoice) {
    ajax_post("m/m36_doc.php?ax=3613", { i: xid_invoice }, function (data) { });
}