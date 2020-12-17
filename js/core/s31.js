/*
function s3sjfind(event) {
    var t = event_target(event);
    var sj = $("input[name=i3btsj]").val();
    $("#div3sjfind").html('..........');
    ajax_post('m/m31_subjects.php?ax=3131', { ltsj: ',12,51,53,55,' , sj:sj }
        , function (data) {
            var rz = JSON.parse(data);
            $("#div3sjfind").html(rz['HTM']);
            $("#div3sjfind").find('button').click(s3sjselect);
        });
}
function s3sjselect(event) {
    var t = event_target(event);
    t = getParentNode('TR', t);
    $("input[name=i3btsj]").val( $(t).find('td.sj').html() );
    $("#div3sjfind").html('');
    s3btsj_change_t($("input[name=i3btsj]"));
}
*/





function i31filter_change(event) {
    var st = $('#m31st').text();
    $(".c30subject").hide();
    var f = $("#i31f").val(); // event_target(event).value;
    var toj = $("#i31toj").val();
    var lon = '';  
    var chk = $("#i31_oj00").prop('checked');
    var nr = $('input[name=nr]').val();
    if (chk) { lon = lon + ',OJ00,'; }

    ajax_post("m/m31_subjects.php?ax=3102", { f: f, st:st, lon:lon, toj:toj, nr:nr }, function (data) {
        var rz = json_parse(data);
        if (rz.ROW_COUNT == 0) {
            $("#btn31newsj").show();
        } else {
            $("#btn31newsj").hide();
        }
        var zhtml = rz['HTML'];
        rz['HTML'] = null;
        $("#t31subjects").html(zhtml);
    });
}


function s31sj_new(event) {
    preventDefault(event);
    var st = $('#m31st').text();
    var zlg = $("#i31f").val();

    ajax_post("m/m30_subject.php?", { sj: zlg, st: st, on:1 }
        , function (data) {
            s31subjects_data(data);
            $(".c30subject button").hide();
        });
}

function s31subjects_data(data) {
    var rz = json_parse(data);
    var err = rz['ERR'];
    if (err.indexOf('ERR') > -1) { DoMsg('err', err); }
    else {
        ClearMsg();
        $("#div31subject").html(rz['HTML']);
        location.hash = "#a31subject";
        if (rz['ROW_COUNT'] == 1) {
            var v = rz['DS'][0];

            $("#i31sj").val(v['LG_SUBJECT']);
            $("#i31st").val(v['LG_SITE']);
            $("#i31_NAME_SUBJECT").val(v['NAME_SUBJECT']);
            $("#i31_LG_OBJECT").val(v['LG_OBJECT']);
            $("#i31_AU_OBJECT").val(v['AU_OBJECT']);
            $("#i31_LG_DISCOUNT").val(v['LG_DISCOUNT']);
            $("#i31_TEXT_CONTACT").val(v['TEXT_CONTACT']);
            $("#i31_TEXT_CARGOADDRESS").val(v['TEXT_CARGOADDRESS']);
            $("#i31_PI_INN").val(v['PI_INN']);
            $("#i31_PI_KPP").val(v['PI_KPP']);
            $("#i31_PI_BIK").val(v['PI_BIK']);
            $("#i31_PI_ACNT").val(v['PI_ACNT']);
            $("#i31_PI_TEXT").val(v['PI_TEXT']);
            $("#i31_PI_NAME").val(v['PI_NAME']);
            $("#i31_TEXT_JURADDRESS").val(v['TEXT_JURADDRESS']);
            $("#i31_TEXT_DIRECTOR").val(v['TEXT_DIRECTOR']);
            $("#i31_TEXT_BOOKKEEPER").val(v['TEXT_BOOKKEEPER']);

            //$("#i31_SORTING_SUBJECT").val(v['SORTING_SUBJECT']);
            //$("#i31_T_ENTRY").val(v['T_ENTRY']);
            //$("#i31_ACCESS_USERS").val(v['ACCESS_USERS']);
            $("#i31_PRIORITY_SUPPLIER").val(v['PRIORITY_SUPPLIER']);
            $("#i31_RATIO_PRICE_SUBJECT").val(v['RATIO_PRICE_SUBJECT']);
        }
        $(".c30subject").show();
        $(".c30subject button").show();
    }
}

function s31subjects_click(event) {
    preventDefault(event);
    var tr = event_target(event).parentNode;
    var o = $(tr);
    var zlg = o.attr('data-id');
    var st = $('#m31st').text();
    ajax_post("m/m30_subject.php?", { sj: zlg, st:st, on:0 }, s31subjects_data );
}


function s31subject_submit(event) {
    preventDefault(event);
    var t = event_target(event);
    ClearMsg();
    var sj = $("#i31sj").val();
    var st = $("#i31st").val();
    var ab = $("#i31ab").val();

    var tsj = $(t).find('select[name=tsj]').val();
    var sk = $(t).find('select[name=sk]').val();
    var lons_sj = $(t).find('input[name=i30lonssj]').val();

    var va = $("#i30va").val();
    var sj_ = $("#i30sj_").val();
    var i31_NAME_SUBJECT = $("#i31_NAME_SUBJECT").val();
    var i31_LG_OBJECT = $("#i31_LG_OBJECT").val();
    var i31_LG_DISCOUNT = $("#i31_LG_DISCOUNT").val();
    var i31_TEXT_CONTACT = $("#i31_TEXT_CONTACT").val();
    var i31_TEXT_CARGOADDRESS = $("#i31_TEXT_CARGOADDRESS").val();
    var i31_PI_INN = $("#i31_PI_INN").val();
    var i31_PI_KPP = $("#i31_PI_KPP").val();
    var i31_PI_BIK = $("#i31_PI_BIK").val();
    var i31_PI_ACNT = $("#i31_PI_ACNT").val();
    var i31_PI_TEXT = $("#i31_PI_TEXT").val();
    var i31_PI_NAME = $("#i31_PI_NAME").val();
    var i31_TEXT_JURADDRESS = $("#i31_TEXT_JURADDRESS").val();
    var i31_TEXT_DIRECTOR = $("#i31_TEXT_DIRECTOR").val();
    var i31_TEXT_BOOKKEEPER = $("#i31_TEXT_BOOKKEEPER").val();
    var psy = $("#i31_PRIORITY_SUPPLIER").val();
    var r1psj = $("#i31_RATIO1_PC").val();
    var r2psj = $("#i31_RATIO2_PC").val();
    ajax_post("m/m30_subject.php?ax=3002", { sk:sk, sj_:sj_, va:va,
        st: st, sj: sj, tsj: tsj, ab: ab, lons_sj: lons_sj
        , ns: i31_NAME_SUBJECT, oj: i31_LG_OBJECT
        , dcnt: i31_LG_DISCOUNT, tc: i31_TEXT_CONTACT, tca: i31_TEXT_CARGOADDRESS
        , inn: i31_PI_INN, kpp: i31_PI_KPP, bik: i31_PI_BIK, acnt: i31_PI_ACNT, pt: i31_PI_TEXT, pn: i31_PI_NAME
        , ja: i31_TEXT_JURADDRESS, dir: i31_TEXT_DIRECTOR, bkr: i31_TEXT_BOOKKEEPER, psy: psy, r1psj: r1psj, r2psj: r2psj
    }
            , function (data) {
                var rz = json_parse(data);
                if (rz.ERR != '') {
                    DoMsg('err', rz.ERR);
                }
                    /*
                else {
                    //$("#div31subject").hide();
                    //s31subjects_click(event);
                }*/
            }
      );
}

