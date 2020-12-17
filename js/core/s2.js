var m2offers = [];

function s2_offers_loaded()
{
    var m2o = $("#m2offers");
    for (var i = 0; i < m2offers.length; i++) {
        var zo = m2offer(m2offers[i]);
        m2o.append(zo);
    }
}

function s2_offers_arrange() {
    var c = !$("#chk0_vtbl").prop("checked");
    $("#m2offers").toggleClass("ll", c);
}

function s2_offers2csv() {
    var aa = [['Артикул','Цена','Количество','Наименование']];
    for (var i = 0; i < m2offers.length; i++) {
        var zo = m2offers[i];
        var a1 = [zo['ID_OFFER'], zo['PRICE_OFFER'], '', zo['TITLE_OFFER'] ];
        aa.push(a1);
    }
    fs_SaveToFileCSV('',aa);
}

function s2_basket_q_plus_click(event) {
    preventDefault(event);
    var n = event_target(event).parentNode.parentNode;
    var ne = $(n).find("#i2_q");
    var q = toNum(ne.val())-0;
    ne.val(q+1);
}

function s2_basket_q_minus_click(event) {
    preventDefault(event);
    var n = event_target(event).parentNode.parentNode;
    var ne = $(n).find("#i2_q");
    var q = 0+toNum(ne.val());
    if (q > 1) { ne.val(q - 1); }
}

function s2_basket_put_click(event, xid_offer, xchgroffer)
{
    preventDefault(event);
    if (xchgroffer == '') {xchgroffer = null;}
    var btn = event_target(event);
    var n = btn.parentNode.parentNode;

    var np = $(n.parentNode).find("#i2price");
    var p = 0;
    if (np != null && np != undefined) { p = toNum(np.val()); } 
    var tb = $(n.parentNode).find("#i2tb").val();
    var sj = $(n.parentNode).find("#i2sj").val();
    var q = toNum( $(n).find("#i2_q").val() );

    $(btn).hide();

    var o = document.getElementById("otr" + xid_offer);
    var o1 = $(o.parentNode).clone(false); 
    o1.find("tr").removeAttr("id");
    o1.find("button").remove();
    o1.find(".offer_submenu").remove();

    var t = o1.html(); 
    var btt = $("#baskets .td_selected");
    var  basket = btt.attr("bt");
    var XLG_DELIVERY = btt.attr("dy");
    if (XLG_DELIVERY == undefined || XLG_DELIVERY == '')
    {
        XLG_DELIVERY = $("#select_delivery").val();
    }

    DoMsg('', '');
    ajax_post("m/p_basket.php?a=1", { dy: XLG_DELIVERY, q: q, o:xid_offer, bt: basket, tb:tb, p:p, sj:sj }
            , function (data) {
                    ajax_post("m/p_basket.php?a=18", { bt: basket },
                            function (data) {
                                var rz = json_parse(data);
                                $("#bts_" + basket).html(rz.SUM_BOOKING);
                                btt.html(basket +' '+XLG_DELIVERY);
                                $("#select_delivery").attr('disabled', 'disabled');
                                DoMsg('', '<div id="item_was_put"> В корзину добавлено: <table>' + t + '</table></div>');
                                $(btn).show();
                                if (xchgroffer != null)
                                {
                                    m0_cmd_post(xchgroffer);
                                }
                                s0_get_subcmdshortwords();
                            });
            });
}

function m2gobasket(xbasket)
{
    ClearMsg();
    s0_menu(8, "bt=" + xbasket);
}



function m2basket_click(event) {
    preventDefault(event);

    s8body_hide(event);

    var o = event_target(event);  
    var bt = $(o).attr("bt");
    var dy = $(o).attr("dy");
    if (dy == '' || dy == undefined) { dy = getCookie('dvry'); }
    if (dy == "undefined" ) { dy = ''; }
   // alert(bt + ' ' + dy);
    ClearMsg();
    //$("#select_delivery").val(dy);
    m0delivery_set('', dy, 1);
    
    $("#baskets td").toggleClass("td_selected", false);
    $("#current_basket").html(bt);
    $(o).toggleClass("td_selected", true);
    s0_get_subcmdshortwords();

    var zdisabled = 0;
    if ($(o).hasClass("c0basketfull")) { zdisabled = 1; }
    if (zdisabled == 1) {
        $("#select_delivery").attr('disabled', 'disabled');
    }
    else {
        $("#select_delivery").removeAttr('disabled');
    }
}
   
function s2vw_offer_click(event)
{
    var n = $(event_target(event).parentNode.parentNode);
    var o = n.attr("data-id");
    ajax_post('m22_vw_offer.php', { o: o}, function (data) { $("#m2body").html(data); });
}

function m2edit_offer_click(xid_offer, xgr_offer)
{
    var dy = $("#select_delivery").val();
    var xcmd = $("#xcmd").val();
    var zcmd = CMD[dy + '/' + xcmd]; // clear cache
    CMD[dy + '/' + xcmd] = undefined;

    window.open('m22_edt_offer.php?a=1&px=1&o=' + xid_offer+'&go='+xgr_offer, 'o' + xid_offer, true);
    /*
   ajax_post('m22_edt_offer.php?a=1&px=0'
        , { o: xid_offer, g: xgr_offer }
        , function (data) { $("#m2body").html(data); }
        );
        */
}

function stock_qty_click(article_entry)
{
   ajax_post('p_entity.php?a=1', { ae: article_entry }, function (data) { $("#m2body").html(data);  });
}

function stock_qty_submit(event) {
    preventDefault(event);
    var q = $("#p2_QTY_ENTITY").val();
    var a = $("#p2_ARTICLE_ENTITY").val();
    ajax_post('p_entity.php?a=2', { ae: a, q: q }, function (data) { chk0_vtbl_click(null); });
}
