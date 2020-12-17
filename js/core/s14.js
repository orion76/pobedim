function s14(event) {
    preventDefault(event);
}

function s14_find_articles_submit(event) {
    preventDefault(event);
    var zf = $("#i14find").val();
    var zy = $("#y14a").val();
    $("#ARTICLE_L").html(s0_msg_waiting());
    ajax_post("m14_article.php?a=2", { xf: zf , y:zy }
        , function (data) {
            $("#ARTICLE_L").html(data);
            $("#i14newar").val(zf);
        })
}

function s14_edt_submit(event) {
    preventDefault(event);
    var art = $("#i14newar").val();
    var m = $("#s14make").val();
    var t = $("#i14newtar").val();

    ajax_post("m14_article.php?a=5", { art: art, m: m, t: t }
        , function (data) {
            $("#i14find").val(art);
            s14_find_articles_submit(event);
        });
}


