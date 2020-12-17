
function s24_i24find_chg(event) {
    preventDefault(event);
    $("#a24newo").hide();
}


function s24_find_objects(event)
{
    preventDefault(event);
    var t = $('form[name=f24find]');
    var toj = $("#i24toj").val();
    var ltsj = $("#i24ltsj").val();
    var xfind = $("#i24find").val();
    var st = $("#i3st").val();
    var href = $(t).attr('action');
    var nr = $(t).find('input[name=nr]').val();
    ajax_post(href, { oj: xfind, st:st ,toj:toj, ltsj:ltsj , nr:nr}
        , function (data) {
            $("#a24newo").show();
            $("#OBJECT_L").html(data);
         }
        );
}


function s24btn_newo(event) {
    preventDefault(event);
    var t = event_target(event);
    var oj = $('#i24find').val();  // $("input[name=i24new_oj]").val();
    var tsj = $('#i24tsj').val();
    

    var zhref = $(t).attr('action'); // 'm/m24_objects.php?ax=2403'
    ajax_post(zhref, { oj: oj , tsj:tsj }, function (data) {
        var rz = json_parse(data);
        if (rz['ERR'] == '') {
            s24_find_objects(event);
        } else {
            $("#t24new_oj").html(rz['ERR']);
        }
    });
}



function s24_object_show(xlg_object, xab, xst)
{
    // see also s25oj_show_click(event)
    ajax_post('m/m25_object.php', { oj: xlg_object , ab:xab, st:xst }
        , function (data) { $("#divOBJECT").html(data); $("#divOBJECT").show(); });
}

function s24objects_delete(event) {
    preventDefault(event);
    var t = event_target(event);
    var zhref = $(t).attr('href');
    ajax_post(zhref, {}, function (data) { $(t.parentNode).html('Объекты учёта удалены'); });
}


function s31_oj_show() {
    var oj = $('#i31_LG_OBJECT').val();
    var ab = $('#i31ab').val();
    var st = $('#i31st').val();
    s24_object_show(oj, ab, st);
}

