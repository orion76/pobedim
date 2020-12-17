

function s38_submit(event) {
    preventDefault(event);
    var t = event_target(event);
    var href=$(t).attr('action');
    //ajax_post(href, {}, function (data) { });
    var f = getParentNode('FORM', t);
    var te = $(f).find('#i38te').val();
    s3menu_exec(href+'&te='+te, null);
}

