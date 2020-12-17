


function s37_uploader(xelement) {
    var sj = $('#i62sj').val();
    var st = $('#i62st').val();
    var sn = $('#i62sn').val();
    var elmnt = document.getElementById(xelement);
    var uploader = null;
    if (elmnt != null) {
        uploader = new qq.FileUploader({
            element: elmnt,
            action: 'm/m62bgfiles_up.php?ax=3705&sj=' + sj + '&st=' + st + '&sn=' + sn,
            sizeLimit: 5500000,
            minSizeLimit: 0,
            debug: true,
            multiple: false,
            onComplete: function (id, fileName, responseJSON) {
                if (responseJSON.success) {
                    //we can parse it
                    ajax_post('m/m62bgfiles_up.php?ax=3706&sj=' + sj + '&st=' + st + '&sn=' + sn, { fn: responseJSON.filename }
                        , function (data) {
                            $("#div37").html(data);
                        });
                }
            }
        });
    }
    return (uploader);
}