function s83_saysbmit(event) {
    preventDefault(event);
    var t = $("#i83topic").val();
    var s = $("#i83say").val();
    ajax_post("p81_meeting.php?ax=8301", { s: s, t: t, m: 1, nn: 1 }
        , function (data) {
        });
}

function s83_topic_click(event) {
    
    if (event != null) {
        $(".c83topic").toggleClass("td_selected", false);
        var e = event_target(event);
        $(e).toggleClass("td_selected", true);
    }

    var t = $(".c83topic.td_selected").eq(0);

    ajax_post("p81_meeting.php?ax=8303", { t:t }
            , function (data) {
                $("#d83says").html(data);
            });
}

function s83_loaded(event) {

    ajax_post("p81_meeting.php?ax=8302", {   }
            , function (data) {
                $("#d83topics").html(data);
                $(".c83topic").click(s83_topic_click);
                var s = $(".td_selected").val();
                if (s == undefined) {
                    var c = $(".c83topic").eq(0);
                    c.toggleClass("td_selected", true);
                }
                s83_topic_click(null);
            });

}

