

function s67_uploader(xelement) {
    var sn = $('#i67sn').val();
    var pl_ = $('#i67pl').val();
    //pl_ = m67pl;
    var elmnt = document.getElementById(xelement);
    var uploader = null;
    if (elmnt != null) {
        uploader = new qq.FileUploader({
            element: elmnt,
            action: 'm/m67_pl_file_up.php?ax=6705&pl_=' + pl_ + '&sn=' + sn,
            sizeLimit: 5500000,
            minSizeLimit: 0,
            debug: true,
            multiple: false,
            onComplete: function (id, fileName, responseJSON) {
                if (responseJSON.success) {
                    //we can parse it
                    $('#div61').html('on progress');
                    ajax_post('m/m67_pl_file_up.php?ax=6706&pl_=' + pl_ + '&sn=' + sn, { fn: responseJSON.filename }
                        , function (data) {
                            $('#div67').html(data);
                        });
                }
            }
        });
    }
    return (uploader);
}
 