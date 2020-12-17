function s60(event) {
    preventDefault(event);
}





function btn60d_new(event) {
    preventDefault(event);
    var t = event_target(event);
    var href = $(t).attr('href');
    alert(href);
/*
    var nt = getParentNode('TABLE', t);
        $(nt).find('button').hide();
    var n = getParentNode('TR', t);
    var q = $(n).find('input[name=i49q]').val();
    var to = $(n).find('input[name=i49to]').val();
    var osj = $(n).find('td.osj').html();
    var st = $(n).find('td.st').html();
    var pco = $(n).find('td.pco').html();
    var oc = $('select[name=m49oc]').val();
    var ab = $('input[name=i49ab]').val();
    var sn = $('input[name=i49sn]').val();
    var o = $('input[name=i49o]').val();
    var s = 1.0 * q * pco;
    ajax_post('m/m49bcbg.php?ax=4905', { s: s, to: to, o: o, q: q, osj: osj, st: st, pco: pco, oc: oc, ab: ab, sn: sn }, function (data) {
        $(nt).html('сформирован заказ для отправки поставщику, перейдите в корзину для подтверждения отправки и формирования файла заказа');
    });
*/

}
