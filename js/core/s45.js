
function s45chgstate_bg(event) {
    preventDefault(event);
    var chk45 = [];
    $("input:checkbox[name=chk45]:checked").each(function () { chk45.push($(this).val()); });
    var t = event_target(event);
    var href = $(t).attr('href');
    var ibt = $(t).attr('#i44ibt');
    ajax_post(href, { lbg: chk45 , ibt:ibt }
         , function (data) {
             $('input:checkbox[name*=chk45]').prop('checked', false);
             s44bt_mouseover_content(event);
         }
        );
}
function s63chgstate_bg(event) {
    preventDefault(event);
    var chk45 = [];
    $("input:checkbox[name=chk45]:checked").each(function () { chk45.push($(this).val()); });
    var t = event_target(event);
    var href = $(t).attr('href');
    ajax_post(href, { lbg: chk45 }
         , function (data) {
             $('input:checkbox[name*=chk45]').prop('checked', false);
             s3menu_exec('m/m63bglist.php?ax=6300');
         }
        );
}



function s45days_credit_change(event) {
    var t = event_target(event);
    var v = $(t).val();
    var ibt = $('#i44ibt').val();
    ajax_post('m/m45bt.php?ax=4551', { ibt:ibt, v: v }, null);
}


function s45_order_submit_by_user(event) {
    s45_order_submit(event, 'USR', 0);
}

function s45_order_submit_by_mngr(event) {
    s45_order_submit(event, 'MNGR', 0)
}

function s45_bill_click(event) // print order
{
    s45_order_submit(event, 'MNGR', 1);
}


function s45_order_submit(event, xt_user,xdomakebill) {
    preventDefault(event);

    /*
    if (v_state.user == '') {
        DoMsg("err", "Требуется ввести контактные данные");
        alert('требуется авторизация');
    }
    else {
    */
        s45_order_send(xdomakebill);
        s45body_hide(event);

        /*

        не актуально, поскольку не для каждого заказа должен быть отдельный пользователь

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
                   DoMsg("err", 'Требуется указать телефон для связи в формате +7(000)111-22-33 '
                              + '<br/><form id="f8contact" onsubmit="f8contact_submit(event)" >Номер телефона<input type="text" id="i8cct" value="" /><input type="submit" value="сохранить"/></form>');
                   $("#f8contact").show();
                   $("#f8contact").focus();
                   return;
               } else {
                   s45_order_send(xdomakebill);
               }
           });
           */
    //}
}

function s45body_hide(event) {
    preventDefault(event);

    var p = $('.div45').parent();
    $(p).html('<div class="div45"></div>');
    //$("#m3header").empty();
}

function s45_order_send(xdomakebill) {
   
    var sj = $('#i44sj').val();
    if (sj == '') {
        DoMsg("err", "Требуется выбрать субъекта");
        return;
    }

    var cred = 0; 
    var bt = $('#i44bt').val();
    var ibt = $('#i44ibt').val();
    var btsn = $('#i44btsn').val();
    var nv = $('#i45nv').val();

    var chk45 = [];
    if (xdomakebill == 1) {
        $('input:checkbox[name=chk45]').prop('checked', true);
        $("input:checkbox[name=chk45]:checked").each(function () { chk45.push($(this).val()); });
    }


    $("#m3header").html('<div class="div45"></div>');
    ajax_post("m/m45bt.php?ax=4504", { ibt:ibt, bt: bt, btsn:btsn,  b: xdomakebill, c: cred, nv: nv ,lbg:chk45 },
            function (data) {
                var rz = json_parse(data);
                if (rz.ERR.indexOf('ERR') > -1)  { DoMsg("err", rz.ERR); }
                else {
                    if (rz.DS[0].ID_INVOICE > 0) {
                        if (xdomakebill == 1) {
                            $("#m3center").html("Счет " + rz.DS[0].ID_INVOICE + " выставлен!");
                          //  btn45_bill_print(rz.DS[0].ID_INVOICE, v_state.sn);
                            s3menu_exec("m/m3bt.php?ax=300&bt=0");
                        }
                        else {
                            if (xt_user == 'MNGR') {
                         //       btn45_bt_print(ibt,  v_state.sn);
                            } else {
                                $("#m3center").html('Заказ размещён, ожидайте звонка оператора в течение 15 минут или позвоните сами для подтверждения заказа.');
                            }
                        }
                    } else {
                        DoMsg("err", rz.ERR);
                    }
                }
            }
           );
}

function btn45_bt91_print(ibt, xfullorder, xsn) {
    $("#b6prn_b").hide();
    var windowAttr = "location=no,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no,width=80,height=150";
    var window_z = window.open('m/m45bt_print.php?ss=91&ibt=' + ibt + '&on=' + xfullorder + '&sn=' + xsn, '_blank', windowAttr);
    $("#b6prn_b").show();
}

function btn45_bt_print(ibt, xfullorder, xsn) {
    $("#b6prn_b").hide();
    var windowAttr = "location=no,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no,width=80,height=150";
    var window_z = window.open('m/m45bt_print.php?z&ibt=' + ibt + '&on=' + xfullorder + '&sn=' + xsn, '_blank', windowAttr);
    $("#b6prn_b").show();
}

function btn45_bill_print(xid_invoice, xsn) {
    $("#b6prn_i").hide();
    var windowAttr = "location=no,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no,width=40,height=50";
    var window_bill = window.open('m/m45_bill_print.php?z&ax=4511&i=' + xid_invoice + '&sn=' + xsn, '_blank', windowAttr);
}


function chk45_click(event) {
    var n = event_target(event);
    var chk = $(n).prop('checked');
    n = n.parentNode.parentNode;
    var bg = $(n).find(".bg").html();

    $(".basketsum").html(s0_msg_waiting());
    ajax_post("m/m44bt.php?ax=4405", { bg: bg, chk: chk },
                function (data) {
                    var rz = JSON.parse(data);
                    $('.basketsum').html(rz['DS'][0]['SUM_BASKET']);
                }
           );
}

function chg45_text_booking(event) {
    var n = event_target(event);
    var v = $(n).val();
    n = n.parentNode.parentNode;
    var bg = $(n).find(".bg").html();
    ajax_post("m/m45bt.php?ax=4553", { xid_booking: bg, xtext: v }, function (data) { });
}

function s45qbg_change(event) {

    var t = event_target(event);
    var q = $(t).val();
    var n = getParentNode('TR', t);
    s_ = $(n).find('input[name=SUM_BOOKING]').val();

    var bg = $(n).find('input[name=chk45]').val();
    ajax_post("m/m45bt.php?ax=4512", { bg: bg, qbg: q, sbg: s_ }
        , function (data) {
            var rz = json_parse(data);
            $(n).find('input[name=QNTY_BOOKING]').val((1.0 * rz['DS'][0]['QNTY_BOOKING']));
            $(n).find('input[name=SUM_BOOKING]').val((1.0 * rz['DS'][0]['SUM_BOOKING']));
            $('.basketsum').html(rz['SUM_BT']);
        });
}



/*



    function f8contact_submit(event) {
        preventDefault(event);
        var c = $("#i8cct").val();
        if (!ContainingPhoneNumber(c)) { c = ''; }
        if (c != '') {
            ajax_post("p9_user.php?ax=925", { c: c }
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



*/
