function s22stopYesNo_osj(event)
{
    preventDefault(event);
    var t = event_target(event);
    var a = getParentNode('A', t);
    var href = $(a).attr('href');
    t = getParentNode('TR', t);
    var st = $(t).find("td.st").html();
    var sj = $(t).find("td.sj").html();
    ajax_post(href, { st: st, sj: sj }, function (data) {
        location.reload();
    //    $(t).hide();
    });
}





/*
function s22delete_osj(event) {
    preventDefault(event);
    var t = event_target(event);
    var a = getParentNode('A', t);
    var href = $(a).attr('href');
    t = getParentNode('TR', t);
    var st = $(t).find("td.st").html();
    var sj = $(t).find("td.sj").html();
    ajax_post(href, { st: st, sj: sj }, function (data) { $(t).hide(); });
}
*/

/*
function btn22os_bg_put_click(event) {
    preventDefault(event);
    var t = event_target(event);
    t = getParentNode('TR', t);
    var n = getParentNode('LI', t);

    var bt = $("#i44bt").val();
    var ibt = $("#i44ibt").val();
    var btsn = $("#i44btsn").val();
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
    var p = 0 + toNum($(t).find('.pc_osj').val());
    var osj = $(t).find(".osj").html();
    var tr = $(t).find(".tva_rate").html();


    DoMsg('', '');
    $(".btn44os_bg_put").hide();

    ajax_post("m/m44bt.php?ax=4404", { tr: tr, s: s, q: q, o: o, bt: bt, tb: tb, p: p, sjbg: sjbg, btsn: btsn, st: st, osj: osj, dy: dy, ibt: ibt, sr: sr }
            , function (data) {
                DoMsg('', '<span id="item_was_put"> В корзину добавлено: ' + o + '</span>');
                var rz = JSON.parse(data);
                $("#i44ibt").val(rz['DS'][0]['ID_BASKET']);
                $('.basketsum').html(rz['DS'][0]['SUM_BASKET']);
                $(".btn44os_bg_put").show();
            });
}

*/






function s22_del_offer(xid_offer) {
    ajax_post("m/p22_edt_offer.php?a=4", { o: xid_offer }
            , function (data) {
                $("#btn22sbmt").hide();
                $("#f22outlay").hide();
                DoMsg('green', 'предложение удалено');
            }
        );
}


function s22_edt_qnty_article(o, art, q) {
    q = toNum(q);
    ajax_post(
        "m/p22_edt_offer.php?a=2206", { o: o, art: art, q: q, sgn:0 }
        , function (data) {}
        );
}

function s22_offer_clone(xo) {
    ajax_post(
        "m/p22_edt_offer.php?a=2214", { o: xo }
        , function (data) {
            var rz = json_parse(data);
            if (rz.R.indexOf('ERR') > -1) {
                DoMsg('err', rz.ERR);
            } else {
                var o = rz.DS[0].ID_OFFER;
                window.open("../m22_edt_offer.php?ax=1&px=1&o=" + o, "m22o" + o, "", true);
            }
        }

        );
}




function s22_outlay_submit(event) {
    preventDefault(event);
    var zid_offer = $("#i22offer").val();
    var art = $("#i22art").val(); // id of art
    var q = toNum($("#i22q").val());
    var kp = toNum($("#i22kp").val());
    ajax_post(
        "m/p22_edt_offer.php?a=2206", { o: zid_offer, art: art, q: q , kp:kp}
        , function (data) {
            ajax_post("m/m22_edt_offer.php?a=2", { o: zid_offer }, function (data) { $("#t22_outlays").html(data); });
          }
        );
    
}


function s22_art_use(xar)
{
    $("#i22art").val(xar);
    $("#div22art").hide();
}
function s22_art_use2(xid_ar) {
    $("#i22art").val(xid_ar);
    $("#div22art").hide();
}

function s22_art_show()
{
    var art = $("#i22art").val();
    $("#div22art").html(s0_msg_waiting());
    $("#div22art").show();
    ajax_post('m14_article.php?a=1', { xf:art, y: "s22_art_use2" }, function (data) {  $("#div22art").html(data); $("#i14find").val(art);});
}



function s22uploader(xo) {

    var z22uploader =
        new qq.FileUploader({
            element: document.getElementById("file-uploader-img22"),
            action: "m/m22_edt_offer.php?ax=2203&o=" + xo,
            sizeLimit: 8000000,
            minSizeLimit: 1000,
            debug: false,
            multiple: true,
            onComplete: function (id, fileName, responseJSON) {
                var o = $("#m22o").val();
                $("#m22_H_IMGSRC").val('o' + o + '/' + responseJSON.filename);
                $("#m22imgoffer").html('<img style="max-width:180;max-height:180" src="o/o'+o+'/' +responseJSON.filename + '" />');
            }
        });
    return z22uploader;
}

function s22view(event) {
    preventDefault(event);
    var t = event_target(event);
    var s = $(t).attr('src');
    $("#m22imgoffer").html('<img src="' + s + '" />');
    $("#m22_H_IMGSRC").val(s.substr(2,250));
}

 

function s22del_img(xo) {

    alert('функция временно отключена');
    return;


    var z = $("#m22imgoffer").find('img');
    var src = z.attr('src');
    ajax_post('m/p22_edt_offer.php?a=2212', { o: xo, src: src }
         , function (data) {
             var rz = json_parse(data);
             $("#m22imgoffer").html('<p style="color:red">Изображение удалено с сервера!</p>');
             
             $(".photo_offer").each(function (i) {
                 var img = $(this).find('img');
                 var a = img.attr('src');
                 if (a.indexOf(rz.imgsrc) > -1) {
                     //$(this).attr('src', '');
                     $(this).remove();
                 }
             });
             
         });
}


