
function s44(event) {
    preventDefault(event);
}


function s44_show_sj(event) {
    var t = event_target(event);
    var href = $(t).attr('href');
    var sj = $("#i44sj").val();
    var st = $("#i44st").val();
    href = href + '&px=1&sj=' + encodeURI(sj) + '&st=' + st;
    $(t).attr('href',href);
}









function s44bt_change(event) {
    var t = event_target(event);
    var bt = $(t).val();
    var ibt = $("#i44ibt").val();
    ajax_post("m/m44bt.php?ax=4408", { bt: bt, ibt: ibt } , function (data) { });
}

function s44sj_blur(event) {
    preventDefault(event);
    var sj = $("#i44sj").val();
    var ibt = $("#i44ibt").val();
    ajax_post("m/m30_subject.php?ax=3004", { sj: sj ,ibt:ibt }
        , function (data) {
            var rz = json_parse(data);
            var rz_ =rz['DS'][0];
            var t_ = '';
            if (rz_['LG_SUBJECT'] != sj && sj != '') { t_ = '<font color="red">карта не найдена</font>'; }
                else { t_ = rz_['NAME_SUBJECT'] + ' ' + rz_['LG_OBJECT'] + ' ' + rz_['TEXT_SUBJECT']; }
            $(".s44sj").html(t_);
            $(".i44sj").val(sj);
            $("#i44ibt").val(rz_['ID_BASKET']);
            s44bt_reload();
        });
}

function s44bt_reload() {
   
    var bt = $("#i44ibt").val();
    var st = $("#i44st").val();
    var sj = $("#i44sj").val();
 
    ajax_post("m/m44bt.php?m=300", { st:st, sj:sj, bt: bt, ibt:bt }, function (data) { $("#m3center").html(data); });
}


function s44bt_mouseover_content(event) {
    preventDefault(event);
    var ibt = $("#i44ibt").val();
    var st = $("#i44st").val();
    ajax_post("m/m45bt.php", { st: st, ibt:ibt }
        , function (data) {
            var rz = json_parse(data);
            $(".basketsum").html(rz['SUM_BASKET']);

            var dv = $(".div45").parent();
            $(dv).html(rz['HTM']);
            $(dv).show();
            //$("#m3header").html(data);
            //$("#m3header").show();
            
        });
}


function chg44_text_comment(event) {
    var bt = $("#i44bt").val();
    var tc = $('#i44tcmnt').val();
    var ta = $('#i44ta').val();
    ajax_post('m/m44bt.php?ax=4416', { bt: bt, ta: ta, tc: tc }, function (data) { })
}



function s44_calc_offer_sum(n) {
    n = getParentNode('LI', n);
    var q = $(n).find(".i2_q").val();
    var nt = $(n).find('input[name=s_bg]');
  //  var pdc = 0.0 + $(n).find('.pdc').html(); if (isNaN(pdc)) { pdc = 0; }
    $(nt).each(
        function () {
            var i = getParentNode('TR', this);
            var pc = 0.0 + $(i).find('.pc_va').text();
             if (pc != 0) {
              //  var tr = 1.0 + (0.0 + $(i).find('.tva_rate').html()) / 100.0;
                var s_ = pc  * q; //* tr
              //  var sdc = s_ *  pdc / 100.0;
              //  var s_ = s_ - sdc;
                $(i).find('input[name=s_bg]').val(s_);
            }
        }
    );
}

function s44_basket_q_change(event) {
    var t = event_target(event);
    s44_calc_offer_sum(t);
}

function s44_basket_q_plus_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var n = t.parentNode;
    var ne = $(n).find(".i2_q");
    var q = toNum(ne.val()) - 0;
    ne.val(q + 1);
    s44_calc_offer_sum(n);
}

function s44_basket_q_minus_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var n = t.parentNode;
    var ne = $(n).find(".i2_q");
    var q = 0 + toNum(ne.val());
    if (q > 1) { ne.val(q-1); }
    s44_calc_offer_sum(n);
}



function s44s_va_change(event) {
    preventDefault(event);
    var t = event_target(event);
    t = getParentNode('TR', t);
    var n_pc = $(t).find('.pc_va');
    var p = 0 + toNum();   
    var n = getParentNode('LI', t);
    var nx = $(n).find(".i2_q");
    var q = 0 + toNum(nx.val());
    var s = $(t).find('input[name=s_bg]').val();
    if (q != 0) p = s / q;
    n_pc.text(p);
    //alert(q+' '+ p);
}

    function btn44os_bg_put_click(event) {
        preventDefault(event);
        var t = event_target(event);
        t = getParentNode('TR', t);
        var n = getParentNode('LI', t);

        var bt = $("#i44bt").val();
        var ibt = $("#i44ibt").val();
        var st = $("#i44st").val();
        var sr = $("#i44sr").val();
        var dy = $("#i44dy").val();

        var o = $(n).find('.o').html();
        var nx = $(n).find(".i2_q");
        var q = 0 + toNum(nx.val());
        var tb = $(n).find(".lo_i2tb").val();

        var tb = $(n).find(".lo_i2tb").val();

        var s = $(t).find('input[name=s_bg]').val();
        var sjbg = $(t).find('input[name=sj_bg]').val();
        var tsjva = $(t).find('input[name=tsjva]').val();
        var p = 0 + toNum($(t).find('.pc_va').val());
        var va = $(t).find(".va").html();
        var tr = $(t).find(".tva_rate").html();

        $("#m3header").html('<div class="div45"></div>');

        DoMsg('', '');
        $(".btn44os_bg_put").hide();

        ajax_post("m/m44bt.php?ax=4404", {  tsjva:tsjva, tr:tr, s:s, q: q, o: o, bt: bt, tb: tb, p: p, sjbg: sjbg, st: st, osj:va, va:va ,dy:dy , ibt:ibt ,sr:sr}
                , function (data) {
                    DoMsg('', '<span id="item_was_put"> В корзину добавлено: ' + o + '</span>');
                    var rz = JSON.parse(data);
                    $("#i44ibt").val(rz['DS'][0]['ID_BASKET']);
                    $('.basketsum').html(rz['DS'][0]['SUM_BASKET']);
                    $(".btn44os_bg_put").show();
                });
    }


    function m44_build0(v, data) {
        $("#i3lci").html('****');
        var rz = json_parse(data);
        if (rz.ERR == undefined) {
            m44_build(v, rz);
        }   else { $("#i3lci").html(data); }
    }

    function m44_build(v, rz) {
        var s = "<ul>";
        for (var k = 0; k < rz.length; k++) {
            var ci = rz[k];
            s = s + '<li class="lo44">'
                  + '<a href="m/m22_edt_offer.php?ax=2201&px=1&o=' + ci["ID_OFFER"] + '" target="_blank"><span class="o">' + ci["ID_OFFER"] + '</span></a>'
                  + '<span class="title_offer">' + ci["TITLE_OFFER"] + '</span>'
                //  + str_iif((1.0 * ci["O_PCNT_DC"] != 0), 'ск-ка<span class="pdc">' + (1.0 * ci["O_PCNT_DC"]) + '</span>%', '')
        + '<br><span valign="top" style="white-space:nowrap;">'
           +'<span style="display:inline-block;">' + ci['HTM_IMGSRC'] + '</span>'
           +'<span style="white-space:nowrap;display:inline-block;">'
                  +'<span class="qnty_offer" ><input class="i2_q" type="text" value="1" size="2" /></span>'
                  +'<button class="btn2q_plus">+</button>'   + '<button class="btn2q_minus">-</button>'
              + '<br/>'
               + '<span class="text_offer">' + ci['TEXT_OFFER'] + '</span>'
               + '<br/><span class="lo_go">' + ci['CATALOG_OFFER'] + '</span>'
               + '<br/><input class="lo_i2tb" type="text" placeholder="комментарий" size="15" id="i2tb" />'
          + '</span>'
        + '</span>'

                   + ci['HTM_LSJ']
                  //  + '<a href="m/m49o.php?o=' + ci["ID_OFFER"] + '" target="m49">*</a>' 
                  + '</li>';
        }
        s = s + '</ul>';
        $("#i3lci").html(s);
        $(".btn2q_plus").click(s44_basket_q_plus_click);
        $(".btn2q_minus").click(s44_basket_q_minus_click);
        $(".lo44 .btn44os_bg_put").click(btn44os_bg_put_click);
        $(".lo44 .i2_q").change(s44_basket_q_change);
    }


    function m44bt_submit(event) {
        preventDefault(event);
        var v = $("#i44t").val();
        m44bt_get(v);
    }

    function m44bt_blur(event) {
        preventDefault(event);
        var t = event_target(event);
        var v = $(t).val();
        $("#i44t").val(v);
        m44bt_get(v);
    }

    function m44bt_co_click(event) {
        preventDefault(event);
        var t = event_target(event);
        var v = $(t).text();
        $("#i44t").val(v);
        m44bt_get(v);
    }


    function m44bt_loaded() {
        $(".co").click(m44bt_co_click);

        var ck = '';
        for (var k in co) {
            if (k != ck) { ck = k; break; }
        }
        $("#i44t").val(ck);
        m44bt_get(ck);
    }


