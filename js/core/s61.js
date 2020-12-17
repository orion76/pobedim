
function s61(event) {
    preventDefault(event);
}

function s61_loaded() {
    var m61uploader = s61_uploader("file-m61uploader");
}


function s61_uploaded(id, fileName, responseJSON) {
    if (responseJSON.success) {
        //we can parse it
        ajax_post("p61_offerings_up.php?ax=6106", { fn: responseJSON.filename }
            , function (data) {
                $("#div61").html(data);
            });
    }
}

function s61_uploader(xelement) {
    var elmnt = document.getElementById(xelement);
    var uploader = null;
    if (elmnt != null) {
        uploader = new qq.FileUploader({
            element: elmnt,
            action: "p61_offerings_up.php?ax=6105",
            sizeLimit: 5500000,
            minSizeLimit: 0,
            debug: true,
            multiple: false,
            onComplete: s61_uploaded
        });
    }
    return (uploader);
}

function s61_impt_offerings(xid) {
    var sj = $("#i61sj").val();
    var dy = $("#i61dy").val();
    var go = $("#i61go").val();
    ajax_post('p61_offerings_up.php'
        , { ax: 6107, sj: sj, dy: dy, go: go, id: xid }, function (data) { $("#div61").html(data); CMD = []; }
        );
}
