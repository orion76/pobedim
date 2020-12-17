function input_clear(event) {
    var t = event_target(event); preventDefault(event);
    var n = t.parentNode; $(n).find('input').val('');
}

/*
function droplist_click0(event) {
    var t = event_target(event);
    var ul = getParentNode('UL', t);
    var name_input = $(ul).attr('name');
    $('input[name=' + name_input + ']').val($(t).html());
}
*/

function droplist_click0(event) {
    var t = event_target(event);
    t = getParentNode('LI', t);
    var c = $(t).find('.v');
    var v = '';
    if ($(c).length == 0) { v = $(t).html(); } else { v = $(c).html(); }

    var ul = getParentNode('UL', t);
    var name_input = $(ul).attr('name');
    var n = $('input[name=' + name_input + ']');
    $(n).val(v);
    $(n).change();
    $(n).focus();
}

function droplist_click1(event) {
    var t = event_target(event);
    t = getParentNode('LI', t);
    var c = $(t).find('.v');
    var v = '';
    if ($(c).length == 0) { v = $(t).html(); } else { v = $(c).html(); }

    var ul = getParentNode('UL', t);
    var name_input = $(ul).attr('name');
    var n = $('input[name=' + name_input + ']');
    var v0 = trim($(n).val()); if (v0 != '') { v0 = v0 + ','; }
    $(n).val(v0 + v);
    $(n).change();
    $(n).focus();
}

