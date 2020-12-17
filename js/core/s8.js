function s8bt_mouseover_content(xbt) {
    preventDefault(event);

    var bt = $("#i8bt").val();
    if (bt != xbt) {
        ajax_post("m/m8_bt.php", { bt: xbt }, function (data) {
            $("#m0m8lbg").html(data);
            $("#m0m8lbg").show();
        });
    }
}

function s8body_hide(event) {
    $("#m0m8lbg").empty();
}




    function chk8showdeleted_click(event)
    {
        var chk8deleted = $("#chk8deleted").attr("checked");
        var chk = event_target(event).checked;
        s0_menu(8, "&chkd=" + chk);
    }

    function chk8_VV(event)
    {
        var v = event_target(event).checked;
        $(".chk6v").each(function ()
        {
            var id = this.getAttribute("data-booking");
            chk8_clickVB(id, v);
            this.checked = v;
        }
        );
    }

    function chk8_clickVB(xid_booking, xchecked) {

        ajax_post("m/p8_booking.php?a=21", { xid_booking: xid_booking, xchecked: xchecked },
                    function (data) { }
               );
    }

    function chk8_click(xid_booking, xchecked)
    {
        var XF_DELIVERY = $("#select8_delivery").val();
        var bt = $("#i8bt").val();

        $("#basketsum").html(s0_msg_waiting());
        ajax_post("m/p_basket.php?a=2", { bt:bt, XF_DELIVERY:XF_DELIVERY, xid_booking: xid_booking, xchecked: xchecked },
                    function (data)
                    {
                        data = trim(data);
                        $("#basketsum").html(data);

                        $("#bts_" + bt).html(data);

                        if (data == '') { $("#formOrder").hide(); } else { $("#formOrder").show(); }
                    }
               );
    }

    function chg8_text_booking(xid_booking, xtext) {
        ajax_post("m/p_basket.php?a=3", { xid_booking: xid_booking, xtext: xtext }, function (data) {} );
    }

    function chg8_text_comment(event)
    {
        var zbuyer = $("#i8_buyer").val();
        var f = $("#formOrder");
        var zumsg = f.find('textarea[id="i8msg"]').val();
        var zbasket = $("#i8basket").val();
        var ta = $('#i8ta').val();
        var ia = $('input[name = "rb8addr"]:checked').val();
        if (ia == undefined) { ia = 0; }
        ajax_post('m/p_basket.php?a=816',{bt:zbasket, ia:ia, ta:ta, byr:zbuyer, cmt:zumsg},function(data){})
    }

    function btn8byr_upd(event)
    {
        preventDefault(event);
        var zcontact = $("#i8_byr_contact").val();
        var zaddress = $("#i8_byr_address").val();
        var zbuyer = $("#i8_buyer").val();
        var zau = $("#i8au").val();
        ajax_post("m/p_basket.php?a=15", { byr: zbuyer, au: zau, cnct: zcontact, adr: zaddress, o:null, di:null }
            , function (data) { i8byr_submit(event); });
    }

    function i8sj_set()
    {
        var zbuyer = $("#i8_buyer").val();
        var zau = $("#i8au").val();
        var bs = trim($("#basketsum").html());
        ajax_post("m/p_basket.php?a=814", { sj: zbuyer, byr: zbuyer, au: zau, bs: bs }, function (data) {
            var rz = json_parse(data);

            if (rz.ERR != '') {
                DoMsg('err',rz.ERR);
            } else {
                msg0_setclass("#i8discount", '')
                $("#div8aa").html(rz.AA);
                if (rz.AA == '') {
                    $("#rb8ta").prop('checked', true);
                }
                $("#i8_buyer").val(rz.LG_SUBJECT);
                $("#i8discount").html(rz.LG_DISCOUNT + ' = ' + rz.PERCENT_DISCOUNT + '%, ' + rz.SUM_DISCOUNT);

                //var ia = $("#i8ia").val();         $('input[value="'+ia+'"]').prop(true);

                //if (rz.SALDO_SUBJECT == null) { rz.SALDO_SUBJECT = ''; }
                $("#i8_byr_saldo").val(rz.SALDO_SUBJECT);
                if (rz.R.indexOf('OK') >= 0) {
                    $("#i8_buyer").toggleClass("ok", true);
                    if (rz.R.indexOf('DEFAULT') > 0) { $("#i8discount").toggleClass("wrn", true); } else { $("#i8discount").toggleClass("ok", true); }
                } else {
                    $("#i8_buyer").toggleClass("ok", false);
                }
                $("#m8byr_edit").show();
                $("#i8msg").focus();
            }
        });
    }

    function s8sj_blur(event) {
        preventDefault(event);
        var z = event_target(event);
        var x = $(z).val();
        var y = x.match(/(\w+)/ig);
        if (y != null) {
            if (x != y[0]) { x = y[0]; $(z).val(x); }

            y = x.match(/(^14)/ig);
            if (y == null) {
                $(z).toggleClass('red', false);
            } else {
                y = x.match(/^14(\d{5}$)/ig);
                $(z).toggleClass('red', (y == null));
            }
        }
    }

    function i8byr_submit(event)
    {
        preventDefault(event);
        chg8_text_comment(event);
        i8sj_set();
    }


    function form8_order_submit_by_mngr(event)
    {
        form8_order_submit(event, 'MNGR',0)
    }

    function form8_order_submit_by_user(event)
    {
        form8_order_submit(event, 'USR', 0);
    }

    function f8contact_submit(event) {
        preventDefault(event);
        var c = $("#i8cct").val();
        if (!ContainingPhoneNumber(c)) { c = ''; }
        if (c != '') {
            ajax_post("m/p9_user.php?ax=925", { c: c }
                , function (data) { DoMsg('', ''); });
        } else { $("#i8cct").val(''); }
    }

    function ContainingPhoneNumber(xs) {
        //    new RegExp("pattern"[, флаги])   http://javascript.ru/basic/regular-expression#obekt-regexp   http://www.regextester.com/
        xs = trim(xs.replace(' ', ''));
        var numbers = /(\+\d+?\(\d{3}\))?([\s*-])?\d{3}?([\s*-])?\d{2}?([\s*-])\d{2}/; // +7(000)111-22-22
        if (xs.match(numbers)) { return true; }
        else {
            numbers = /([\s*-])?\d{3}?([\s*-])?\d{2}?([\s*-])\d{2}/; // 111-22-22
            if (xs.match(numbers)) { return true; }
            else {
                numbers = /\d{7}/; // 1112222
                if (xs.match(numbers)) { return true; }
            }
        }
        return false;
    }

    function form8_order_submit(event, xt_user, xdomakebill) {
        preventDefault(event);

        if (v_state.user == '') {
            DoMsg("err", "Требуется ввести контактные данные");
            $("#xcontact").focus();
            $("#lcontact").addClass("lc_req");
        }
        else {
            ajax_post("m/p9_user.php?ax=924", {}
               , function (data) {
                   var lc = '';
                   var r = json_parse(data);
                   if (r.ROW_COUNT < 1) { DoMsg("err", "ошибка получения контактных данных"); return; }
                   else
                   {
                       lc = r.DS[0]['LIST_CONTACT'];
                       if (!ContainingPhoneNumber(lc)) { lc = ''; }
                   }

                   if (lc.length < 5) {
                       DoMsg("err", 'Требуется указать телефон для связи в формате +7(000)111-22-22 '
                                  + '<br/><form id="f8contact" onsubmit="f8contact_submit(event)" >Номер телефона<input type="text" id="i8cct" value="" /><input type="submit" value="сохранить"/></form>');
                       $("#f8contact").show();
                       $("#f8contact").focus();
                       return;
                   }

                   var f = $("#formOrder");
                   var XF_DELIVERY = $("#select8_delivery").val();
                   var xumsg = f.find('textarea[id="i8msg"]').val();
                   var xid_invoice = $("#select8_inv").val();

                   if (xid_invoice == undefined) { xid_invoice = 0; }

                   var XLG_BUYER = $("#i8_buyer").val();
                   var ta = $('#i8ta').val();
                   var nv = $('#i8n').val();
                   var ia = $('input[name = "rb8addr"]:checked').val();
                   if (ia == undefined) { ia = 0; }

                   var cred = $('#i8credit').val();
                   var bt = $("#i8bt").val();

                   ajax_post("m/p8_booking.php?a=804", { bt:bt,  dy: XF_DELIVERY, tc: xumsg, xt_user: xt_user, i: xid_invoice, sj: XLG_BUYER, b: xdomakebill, ia: ia, ta: ta, c: cred, nv: nv },
                           function (data) {
                               var rz = json_parse(data);
                               if (rz.ERR.indexOf('ERR') > -1)
                               { DoMsg("err", rz.ERR); }
                               else {
                                   if (rz.DS[0].ID_INVOICE > 0) {
                                       if (xdomakebill == 1) {
                                           $("#body3").html("Счет " + rz.DS[0].ID_INVOICE + " выставлен!");
                                           btn15_bill_click2(rz.DS[0].ID_INVOICE, XLG_BUYER, cred, v_state.sn);
                                       }
                                       else {
                                           if (xt_user == 'MNGR') {
                                               s0_menu(2, '');
                                               btn15_basket_print(XF_DELIVERY, rz.DS[0].ID_INVOICE, 0, v_state.sn);
                                           } else {
                                               s0_menu(2, '');
                                               DoMsg('ok', 'Заказ размещён, ожидайте звонка оператора в течение 15 минут или позвоните сами для подтверждения заказа.');
                                           }
                                       }
                                   } else {
                                       DoMsg("err", rz.ERR);
                                   }

                               }
                           }
                          );
               });
        }
    }

    function btn8bill_click(event) // print order
    {
        form8_order_submit(event, 'MNGR', 1);
    }

    function div6buyer_show()
    {
        $("#div6buyer").show();
    }

    function btn8_order_accept_click(xid_invoice)
    {
        ajax_post("m/p8_booking.php?a=6", { xid_invoice: xid_invoice },
                    function (data)
                    {
                        s0_menu(5, '');
                    }
               );
    }

    function chk8_click5( xid_booking ,xchecked) 
    {
        $("#s6_acceptorder").show();
        $("#basketsum").html(s0_msg_waiting());
        ajax_post("m/p8_booking.php?a=5", { xid_booking: xid_booking, xchecked: xchecked },
                    function(data) 
                    {
                        if (data.indexOf("ERR",0) == -1) $("#basketsum").html(data);   
                    }
               );
    }
			
    function btn8_click7(xid_booking, xlg_status) 
    {
        ajax_post("m/p8_booking.php?a=7", { xid_booking: xid_booking, xlg_status: xlg_status },
                    function(data) 
                    {
                        var btn="#btn";
                        if (xlg_status == 1004) btn=btn+"4_"+xid_booking;
                        if (xlg_status == 1005) btn=btn+"5_"+xid_booking;
                        $(btn).hide();
                    }
               );
    }	
