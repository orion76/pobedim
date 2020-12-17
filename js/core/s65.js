

function s65_submit(event) {
    preventDefault(event);
    var t = event_target(event);
    t = getParentNode('FORM', t);

    var f = $(t).find('input[name=ofind]').val();
    var ltsj = $(t).find('input[name=ltsj]').val();
    var href = $(t).attr('action');
    var xp = { ofind: f, ltsj: ltsj };
    s3menu_exec(href, null, xp);
}

 