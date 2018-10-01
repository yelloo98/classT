/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  :
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/20     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

//######################################################################################################################
//##
//## >> Variable: 공통변수
//##
//######################################################################################################################
var url = document.location.href;
var urlPath = document.location.pathname;
var urlParams = document.location.search;

//######################################################################################################################
//##
//## >> Function : Debug
//##
//######################################################################################################################
if(typeof testP != "function"){
    function testP(){
        var length = arguments.length;
        if(length == 0) {
            console.log("ENJCOM");
        }
        else if(length == 1) {
            console.log(arguments[0]);
        }
        else {
            var args = [];
            for(var i in arguments){
                args.push('arguments['+i+']');
            }
            eval('console.log('+args.join()+');')
        }
    }
}

if(typeof testT != "function"){
    function testT(func){
        console.time("ENJCOM");
        if(typeof func == "function")func();
        console.timeEnd("ENJCOM");
    }
}

//######################################################################################################################
//##
//## >> Function : Tag Action
//##
//######################################################################################################################
if(typeof addAction != "function"){
    function addAction(selector, func){
        if(!selector) return;
        jQuery(selector).off('click');
        jQuery(selector).on('click',function(e){
            e.preventDefault();
            if(typeof func == 'function')func(this);
        });
    }
}

if(typeof hrefAction != "function"){
    function hrefAction(href, func){
        if(!href) return;
        if(typeof href == "object"){
            jQuery.each(href, function (hrefKey, hrefFunc) {
                jQuery('[href="#' + hrefKey + '"]').off('click');
                jQuery('[href="#' + hrefKey + '"]').on('click', function (e) {
                    e.preventDefault();
                    hrefFunc(this);
                });
            });
        }else{
            jQuery('[href="#' + href + '"]').off('click');
            jQuery('[href="#' + href + '"]').on('click', function (e) {
                e.preventDefault();
                if(typeof func == 'function')func(this);
            });
        }
    }
}

if(typeof dataAction != "function"){
    function dataAction(data, func){
        if(!data) return;
        if(typeof data == "object"){
            jQuery.each(data, function (dataKey, dataFunc) {
                jQuery('[data-action="' + dataKey + '"]').off('click');
                jQuery('[data-action="' + dataKey + '"]').on('click', function (e) {
                    e.preventDefault();
                    dataFunc(this);
                });
            });
        }else{
            jQuery('[data-action="' + data + '"]').off('click');
            jQuery('[data-action="' + data + '"]').on('click', function (e) {
                e.preventDefault();
                if(typeof func == 'function')func(this);
            });
        }
    }
}

if(typeof initAction != "function"){
    function initAction(init, func){
        if(!init)return;
        if(typeof init == "object"){
            jQuery('[init-action]').each(function(){
                var initKey = jQuery(this).attr('init-action');
                if(typeof init[initKey] == 'function'){
                    return init[initKey](this);
                }
            });
        }else{
            if(typeof func == 'function')return func(jQuery('[init-action='+init+']'));
        }
    }
}

if(typeof setAction != "function"){
    function setAction(init, func){
        if(!init)return;
        if(typeof init == "object"){
            var res = [];
            jQuery('[set-action]').each(function(){
                var initKey = jQuery(this).attr('set-action');
                if(typeof init[initKey] == 'function'){
                    res[initKey] = init[initKey](this);
                }
            });
            return res;
        }else{
            if(typeof func == 'function')return func(jQuery('[set-action='+init+']'));
        }
    }
}

if(typeof funcAction != "function"){
    function funcAction(funcs, func){
        if(!funcs) return;
        if(typeof funcs == "object"){
            jQuery.each(funcs, function (funcName, funcAction) {
                if(typeof funcName != "function"){
                    window[funcName] = funcAction;
                }
            });
        }else{
            if(typeof funcs != "function"){
                window[funcs] = func;
            }
        }
    }
}

if(typeof autoAction != "function"){
    function autoAction(funcs, func){
        if(!funcs) return;
        if(typeof funcs == "object"){
            jQuery.each(funcs, function (funcName, funcAction) {
                if(typeof funcName != "function"){
                    funcAction();
                }
            });
        }else{
            if(typeof funcs != "function"){
                func();
            }
        }
    }
}

//######################################################################################################################
//##
//## >> Function :
//##
//######################################################################################################################

if(typeof remainTime != "function") {
    
    //### 남은 시간 카운터
    function remainTime(milliseconds) {
        var now = new Date();
        var gap = Math.round((milliseconds - now.getTime()) / 1000);

        var D = Math.floor(gap / 86400);
        var H = Math.floor((gap - D * 86400) / 3600 % 3600);
        var M = Math.floor((gap - H * 3600) / 60 % 60);
        var S = Math.floor((gap - M * 60) % 60);
        var res = "";
        if(D > 0)res += (D<10 ? "0"+D  : D)+"일";
        if(H > 0)res += (H<10 ? "0"+H  : H)+"시간";
        res += (M<10 ? "0"+M  : M)+"분";
        res += (S<10 ? "0"+S  : S)+"초";
        return res;
    }
}

if(typeof laterTime != "function") {

    //### 지난 시간 카운터
    function laterTime(milliseconds) {
        var now = new Date();
        var gap = Math.round((now.getTime() - milliseconds) / 1000);

        var D = Math.floor(gap / 86400);
        var H = Math.floor((gap - D * 86400) / 3600 % 3600);
        var M = Math.floor((gap - H * 3600) / 60 % 60);
        var S = Math.floor((gap - M * 60) % 60);
        var res = "";
        if(D > 0)res += (D<10 ? "0"+D  : D)+"일";
        if(H > 0)res += (H<10 ? "0"+H  : H)+"시간";
        res += (M<10 ? "0"+M  : M)+"분";
        res += (S<10 ? "0"+S  : S)+"초";
        return res;
    }
}

if(typeof strTime != "function") {

    //### 입력한 시간 표시
    function strTime(milliseconds) {
        var gap = Math.round(milliseconds / 1000);

        var D = Math.floor(gap / 86400);
        var H = Math.floor((gap - D * 86400) / 3600 % 3600);
        var M = Math.floor((gap - H * 3600) / 60 % 60);
        var S = Math.floor((gap - M * 60) % 60);
        var res = "";
        if(D > 0)res += (D<10 ? "0"+D  : D)+"일";
        if(H > 0)res += (H<10 ? "0"+H  : H)+"시간";
        res += (M<10 ? "0"+M  : M)+"분";
        res += (S<10 ? "0"+S  : S)+"초";
        return res;
    }
}


if(typeof getParam != "function") {

    //### Url Parameter 가져오기
    function getParam(key) {
        var _parammap = {};
        document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
            function decode(s) {
                return decodeURIComponent(s.split("+").join(" "));
            }

            _parammap[decode(arguments[1])] = decode(arguments[2]);
        });

        return _parammap[key];
    }
}

if(typeof removeTags != "function"){

    //### Content Tag 제거
    function removeTags(text){
        text = text.replace(/<br\/>/ig, "\n");
        text = text.replace(/<(\/)?([a-zA-Z]*)(\s[a-zA-Z]*=[^>]*)?(\s)*(\/)?>/ig, "");
        return text;
    }
}

if(typeof removeAllTags != "function"){

    //### Content 모든 Tag 제거
    function removeAllTags(text){
        text = text.replace(/<(\/)?([a-zA-Z]*)(\s[a-zA-Z]*=[^>]*)?(\s)*(\/)?>/ig, "");
        return text;
    }
}

if(typeof fileSize != "function"){

    //### Helper function that formats the file sizes
    function fileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }
        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }
        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }
        return (bytes / 1000).toFixed(2) + ' KB';
    }

}

if(typeof isEmpty != "function") {
    function isEmpty(value) {
        var isEmptyObject = function (a) {
            if (typeof a.length === 'undefined') { // it's an Object, not an Array
                var hasNonempty = Object.keys(a).some(function nonEmpty(element) {
                    return !isEmpty(a[element]);
                });
                return hasNonempty ? false : isEmptyObject(Object.keys(a));
            }

            return !a.some(function nonEmpty(element) { // check if array is really not empty as JS thinks
                return !isEmpty(element); // at least one element should be non-empty
            });
        };
        return (
            value == false
            || typeof value === 'undefined'
            || value == null
            || (typeof value === 'object' && isEmptyObject(value))
        );
    }
}

if(typeof number_format != "function") {
    function number_format (number, decimals, decPoint, thousandsSep) {
        // eslint-disable-line camelcase
        //  discuss at: http://locutus.io/php/number_format/
        // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // improved by: davook
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Theriault (https://github.com/Theriault)
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // bugfixed by: Michael White (http://getsprink.com)
        // bugfixed by: Benjamin Lupton
        // bugfixed by: Allan Jensen (http://www.winternet.no)
        // bugfixed by: Howard Yeend
        // bugfixed by: Diogo Resende
        // bugfixed by: Rival
        // bugfixed by: Brett Zamir (http://brett-zamir.me)
        //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        //  revised by: Luke Smith (http://lucassmith.name)
        //    input by: Kheang Hok Chin (http://www.distantia.ca/)
        //    input by: Jay Klehr
        //    input by: Amir Habibi (http://www.residence-mixte.com/)
        //    input by: Amirouche
        //   example 1: number_format(1234.56)
        //   returns 1: '1,235'
        //   example 2: number_format(1234.56, 2, ',', ' ')
        //   returns 2: '1 234,56'
        //   example 3: number_format(1234.5678, 2, '.', '')
        //   returns 3: '1234.57'
        //   example 4: number_format(67, 2, ',', '.')
        //   returns 4: '67,00'
        //   example 5: number_format(1000)
        //   returns 5: '1,000'
        //   example 6: number_format(67.311, 2)
        //   returns 6: '67.31'
        //   example 7: number_format(1000.55, 1)
        //   returns 7: '1,000.6'
        //   example 8: number_format(67000, 5, ',', '.')
        //   returns 8: '67.000,00000'
        //   example 9: number_format(0.9, 0)
        //   returns 9: '1'
        //  example 10: number_format('1.20', 2)
        //  returns 10: '1.20'
        //  example 11: number_format('1.20', 4)
        //  returns 11: '1.2000'
        //  example 12: number_format('1.2000', 3)
        //  returns 12: '1.200'
        //  example 13: number_format('1 000,50', 2, '.', ' ')
        //  returns 13: '100 050.00'
        //  example 14: number_format(1e-8, 8, '.', '')
        //  returns 14: '0.00000001'

        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''

        var toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec)
            return '' + (Math.round(n * k) / k)
                .toFixed(prec)
        }

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || ''
            s[1] += new Array(prec - s[1].length + 1).join('0')
        }

        return s.join(dec)
    }
}

if(typeof number_format != "Toast") {
    /**
     * Toast Message
     * @param msg : 메세지
     * @constructor
     */
    var Toast = function (msg) {
        // Get the snackbar DIV
        var x = $("#snackbar");

        // Add the "show" class to DIV
        x.addClass("show");
        x.text(msg);

        // After 3 seconds, remove the show class from DIV
        setTimeout(function () {
            x.removeClass("show");
        }, 2000);
    }
}


//######################################################################################################################
//##
//## >> Function : jQuery
//##
//######################################################################################################################
if(typeof jQuery == 'function'){
    $.put = function(url, data, callback, type){

        if ( $.isFunction(data) ){
            type = type || callback,
                callback = data,
                data = {}
        }

        return $.ajax({
            url: url,
            type: 'PUT',
            success: callback,
            data: data,
            contentType: type
        });
    }

    $.delete = function(url, data, callback, type){

        if ( $.isFunction(data) ){
            type = type || callback,
                callback = data,
                data = {}
        }

        return $.ajax({
            url: url,
            type: 'DELETE',
            success: callback,
            data: data,
            contentType: type
        });
    }

    $.isEmpty = function(selector){
        var objElement = $(selector);
        return objElement.length > 0 ;
    }

    /**
     * 전체 스크롤 마지막 까지 내렸을 경우 이벤트 발생
     * -----------------------------------------------------------------------------------------------------------------
     * @param callback
     */
    $.endOfScroll = function(callback){
        if(!callback){
            $(window).off("scroll");
        } else {
            $(window).on("scroll", function(e){
                var clientHeight = document.body.clientHeight || document.documentElement.clientHeight;
                var scrollHeight = document.body.scrollHeight || document.documentElement.scrollHeight;
                var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;

                if( scrollTop + clientHeight >= scrollHeight ) {
                    if($.isFunction(callback)){
                        callback.call($(this));
                    }
                }
            });
        }
    }

    /**
     * Element 스크롤 마지막 까지 내렸을 경우 이벤트 발생
     * -----------------------------------------------------------------------------------------------------------------
     * @param callback
     */
    $.fn.endOfScroll = function(callback){
        return this.each(function(){
            if(!callback){
                $(this).off("scroll");
            } else {
                $(this).on("scroll", function(){
                    if( $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight ) {
                        if($.isFunction(callback)){
                            callback.call($(this));
                        }
                    }
                });
            }
        });
    };

}



//######################################################################################################################
//##
//## >> Function : Polyfill
//##
//######################################################################################################################

if (typeof String.prototype.trim === 'undefined') {
    String.prototype.trim = function () {
        return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
    };
}

if (typeof String.prototype.format === 'undefined') {
    String.prototype.format = function () {
        var a = this;
        for (var k in arguments) {
            a = a.replace("{" + k + "}", arguments[k])
        }
        return a;
    }
}

if (typeof String.prototype.contains === 'undefined') {
    String.prototype.contains = function(it) { return this.indexOf(it) != -1; };
}

if (typeof String.prototype.toNumberString === 'undefined') {
    String.prototype.toNumberString = function () {
        var a = this;
        var val = parseFloat(a);
        return isNaN(val) || val == 0?"0":a.toString();
    }
}

if (typeof Number.prototype.toNumberString === 'undefined') {
    Number.prototype.toNumberString = function () {
        var a = this;
        return String(a);
    }
}
