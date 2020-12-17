function blurTextarea(event){ 
    var n = event_target(event);
    var ta = n.parentNode.querySelector("textarea");
    ta.innerHTML = n.innerHTML;
 }



﻿function json_parse(s) {
    s = trim(s);
    if (s == '' || s == null) { return {"ERR":"ERR: no data"}; }
    else {
        if (s.indexOf('{') != 0 && s.indexOf('[') != 0) {
            ajax_post('m/p81_saying.php?ax=8103', { t: s, sgq: 680, tsg: 3 }, function (data) { });
            return {"ERR":"ERR: invalid data"};
        } else { return JSON.parse(s); }
    }
}

function r0ok(rz) { if (rz['ERR'] == '') { return true; } else { return false; } }
function r0r(rz) {
    if (r0ok(rz)) { return rz['DS'][0]['R']; } else { return rz['ERR']; }
}

function str_iif(b,t,f) {
    if (b) {return t;} else {return f;}
}

function ajax_post(xl, xp, xf) {
    $(".busy").show();
    $.post(xl, xp,
            function (data) {
                if (xf) {
                    try {
                        xf(data);
                    } catch (ex) { }
                } $(".busy").hide();
            });
}

function getParentNode(xtagName, xn) {
    var i = 10;
    xtagName = xtagName.toUpperCase();
    while (xn.tagName != xtagName && i > 0 ) { i--; xn = xn.parentNode;
      if (xn.tagName == 'HTML') return null;
    }
    if (xn.tagName == xtagName) { return xn; } else { return null; }
}




function trim(str) {
    if (!str || typeof str != 'string' || str == null || str == undefined)
        return '';

    return str.replace(/^[\s]+/, '').replace(/[\s]+$/, '').replace(/[\s]{2,}/, ' ');
}


function setCookie(name, value, expire)
{
    var ws = new Date();
    ws.setMinutes(expire + ws.getMinutes());
    document.cookie = name + "=" + escape(value)
    + ((expire == null) ? "" : ("; expires=" + ws.toGMTString()))
}

function getCookie(Name) {
    var search = Name + "="
    if (document.cookie.length > 0) { // если есть какие-либо куки
        offset = document.cookie.indexOf(search)
        if (offset != -1) { // если кука существует 
            offset += search.length
            // установить индекс начала значения
            end = document.cookie.indexOf(";", offset)
            // установить индекс конца значения куки
            if (end == -1)
                end = document.cookie.length
            return unescape(document.cookie.substring(offset, end))
        }
    }
}

function setLocationHash(curLoc) {
    var zhref = location.href;
    var zsearch = location.search;
    try {
        // history.pushState(v_state, null, '#' + curLoc);
        history.pushState(v_state, null, zhref);
        return;
    } catch (e) { }
    location.hesh = '#' + curLoc;
    location.search = zsearch;
}


function setLocationSN(xsn) {
    var s = location.search;
    if (s == '' || s == null) {
        location.href = location.pathname + '?sn=' + xsn;
    } else {
        if (s.indexOf('?sn=') == -1 && s.indexOf('&sn=') == -1) {
            location.href = location.pathname + s + '&sn=' + xsn;
        }
    }
}

/*

void pushState(state, title, url); // Добавление
void replaceState(state, title, url); // Замена текущего

window.addEventListener("popstate", function(event) {
    // какое то событие, например, открытие фото с фреймом
    // Если вы использовали переменную state, то она доступна в event.state 
}

*/

function IsNumeric(x) {
    return ((x - 0) == x) && (('' + x).trim().length > 0);
}

function toNum(s) {
    if (s == undefined) { s = '0'; }
    s = trim(s.replace(' ', ''));
    if (s == null) { return 0; }
    else {
        if (s.indexOf(",") > -1) { s = s.replace(',', '.'); }
        if (isNaN(s) || s == "") { return 0; } else { return 1.0*s; }
    }
}

function toInt(s) {
    if (isNaN(s) || s == "") { return 0; }
    s = '' + s;
    if (s.indexOf(",") > -1 || s.indexOf(".") > -1) { return 0; }
    else { return 1*s; }
}


function createDocument(html) {
    var doc = document.implementation.createDocument('http://www.w3.org/1999/xhtml', 'html', null);
    doc.documentElement.innerHTML = html;
    return doc;
}
function print_test(event) {
    var w = window.open();
    w.document.body.innerHTML = 'test test';
    w.print();
}

function IsDDMMYYYY(s)
{
    var re = new RegExp("^([0-9]|0[1-9]|1[0-9]|2[0-9]|3[0-1])[- / .]([1-9]|0[1-9]|1[0-2])[- / .](1[9][5-9][0-9]|2[0][0-1][0-9])$");
    return s.match(re);
}

function o_SetErr(o, isErr)
{
    if (isErr) { o.addClass("err"); } else { o.removeClass("err"); }
    return isErr;
}

function o_IsErrDate(o) {
    var s = o.val();
    if (s.indexOf(",") > -1) { s = s.replace(',', '.'); o.val(s); }
    if (!IsDDMMYYYY(s)) { o.addClass("err"); return true; } else { o.removeClass("err"); return false; }
}

function o_IsErrNum(o) {
    var n = o.val();
    if (n.indexOf(",") > -1) { n = n.replace(',', '.'); o.val(n); }
    if (isNaN(n)) { o.addClass("err"); return true; } else { o.removeClass("err"); return false; }
}

function o_IsErrInt(o) {
    var n = o.val();
    if (n.indexOf(",") > -1 || n.indexOf(".") > -1 || isNaN(n)) { o.addClass("err"); return true; } else { o.removeClass("err"); return false; }
}


function addEvent(elem, type, handler) {
    if (elem.addEventListener) { elem.addEventListener(type, handler, false) }
    else { elem.attachEvent("on" + type, handler) }
}
function removeEvent(elem, type, handler) {
    if (elem.removeEventListener) { elem.removeEventListener(type, handler, false) }
    else { elem.detachEvent("on" + type, handler) }
}


function preventDefault(event) {
    if (event == null || event == undefined ) return;
    // bugfix of IE8
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
}

function event_target(event)
{
    if (event.target) { return event.target; } else { return event.srcElement; }
}

function event_attr(event, attr) { var a = null; if (event_target(event).hasAttribute(attr)) { a = event_target(event).getAttribute(attr); } return (a); }
function _getDate() {
    var month_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    var d = new Date();
    var current_date = d.getDate();
    var current_month = d.getMonth();
    var current_year = d.getFullYear();
    return current_date + " " + month_names[current_month] + " " + current_year;
}

function fmtDDMMYYYY(date) {
    return date.getDate() + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();
}


function ts(href) { var d = new Date(); return (href + "&tmp=" + d.getTime()); }


function page(xpage) { return 'core/' + xpage; }


function getElementById(xid) {
    return document.getElementById(xid);
}



/*
 * Объект "validator" 
 http://php-zametki.ru/javascript-laboratoriya/106-jsformvalidator.html
 */
var validator = (function (GLOB) {
    return {
        // Объект для тестов. Здесь он пустой
        // Наполняется он в файле validator_tests.js
        tests: {},
        /**
         * Метод для валидации формы.
         * Вешается на обработчик onsubmit
         * @param object form: Объект html формы.
         * @return boolean true|false
         */
        check: function (form) {
            var DOC = GLOB.document,   // Зависимость 
                elements = form.elements,   // Здесь будут элементы формы
                elLength = elements.length, // Количество элементы формы
                require = false, // Флаг: обязательно ли поле
                curItem = null,  // Текущий (для цикла) элемент формы
                curVal = null,  // Текущее (для цикла) значение элемента формы
                curTest = null,  // Текущий (для цикла) класс/тест элемента формы
                errSpan = null,  // Контейнер для ошибок элемента формы
                tests = [],    // Здесь будут классы/тесты элемента формы
                errors = {},    // Флаг указывает есть ли ошибки
                prop = null,  // Своство для обхода объекта errors
                testsLength = 0,  // Количество классов/тестов элемента формы               
                i, // Счётчики циклов
                q;
            for (i = 0; i < elLength; i += 1) {
                // Получаем текущий элемент:    
                curItem = elements[i];
                // Получаем текущее значение:
                curVal = curItem.value;
                // Пропускаем элементы не имеющие классов/тестов:
                if (typeof (curItem.className) === "undefined") {
                    continue;
                }
                // Узнаём обязателен ли текущий элемент:
                require = (curItem.className.indexOf("require") !== -1);
                // Пытаемся получить ссылку на элемент-контейнер ошибок:
                errSpan = DOC.getElementById(curItem.name + "_error");
                // Если элемента-контейнера не существует
                if (!errSpan) {
                    // ... формируем его:
                    errSpan = DOC.createElement("SPAN");
                    errSpan.id = curItem.name + "_error";
                    errSpan.className = "error";
                    // и добавляем его в DOM - древо, 
                    // сразу после текущего элемента формы.
                    curItem.parentNode.insertBefore(errSpan, curItem.nextSibling);
                }
                // Если текущий элемент не обязателен и не заполнен...
                if (curVal.length < 1 && !require) {
                    // Очищаем его контейнер, на случай, если он уже содержал текст ошибки,
                    errSpan.innerHTML = "";
                    // и пропускаем итерацию цикла.
                    continue;
                }
                // Получаем имена классов/тестов в массив:
                tests = curItem.className.split(" ");
                // Получаем длину массива:
                testsLength = tests.length;
                // Проходим по массиву классов/тестов циклом:
                for (q = 0; q < testsLength; q += 1) {
                    // Получаем текущее имя класса: 
                    curTest = tests[q];
                    // Если текущее имя класса не является тестом...
                    if (!this.tests.hasOwnProperty(curTest)) {
                        // пропускаем итерацию.
                        continue;
                    }
                    // Собсна проверка:
                    if (!curVal.match(this.tests[curTest].condition)) {
                        // Устанавливаем флаг для этой ошибки:
                        errors[curItem.name] = true;
                        // Не удачно - пишем ошибку в контейнер:
                        errSpan.innerHTML = this.tests[curTest].failText;
                        // Останавливаем цикл - вывод остальных ошибок для этого элемента не нужен,
                        // - не зачем пугать пользователя, пусть сначала устранят ту ошибку что есть.
                        break;
                    } else {
                        // Снимаем флаг ошибки:
                        errors[curItem.name] = false;
                        // Удачно - очищаем контейнер от содержимого.
                        errSpan.innerHTML = "";
                    } // END IF
                } // END FOR
            } // END FOR

            /* 
             * Проверяем наличие установленных флагов ошибок. 
             * Если ошибок нет возвращаем true - в этом случае
             * Обработчик "onsubmit" должен штатно отработать.
             */
            for (prop in errors) {
                if (errors.hasOwnProperty(prop) && errors[prop] === true) {
                    return false;
                }
            }
            return true;
        }
    };
}(this));




function checkit(v, t) {
    var r = { valid: true, errmsg: "" };
    if (t == "date") {
        if (!isDate(v)) { r = { valid: false, errmsg: "error" } }
    }
    else
        if (t == "decimal") {
            if (!isDecimal(v)) { r = { valid: false, errmsg: "error" } }
        }
    return r;
}




function isNumber(inputtxt) {
    //    new RegExp("pattern"[, флаги])   http://javascript.ru/basic/regular-expression#obekt-regexp
    var numbers = /^[0-9]+$/;
    if (inputtxt.match(numbers)) { return true; }
    else { return false; }
}


function isDecimal(inputtxt) {
    var decimal = /^[-+]?[0-9]+\.[0-9]+$/;
    var numbers = /^[0-9]+$/;
    if (inputtxt.match(decimal) || inputtxt.match(numbers)) { return true; }
    else { return false; }
}



function isDate(value)
{
    value = trim(value);
    var Ok = isDate0(value, '-', 2, 1, 0);
    if (!Ok) { Ok = isDate0(value, '.', 0, 1, 2); }
    return Ok;
}

//Value parameter - required. All other parameters are optional.
function isDate0(value, sepVal, dayIdx, monthIdx, yearIdx) {
    try {
        //Change the below values to determine which format of date you wish to check. It is set to dd/mm/yyyy by default.
        var DayIndex = dayIdx !== undefined ? dayIdx : 0;
        var MonthIndex = monthIdx !== undefined ? monthIdx : 0;
        var YearIndex = yearIdx !== undefined ? yearIdx : 0;

        value = value.replace(/-/g, "/").replace(/\./g, "/");
        var SplitValue = value.split("/"); // sepVal ||
        var OK = true;
        if (!(SplitValue[DayIndex].length == 1 || SplitValue[DayIndex].length == 2)) {
            OK = false;
        }
        if (OK && !(SplitValue[MonthIndex].length == 1 || SplitValue[MonthIndex].length == 2)) {
            OK = false;
        }
        if (OK && SplitValue[YearIndex].length != 4) {
            OK = false;
        }
        if (OK) {
            var Day = parseInt(SplitValue[DayIndex], 10);
            var Month = parseInt(SplitValue[MonthIndex], 10);
            var Year = parseInt(SplitValue[YearIndex], 10);

            if (OK = ((Year > 1900) && (Year <= new Date().getFullYear()))) {
                if (OK = (Month <= 12 && Month > 0)) {

                    var LeapYear = (((Year % 4) == 0) && ((Year % 100) != 0) || ((Year % 400) == 0));

                    if (OK = Day > 0) {
                        if (Month == 2) {
                            OK = LeapYear ? Day <= 29 : Day <= 28;
                        }
                        else {
                            if ((Month == 4) || (Month == 6) || (Month == 9) || (Month == 11)) {
                                OK = Day <= 30;
                            }
                            else {
                                OK = Day <= 31;
                            }
                        }
                    }
                }
            }
        }
        return OK;
    }
    catch (e) {
        return false;
    }
}