var m35ee = [];

function s35(event) {
    preventDefault(event);
}



function s35_loaded() {
    var t35rr = $("#t35ee");
    for (var i = 0; i < m35ee.length; i++) {
        var zo = m35ee_tr(m35ee[i]);
        t35rr.append(zo);
    }
}



function s35_2csv() {
    var aa = [
                ['T_ENTRY', 'SJ_DOC', 'ID_ENTRYITEM', 'ID_DOC', 'ID_ENTRY'
                        , 'SUM_DOCITEM', 'QNTY_DOCITEM'
                        , 'PRICE', 'DATE_DOC']
            ];
    
    for (var i = 0; i < m35ee.length; i++) {
        var zo = m35ee[i];
        var a1 = [zo['T_ENTRY'], zo['SJ_DOC'], zo['ID_ENTRYITEM'], zo['ID_DOC']
            , zo['ID_ENTRY'], zo['SUM_DOCITEM'], zo['QNTY_DOCITEM']
            , zo['PRICE'], zo['DATE_DOC']
        ];
        aa.push(a1);
    }

    fs_SaveToFileCSV('', aa);
}
