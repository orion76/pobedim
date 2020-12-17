/*
save to file util 

it requires FileSaver.js
*/

function csv_value(x, fs_ValueDot ,fs_ValueDelimiter) {
    if (x == '' || x == null || x == undefined) { x = '""'; } else
    {
        x = '' + x;
        x = x.replace('\r\n', ' ');
        x = x.replace('"', '""');

        if (fs_ValueDot == ',') { if (IsNumeric(x.replace(',', '.'))) { x = x.replace('.', fs_ValueDot); } }
        if (x.indexOf(fs_ValueDelimiter) > -1 || x.indexOf('"') > -1)
        {
            x = '"' + x + '"';
        }
    }
    return x;
}


function fs_SaveToFileCSV(xFileName, xArrayData) {
    var s = '';
    var fs_ValueDelimiter = ';';
    for (var i = 0; i < xArrayData.length; i++) {
        for (var j = 0; j < xArrayData[i].length; j++) {
            if (j != 0) { s += fs_ValueDelimiter; }
            s += csv_value(xArrayData[i][j], ',', fs_ValueDelimiter);
            //s += csv_value(xArrayData[i][j], '.', fs_ValueDelimiter);
        }
        s += '\r\n';
    }
    var blob = new Blob([s], { type: "csv/plain;charset=utf-8" });
    if (xFileName == '') { xFileName = "file.csv"; }
    saveAs(blob, xFileName);
}


function fs_SaveToFileCSV0(xFileName, xArrayData) {
    var s = '';
    for (var i = 0; i < xArrayData.length; i++) {
        for (var j = 0; j < xArrayData[i].length; j++) {
            if (j != 0) { s += ','; }
            s += csv_value(xArrayData[i][j]);
        }
        s += '\r\n';
    }
    var blob = new Blob([s], { type: "csv/plain;charset=utf-8" });
    if (xFileName == '') { xFileName = "file.csv"; }
    saveAs(blob, xFileName);
}



