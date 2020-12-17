

function s46bg_click(event) {
    preventDefault(event);
    var t = event_target(event);
    t = getParentNode('TR', t);
    var bg = $(t).find('.bg').html();
    $("#fm46bgbx").find('input[name=bg]').val(bg);
}

function s46bx_click(event) {
    preventDefault(event);
    var t = event_target(event);
    t = getParentNode('TR', t);
    var ibx = $(t).find('.bx').html();
    $("#fm46bgbx").find('input[name=ibx]').val(ibx);
}

