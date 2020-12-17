
function m7au_select(xselect) {
    $("#div7body").html("");
    var au = $("#s7_AU_L").val();
    setCookie('au', au, 5000);
}



function menu7_click(xmi) {
    var zAU = $("#s7_AU_L").val();
    var zdate_cur = $("#i0d").val();
    var zprdb = $("#i0bp").val();
    var zprde = $("#i0ep").val();
    s0_menu_showtext(0);

    s0_waiting_show();

    if (xmi == 16) {
        $("#div7body").load(ts("m16_entry.php?dc=" + zdate_cur + "&au=" + zAU + "&prdb=" + zprdb + "&prde=" + zprde));
    }

    if (xmi == 17) {
        $("#div7body").load(ts("m17_jobtime.php?a=171&dc=" + zdate_cur + "&au=" + zAU + "&prdb=" + zprdb + "&prde=" + zprde));
    }

    if (xmi == 19) {
        s19_load7(zdate_cur, zAU, zprdb, zprde);
    }

    if (xmi == 21) {
        $("#body3").load("m21_offers.php");
    }

    if (xmi == 24) {
        $("#div7body").load(ts("m/m24_objects.php?a=1&au=" + zAU));
    }


    if (xmi == 31) {
        ajax_post(ts("m/m31_subjects.php?ax=3101&au=" + zAU), {}
            , function (data) {
                $("#div7body").html(data);
                $("#t31subjects tr").click(s31subjects_click);
            }
        );
    }


    if (xmi == 33) {
        $("#div7body").load(ts("m33_layouts.php?a=1&au=" + zAU));
    }

    if (xmi == 51) {
        $("#div7body").load(ts("m51_supplyings.php"));
    }


    if (xmi == 34) {
        var aa = $("#i34aa").val();
        var go = $("#i34go").val();

        ajax_post("m/m34_stocking_turns.php", {a:1,au:zAU,pb:zprdb,pe:zprde,aa:aa,go:go}
            , function (data) {
           
            $("#div7body").html(data);
           } );
        //$("#div7body").load(ts("m34_stocking_turns.php?a=1&au=" + zAU + "&pb=" + zprdb + "&pe=" + zprde+"&aa="+aa+"&go="+go));
    }
    if (xmi == 38) {

        var go = $("#i38go").prop('checked');
        if (go) { go = 'GO'; } else { go = ''; };
        var m = '&m=' + go;

        var o = $("#i38obj").val();
        if (o == undefined || o == null) { o = ''; }
        $("#div7body").load(ts("m/m38_article_turns.php?ax=1&au=" + zAU + "&pb=" + zprdb + "&pe=" + zprde+"&o="+o+m));
    }

    s0_waiting_hide();
}


function s7show_newentryitem() {
    $("#ENTRYITEMS_L tr").toggleClass("tr_selected", false);
    $("#id_entryitem_new").val(null);
    $("#sum_entryitem_new").val(null);
    $("#qnty_entryitem_new").val(null);
    $("#code_entryitem_new").val(null);
    $("#id_offer_new").val(null);
    $("#text_entryitem_new").val(null);
    $("#div7_entryitem_new").show();
}

function s7entry_id_click(event) {
    var et = event_target(event);
    if (et.tagName != 'A') {
        var o = event_target(event).parentNode;  
        preventDefault(event);
        var id_entry = o.getAttribute("data-id");
        $("#t_ENTRY_L tr").toggleClass("tr_selected", false);
        $(o).toggleClass("tr_selected", true);
    }
}


/*
function s7show_entryitems(xid_entry) {
    s7show_entryitems2(xid_entry, null);
}

function s7show_entryitems1(xid_entry) {
    $("#ENTRYITEMS_L").html(s0_msg_waiting());
    ajax_post("m16_entryitems.php", { e: xid_entry, d: xid_entry }
        , function (data) {
            $("#ENTRYITEMS_L").html(data);
            $("#id_entry_new").val(xid_entry);
            $("#ENTRYITEMS_L").show();
            // var au = $("#s7_AU_L").val();
            // var m16uploader = m7uploader(xid_entry, au);
            // s16_doc_show(xid_entry);
        }
    );
}


function s7show_entryitems2(xid_entry, xid_doc)
{
    var zid_doc = $("#div16doc").html();
    if (zid_doc == undefined || zid_doc != xid_doc) {
        $("#id_entry_new").val(xid_entry);

        $("#ENTRYITEMS_L").html(s0_msg_waiting());
        ajax_post("m16_entryitems.php", { e: xid_entry, d:xid_doc }
            , function (data) {
                $("#ENTRYITEMS_L").html(data);
                $("#ENTRYITEMS_L").show();
                // var au = $("#s7_AU_L").val();
                // var m16uploader = m7uploader(xid_entry, au);
            }
        );
    }
}

function s7show_docitems(xid_entry, xid_doc) {
    $("#id_entry_new").val(xid_entry);
    $("#ENTRYITEMS_L").html(s0_msg_waiting());
    ajax_post("m16_entryitems.php", { e: xid_entry, d: xid_doc }
        , function (data) {
            $("#ENTRYITEMS_L").html(data);
            $("#ENTRYITEMS_L").show();
           // var au = $("#s7_AU_L").val();
           // var m16uploader = m7uploader(xid_entry, au);
        }
    );
}

*/

function s7chk_click(event) {
    var chk = event_target(event); 
    var o = chk.parentNode.parentNode;
    var ei = o.getAttribute("data-id");
   
    ajax_post("m/p7_entry.php?a=78", { ei: ei, v: chk.checked }
        , function (data) {
            var rz = json_parse(data);
            if (rz.R == 'CHECKED') { chk.checked = true; }
            else { chk.checked = false; }
    });
}

 

function m7_entry_del(event) {
    preventDefault(event);
    var o = event_target(event).parentNode.parentNode;
    var zid_entry = o.getAttribute("data-id");
    ajax_post('m/p7_entry.php?a=4', { e: zid_entry },
        function (data) {
            var rz = json_parse(data);
            if (rz.SYS_F == -1) { $(o).toggleClass("deleted", true); }
            else { $(o).removeClass("deleted"); }
        });
}


function m7_sum_entry_onchange() {
    var o = $("#sum_entry");
    var b = o_IsErrNum(o);
}


function m7_cnt_entry_onchange() {
    var o = $("#cnt_entry");
    var b = o_IsErrInt(o);
    var zo = $("#div7_entryitem_new");
    if (b || o.val() == 0) { zo.hide(); } else { zo.show(); }
}


function s7uploader(xid_entry, xau, xelement)
{
    var elmnt = document.getElementById(xelement);
    var uploader = null;
    if (elmnt != null) {
        uploader = new qq.FileUploader({
            element: elmnt,
            action: "m/p7_entry.php?a=5&e=" + xid_entry + "&au=" + xau,
            sizeLimit: 5500000,
            minSizeLimit: 0,
            debug: false,
            multiple: true,
            onComplete: function (id, fileName, responseJSON) {
                //$("#m22_H_IMGSRC").val(fileName);
                //$("#m22imgoffer").html('<img style="max-width:80;max-height:80" src="tmp/' + v_state.user + '/' + fileName + '" />');
            }
        });
    }
    return (uploader);
}

function m7uploader(xid_entry, xau) {
    xelement = "file-m16uploader";
    return ( s7uploader(xid_entry, xau, xelement) );
}




function m7entry_submit(event) {
    preventDefault(event);
    var f = $("#form_entry");
    var zt_entry = $("#t_entry"); //f.find( \'select[id="t_entry"]\' );
    var date_entry = $("#i0d").val();  // _getDate();
    var sum_entry = toNum($("#sum_entry").val());
    var cnt_entry = toInt($("#cnt_entry").val());
    var cy_entry = "RUB";
    var t_entry = zt_entry.val();
    var text_entry = $("#text_entry").val();
    var XAU = $("#s7_AU_L").val();
    var pmt = $("#s16pmt").val();

    ajax_post("m/p7_entry.php?a=71", {
        au: XAU,
        date_entry: date_entry,
        pmt: pmt,
        cnt_entry: cnt_entry,
        sum_entry: sum_entry,
        cy_entry: cy_entry,
        t_entry: t_entry,
        text_entry: text_entry
    },
    function (data) {
        var rz = json_parse(data);
        s16_entry_showlist();
        $("#sum_entry").val('');
        $("#cnt_entry").val('');
        $("#LG_SUBACCOUNT_ENTRY").val('');
        $("#text_entry").val('');
    }
  );
}