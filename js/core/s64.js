function s64_submit(event) {

    $("#s64tsj").change();

}

function s64refresh(event) {
    preventDefault(event);
    $("#s64tsj").change();
}


//---------------------------------------------------
function s44sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    var f = $(z).find('input[name=f]').val();
    
    ajax_post('m/m64_lsj.php?ax=6401', { nr:nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s44sjbg_select_click);
    });
}

function s44sjbg_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    var n = $(".btn64clicked");
    $(n).find('input').val(sj);
    $(n).find('input').focus();
    $("#m3footer").html('');
}




function s44sjbg_find_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var n = getParentNode('TD', t);

    var tr = getParentNode('TR', n);
    var va = $(tr).find(".va").text(); 
    var sj = $("#i44sj").val();

    $(".btn64clicked").removeClass("btn64clicked");
    $(n).addClass("btn64clicked");
    var f = $(n).find('input').val();
    ajax_post('m/m64_lsj.php?ax=6401', { f:f , va:va, mode:'va-sjbg' , sj:sj }, function (data) {
        var rz=json_parse(data);
        $("#m3footer").html( rz['HTM'] );
        $("#m3footer table").html(rz['TABLE']);
        $("#s64tsj").change(s44sjt_click);
        $(".tr64_sj a.sj").click(s44sjbg_select_click);
        $("#div64 input").focus();
    })
}

//---------------------------------------------------
function s44sjet_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    var f = '';// $(z).find('input[name=f]').val();

    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s44sje_select_click);
    });
}

function s44sje_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    $("#i44sj").val(sj);
    $("#i44sj").focus();
    $("#m3footer").html('');
    s44sj_blur(event);
}


function btn44xfrm_click(event) {
    preventDefault(event);
    $("form[name=f64]").hide();
}

function s44sje_find_click(event) {
    preventDefault(event);
    var f = $("#i44sj").val();
    var tsj = $("#i44tsj").val();
    var sj = $("#i44sj").val();
    var st = $("#i44st").val();
    ajax_post('m/m64_lsj.php?ax=6401', { st:st, f: f, tsj:0, sj: sj, te:1 }
        , function (data) {
            var rz = json_parse(data);
            $("#m3footer").html(rz['HTM']);
            $("#m3footer table").html(rz['TABLE']);
            $("#s64tsj").change(s44sjet_click);
           
            $("form[name=f64]").show();
            $("#div64 input").focus();

            s36sjt_refresh(tsj, s44sje_select_click); //$(".tr64_sj a.sj").click();
        })
}

//---------------------------------------------------
function s45sjbg_find_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var n = getParentNode('TD', t);
    $(".btn64clicked").removeClass("btn64clicked");
    $(n).addClass("btn64clicked");
    var f = $(n).find('input').val();
    ajax_post('m/m64_lsj.php?ax=6401', { f: f }, function (data) {
        var rz = json_parse(data);
        $("#m3footer").html( rz['HTM'] );
        $("#m3footer table").html(rz['TABLE']);
        $("#s64tsj").change(s45sjt_click);
        $(".tr64_sj a.sj").click(s45sjbg_select_click);
        $("#div64 input").focus();
    })
}

function s45sjbg_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    var n = $(".btn64clicked");
    $(n).find('input').val(sj);
    $(n).find('input').focus();
    $("#m3footer").html('');
}

function s45sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    var f = $(z).find('input[name=f]').val();

    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s45sjbg_select_click);
    });
}




//---------------------------------------------------
function s3sjbg_find_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var f = $('input[name=i3btsj]').val();
    ajax_post('m/m64_lsj.php?ax=6401', { f: f }, function (data) {
        var rz = json_parse(data);
        $("#m3footer").html( rz['HTM'] );
        $("#m3footer table").html(rz['TABLE']);
        $("#s64tsj").change(s3sjt_click);
        $("#div64 a.sj").click(s3sjbg_select_click);
        $("#div64 input").focus();
    })
}


function s3sjbg_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    var n = $('input[name=i3btsj]'); n.val(sj);
    s3btsj_change_t(n);
    $("#m3footer").html('');
}


function s3sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    var f = $(z).find('input[name=f]').val();

    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s3sjbg_select_click);
    });
}


//---------------------------------------------------
function s43sj_find_click(event) {
    preventDefault(event);
    var t = event_target(event);

    var fm = $('form[name=fm43sj]');
    var f = $(fm).find('input[name=sj]').val();
    var st = $(fm).find('select[name=st]').val();
    ajax_post('m/m64_lsj.php?ax=6401', { f: f , st:st}, function (data) {
        var rz = json_parse(data);
        $("#m3footer").html(rz['HTM']);
        $("#m3footer table").html(rz['TABLE']);
        $("#s64tsj").change(s43sjt_click);
        $("#div64 a.sj").click(s43sj_select_click);
        $("#div64 input").focus();
    })
}


function s43sj_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');

    var fm = $('form[name=fm43sj]');
    var n = $(fm).find('input[name=sj]'); n.val(sj);
    s3btsj_change_t(n);
    $("#m3footer").html('');
}


function s43sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    var f = $(z).find('input[name=f]').val();

    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s43sj_select_click);
    });
}

//---------------------------------------------------
function s36sjt_refresh(tsj, fx_select_click) {
    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    if (nr < 0 || nr == '' || nr == null) nr = 10;
    var f = $(z).find('input[name=f]').val();

    var d = $('#m64d_').val();
    if (d == undefined) { d = $('#m36doc').val(); }


    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, d: d, tsj: tsj }
        , function (data) {
            var rz = json_parse(data);
        $("#m3footer").html(rz['HTM']);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(fx_select_click);
        $("#div64 input").focus();
        $("#btn64refresh").click(s36sjt_click);
        $("#s64tsj").change(s36sjt_click);
    });
}



function s36sjd_find_click(event) {
    preventDefault(event);
    var t = event_target(event);
    s36sjt_refresh(0, s36sjd_select_click);
}
 
 
function s36sjd_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    var nsj = $(t).attr('name');
    var sj_ = $(t).attr('sj_');
    $('#m36sjdoc').val(sj);
    $('#m36sj_').val(sj_);
    $('#m36nsj').html(nsj);
    $("#m3footer").html('');
}
 


function s36sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var tsj = $("#s64tsj").val(); // $(t).val();
    s36sjt_refresh(tsj, s36sjd_select_click);
}




//---------------------------------------------------
function s16sje_find_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var f = $('#i16sj').val();
    ajax_post('m/m64_lsj.php?ax=6401', { f: f }
      , function (data) {
        var rz = json_parse(data);
        $("#m3footer").html(rz['HTM']);
        $("#m3footer table").html(rz['TABLE']);
        $("#s64tsj").change(s36sjt_click);
        $("#div64 a.sj").click(s16sje_select_click);
        $("#div64 input").focus();
    })
}


function s16sje_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    var n = $('#i16sj'); n.val(sj);
    $("#m3footer").html('');
}


function s16sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    var f = $(z).find('input[name=f]').val();

    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s16sje_select_click);
    });
}


//------------------------------------------------------------------------

//---------------------------------------------------
function s17sje_find_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var f = $('#i17sj').val();
    ajax_post('m/m64_lsj.php?ax=6401', { f: f }
      , function (data) {
          var rz = json_parse(data);
          $("#m3footer").html(rz['HTM']);
          $("#m3footer table").html(rz['TABLE']);
          $("#s64tsj").change(s36sjt_click);
          $("#div64 a.sj").click(s17sje_select_click);
          $("#div64 input").focus();
      })
}


function s17sje_select_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var sj = $(t).attr('href');
    var n = $('#i17sj'); n.val(sj);
    $("#m3footer").html('');
}


function s16sjt_click(event) {
    preventDefault(event);
    var t = event_target(event);
    var ltsj = $(t).val();

    var z = $('form[name=f64]');
    var nr = $(z).find('input[name=nr]').val();
    if (nr == null || nr <= 0) nr = 10;
    var f = $(z).find('input[name=f]').val();

    ajax_post('m/m64_lsj.php?ax=6401', { nr: nr, f: f, ltsj: ltsj }, function (data) {
        var rz = json_parse(data);
        $("#m3footer table").html(rz['TABLE']);
        $(".tr64_sj a.sj").click(s17sje_select_click);
    });
}

