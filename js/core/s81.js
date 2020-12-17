

function s81_submit_new_topic(event) {
    preventDefault(event);
    var v = trim($("#m3s81t").val());
    if (v != '') {
        ajax_post('m/p81_saying.php?ax=8101', { t: v }, function (data) { $("#m3s81t").val(''); $("#m3lsg").load('m/p81_saying.php?ax=8102'); });
    }
}



function s81text_submit(event) {
    preventDefault(event);
    var t = $("#i81t").val();
    var sgq = $("#i81sgq").val();
    ajax_post('m/p81_saying.php?ax=8103', { t: t, u: '', sgq: sgq, tsg: 3 }, function (data) {
        $("#i81t").val('');
        var n = $("#div_lsg");
        var id = n.parent()[0].id;
        $("#"+id).load('m/m81_saying.php?sg=' + sgq);
    });
}
function s81user_submit(event) {
    preventDefault(event);
    var u = $("#i81u").val();
    var sgq = $("#i81sgq").val();
    ajax_post('m/p81_saying.php?ax=8103', { t: '', u: u, sgq: sgq, tsg: 2 }, function (data) { });
}


function b81hide8_click(event) {
    $("#m81lsg").toggleClass("hide8");
}

 

function s81uploader(xelement, xsg) {
    var elmnt = document.getElementById(xelement);
    var uploader = null;
    if (elmnt != null) {
        uploader = new qq.FileUploader({
            element: elmnt,
            action: "m/p81_saying.php?ax=8105&sg=" + xsg,
            sizeLimit: 5500000,
            minSizeLimit: 0,
            debug: false,
            multiple: true,
            onComplete: function (id, fileName, responseJSON) {
                var d = $("#i81dsg").val();
                $("#m81lsg").load('m/m81_saying.php?ax=8110&sg=' + xsg+'&d='+d);
            }
        });
    }
    return (uploader);
}







function i81filter_change(event) {
    var f = $("#i81f").val(); // event_target(event).value;
    ajax_post("m81_nnames.php?ax=8102", { f: f }, function (data) {
        $("#t81nnames").html(data);
        //$("#t31subjects tr").click(s31subjects_click);
    });
}




/*function i81filter_change(event) {
    var f = $("#i81f").val(); // event_target(event).value;
    ajax_post("m81_nnames.php?ax=8102", { f: f }, function (data) {
        $("#t81nnames").html(data);
        //$("#t31subjects tr").click(s31subjects_click);
    });
}
*/


/*
    var tr = event_target(event).parentNode;
    var o = $(tr);
    var zlg = o.attr('data-id');
    ajax_post("p81_subjects.php?ax=3101", { s: zlg }
        , function (data) {
            var r = json_parse(data);
            var rz = r['DS'][0];
            $("#i31_LG_SUBJECT").val(rz['LG_SUBJECT']);
            $("#i31_NAME_SUBJECT").val(rz['NAME_SUBJECT']);
            $("#i31_LG_OBJECT").val(rz['LG_OBJECT']);
            $("#i31_AU_OBJECT").val(rz['AU_OBJECT']);
            $("#i31_LG_DISCOUNT").val(rz['LG_DISCOUNT']);
            $("#i31_TEXT_CONTACT").val(rz['TEXT_CONTACT']);
            $("#i31_TEXT_CARGOADDRESS").val(rz['TEXT_CARGOADDRESS']);
            $("#i31_PI_INN").val(rz['PI_INN']);
            $("#i31_PI_KPP").val(rz['PI_KPP']);
            $("#i31_PI_BIK").val(rz['PI_BIK']);
            $("#i31_PI_ACNT").val(rz['PI_ACNT']);
            $("#i31_PI_TEXT").val(rz['PI_TEXT']);
            $("#i31_PI_NAME").val(rz['PI_NAME']);
            $("#i31_TEXT_JURADDRESS").val(rz['TEXT_JURADDRESS']);
            $("#i31_TEXT_DIRECTOR").val(rz['TEXT_DIRECTOR']);
            $("#i31_TEXT_BOOKKEEPER").val(rz['TEXT_BOOKKEEPER']);
            $("#div31subject").show();
            location.href = "#a31subject";
        }
      );
*/


