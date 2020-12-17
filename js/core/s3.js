function s3nk_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var osj = trim( $('input[name=i3btsj]').val() );
    if (osj == '') { osj = $("#i3osj").val(); }
    var href = $(t).attr('href') + '&sj=' + osj;
    window.open(href, 'm42', '', true);
}




function s3show_lsg(data){
    $("#m3center").html(data);
}

    function s3form_sg(event){
        preventDefault(event);
        var t = event_target(event);
        var href = $(t).attr('href');
        ajax_post(href,{},s3show_lsg);
    }
    function s3find_sg(event){
        preventDefault(event);
        var t = event_target(event);
        var sch = $(t).find('input[name=sch]').val();
        var href = $(t).attr('action');
        ajax_post(href,{sch:sch},s3show_lsg);
    }




    function s3st_select(xst)
    {
        var zsn = $("#i3sn").val();
        window.location = location.protocol + '//' + location.host + location.pathname + '?st=' + xst + '&sn=' + zsn;
    }


    function btn3bt_v_click(event) {
        preventDefault(event);
        var t = event_target(event);
        var n = getParentNode('LI', t);
        var zhref = t.attributes['href'].value;
        ajax_post(zhref, {}, function (data) { $(n).hide(); } );
    }

    function s3sg_click(event)
    {
        preventDefault(event);
        ClearMsg();
        var n = event_target(event);
        $("#m3footer").load(n.attributes['href'].value);
    }

    function s3menu_click(event)
    {
        preventDefault(event);
        ClearMsg();
        var n = event_target(event);
        var t = n.parentNode;
        if (n.tagName != 'A') { n = n.parentNode; }
        if (n.tagName == 'A') {

            if (n.parentNode.tagName == 'LI') {
                t = getParentNode('UL', n);
                if (t == null) {t = n;}
                $(t).hide();
            }
            s3menu_exec(n.attributes['href'].value, function () { $(t).show(); });
        } else { alert('tag is not A'); }
        //   
    }

    function s3st_click(event) {
        var zhref='';
        var zst = $("#s3st").val();
        var zsn = $("#i3sn").val();
        var zh = location.pathname;
        if (zh.indexOf('main') > 0) { zh = location.origin + '/main/'; } else { zh = location.origin + '/';}
        if (zst == 'kotabl.ru') {  zhref = zh+ "kotabl/home.php?sn=" + zsn;  }
        location.assign(zhref);
    }


    
    function s3menu_href(event) {
        preventDefault(event);
        var n = event_target(event);
        s3menu_exec(n.href, null, {});
    }


    function s3menu_action(event) {
        preventDefault(event);
        var n = event_target(event);
        var a = n.action;
        var p = {};
        for (var i = 0; i < n.elements.length; i++) {
            var ch = n.elements[i];
            if (ch.tagName == 'INPUT' && ch.name != '') {
                p[ch.name] = ch.value;
            }
        }
        s3menu_exec(a, null, p);
    }

    function s3menu_exec(xhref, xf, xp ) {
        // xf - callback function 
        $(".div45").hide();

        var zpb = $("#i0bp").val();
        var zpe = $("#i0ep").val();
        var zst = $("#i3st").val();
        var zab = $("#i3ab").val();
        var zsn = $("#i3sn").val();
        var zdy = $("#i3osj").val();

        var sr = $("input[name=i3btsj]").val();

        var btsjchk = $("#i3btsjchk").prop('checked');

        if (btsjchk) { zdy = null; }

        var zhref = xhref + '&pb=' + zpb + '&pe=' + zpe;

        if (xhref.indexOf('&osj=') == -1) { zhref = zhref + '&osj=' + zdy; }
        if (xhref.indexOf('&st=') == -1) { zhref = zhref + '&st=' + zst; }
        if (xhref.indexOf('&sn=') == -1) { zhref = zhref + '&sn=' + zsn; }
        if (xhref.indexOf('&ab=') == -1) { zhref = zhref + '&ab=' + zab; }
        if (xhref.indexOf('&sr=') == -1) { zhref = zhref + '&sr=' + sr; }

        if (zhref.indexOf('m34_') > -1) {
            var z34aa = $("#i34aa").val();
            var z34go = $("#i34go").val();
            zhref = zhref + '&aa=' + z34aa + '&go=' + z34go;
        }

        if (zhref.indexOf('m38_') > -1) {
            var z38go = $("#i38go").prop('checked');
            if (z38go) { z38go = 'GO'; } else { z38go = ''; };
            var m = '&m=' + z38go;
            var z38o = $("#i38obj").val();
            if (z38o == undefined || z38o == null) { z38o = ''; }
            zhref = zhref + "&o=" + z38o + m;
        }
        ajax_post(zhref, xp , function (data) { $("#m3center").html(data); if (xf) { xf(); } });
    }




    function s3btsj_change(event) {
        preventDefault(event);
        var t = event_target(event);
        s3btsj_change_t(t);
    }

    function s3btsj_change_t(t) {
        var sr = $(t).val();
        if (sr != '') {
            var st = $("#i3st").val();
            ajax_post('m/m3bt.php?ax=387', { sr: sr, st: st }
                , function (data) {
                    var rz = json_parse(data);
                    if (rz['ROW_COUNT'] == 0) { DoMsg('err', 'Субъект учёта не найден для данного сайта'); }
                    else {
                        DoMsg('', '');
                        s3menu_exec('m/m3bt.php?ax=300&bt=0&sr=' + sr);
                    }
                });
        } else { DoMsg('', ''); s3menu_exec('m/m3bt.php?m=300&bt=0&'); }
    }


    function s3_turns( xopt ) {
        $("#m3center").html('<div id="div7body"></div>');
        var zpb = $("#i0bp").val();
        var zpe = $("#i0ep").val();
        s19_load2div("#div7body", zpe, '', zpb, zpe, xopt, null, null); // 'vovb'
        return;
    }




    function Login_click(event)
    {
        $("#divlogin").show();
        $("#menu1").show();
        $("#menu2").show();
        $("#body3").hide();
        $("#menu4").show();
    }
				

    function m3_Logn(event, xpas) {
        preventDefault(event);
        s3_Login(xpas);
    }


    function btn3_Login_submit(event)
    {
        var f =  $("#form3_Login");  
        var xpas = f.find('input[id="xpas"]').val();  f.find('input[id="xpas"]').val("");
        m3_Logn(event, xpas);
    }

						
    function m3_xcontact_submit(event)
    {
        preventDefault(event);
        $("#a0void").focus();
    }




    function s3_shortword_click(event) {
        preventDefault(event);
        var o = event_target(event);
        var sw = $(o).find(".go").eq(0);
        var zshortword = sw.context.innerText;
        $("#cmd_filter").html(zshortword);
        s3_cmd_post(zshortword);
        $(o).addClass("shortword_current");
        var btn = $("#btn0add_offer");
        btn.attr("gr_offer", zshortword);
        btn.html(" NEW OFFER (" + zshortword + ")");
    }



    function s3_cmd_post(xcmd) {
        xcmd = trim(xcmd);
        s8body_hide(null);
        if (xcmd == undefined || xcmd == null) { xcmd = ''; }

        $("#btn0add_offer").hide();
        $("#b0sy").hide();
        $(".shortword_current").removeClass("shortword_current");
        $("#xcmd").val(xcmd);

        if (xcmd == '') { $("#shortwordmsg").show(); } else { $("#shortwordmsg").hide(); }



        v_state.filter = xcmd;
        var dy = $("#i44dy").val();
        var bt = $("#i44bt").val();


        var o1 = $("#chk0_vtbl").prop('checked');
        var o2 = $("#chk0_vimg").prop('checked');
        var z_vwtbl = ''; if (o1) { z_vwtbl = 'checked'; }
        var z_vwimg = ''; if (o2) { z_vwimg = 'checked'; }
        var chk0aab = $("#chk0aab").prop('checked');

        $("#m2body").html(s0_msg_waiting());
        ajax_post("kotabl/m3_offerlist.php", { xfilter: v_state.filter, xvwtbl: z_vwtbl, xvwimg: z_vwimg, dy: dy, chk0aab: chk0aab, bt: bt },
                function (data) {
                    var rz = json_parse(data);
                    s0_cmd_body2_show(rz, xcmd);

                    if (rz['BOOKED'] == 1) {

                        ajax_post("m/p_basket.php?a=18", { bt: bt },
                                        function (data) {
                                            var rz = json_parse(data);
                                            $("#bts_" + bt).html(rz.SUM_BOOKING);
                                        });
                        $("#xcmd").val('');
                        $("#xcmd").focus();
                    }
                    m2offers_set();
                }
            );
    }





    function s3_basket_put_click(event, xid_offer, xchgroffer) {
        preventDefault(event);
        if (xchgroffer == '') { xchgroffer = null; }
        var btn = event_target(event);
        var n = btn.parentNode.parentNode;

        var np = $(n.parentNode).find("#i2price");
        var p = 0;
        if (np != null && np != undefined) { p = toNum(np.val()); }
        var tb = $(n.parentNode).find("#i2tb").val();
        var sj = $(n.parentNode).find("#i2sj").val();
        var q = toNum($(n).find("#i2_q").val());

        $(btn).hide();

        var o = document.getElementById("otr" + xid_offer);
        var o1 = $(o.parentNode).clone(false);
        o1.find("tr").removeAttr("id");
        o1.find("button").remove();
        o1.find(".offer_submenu").remove();

        var t = o1.html();

        var dy = $("#i44dy").val();
        var bt = $("#i44bt").val();

        DoMsg('', '');
        ajax_post("m/p_basket.php?a=5401", { dy: dy, q: q, o: xid_offer, bt: bt, tb: tb, p: p, sj: sj }
                , function (data) {
                    ajax_post("m/p_basket.php?a=18", { bt: bt },
                            function (data) {
                                var rz = json_parse(data);
                                var bt = $("#i44bt").val();
                                var dy = $("#i44dy").val();
                                $("#bts_" ).html(rz.SUM_BOOKING);
                                DoMsg('', '<div id="item_was_put"> В корзину добавлено: <table>' + t + '</table></div>');
                                $(btn).show();
                                // if (xchgroffer != null) { m0_cmd_post(xchgroffer); }
                                // s0_get_subcmdshortwords();
                            });
                });
    }




function s3_xcontact_change(event) {
    preventDefault(event);
    var v = trim($("#xcontact").val());
    if (v != '') {
        ajax_post("m/p_login.php?ax=3001", { xcontact: v },
            function (data) {
                var rz = json_parse(data);
                v_state.user = rz.LG_USER;
                $("#xcontact").val(rz.CONTACT);
                if (rz.LG_USER == null) {
                    // make new user
                    v_state.user = '';
                    $("#f00waitpwd").hide();
                    $("#btn00newuser").show();
                }
                else {
                    $("#btn00newuser").hide();
                    if (rz.PAS == '') {
                        $("#login_user").html('<INPUT TYPE="hidden" id="xuser" SIZE="30" value="' + rz.LG_USER + '" />' + rz.LG_USER);
                        $("#newuser").hide();
                        m3_Logn(event, rz.PAS);
                    }
                    else {
                        $("#current_user").html('');
                        $("#f00waitpwd").show();
                        $("#xpas").focus();
                        $("#login_user").html('<INPUT TYPE="hidden" id="xuser" SIZE="30" value="' + v_state.user + '" />' + rz.LG_USER);
                        $("#newuser").hide();
                    }
                }
                $("#login_err").html(rz.R);
            }
        );
    } else { $("#f00waitpwd").hide(); $("#btn00newuser").hide(); }
}



function btn3_LoginWoPWD_submit(event) {
    var xcontact = $("#xcontact").val();
    if (xcontact == null || xcontact.length < 3) { $("#login_err").html("Требуется ввести контактную информацию или псевдоним"); }
    else {
        var btn = event_target(event);
        $(btn).hide();
        ajax_post("m/p_login.php?ax=3005", { xuser: '', xpas: '', xcontact: xcontact },
               function (data) {
                   $(btn).show();
                   var rz = json_parse(data);
                   if (rz.R.indexOf("ERR", 0) != -1) { $("#login_err").html(rz.R); }
                   else {
                       $("#xuser").val(rz.LG_USER);
                       s3_Login('')
                   }
               });
    }
}

