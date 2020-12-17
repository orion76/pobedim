function btn18bgx_click(event) {
    preventDefault(event);
    var t = event_target(event);
    $(t).hide();
    var bg = $(t).val();
    ajax_post('m/m18_i.php?ax=1825', { bg: bg }, null);
}

function s18tva_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var n = getParentNode('TR', t);
    $(n).find('input').val('18%');
}

function tag_td(s, attr) { return '<td>' + s + '</td>'; }

function s18getbg_click(event) {
    preventDefault(event);
    var t = event_target(event);
    $("#tb18bg").html('');

    ajax_post($(t).attr('href')
        , {}
        , function (data) {
            var rz = json_parse(data);
            var aa = rz['DS'];
            for (var i = 0; i < aa.length; i++) {
                $("#tb18bg").append('<tr>'
                    + tag_td('<input type="checkbox" name="ck18bg[]" form="f18bgi" value="' + aa[i]['ID_BOOKING'] + '"/>')
                    + tag_td(aa[i]['ID_BOOKING'])
                    + tag_td(1.0 * aa[i]['QNTY_BOOKING'])
                    + tag_td(aa[i]['TITLE_OFFER'])
                    + '</tr>');
            }
            if (aa.length > 0) { $("#btn18bgi").show(); } else { $("#btn18bgi").hide(); }
        });
}