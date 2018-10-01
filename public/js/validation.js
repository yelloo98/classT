/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  :
 * @project : Pro_DivingMall
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2017/12/29     Name_0070
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * Project Description
 * Copyright(c) 2015 enjoyworks Co., Ltd. All rights reserved.
 **********************************************************************************************************************/

"use strict";
//######################################################################################################################
//##
//## >> Function : Validation
//##
//######################################################################################################################
(function( $ ) {
    //### ADD Method
    $.validator.addMethod( "ko", function(value, element){
        return this.optional( element ) || /^[ㄱ-ㅎ|ㅏ-ㅣ|가-힣| ]*$/.test(value);
    }, "한글만 입력 가능 합니다.");

    $.validator.addMethod( "en", function(value, element){
        return this.optional( element ) || /^[a-z|A-Z| ]*$/.test(value);
    }, "영어만 입력 가능 합니다.");

    $.validator.addMethod( "password", function(value, element){
        return this.optional( element ) || /^[a-zA-Z0-9!@#$]*$/.test(value);
    }, "영문 대소문자,숫자,!@#$만 입력 가능 합니다.");

    $.validator.addMethod( "phone", function(value, element){
        return this.optional( element ) || /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/.test(value);
    }, "잘못된 휴대폰 번호 입니다.");

    $.validator.addMethod( "hyphenNum", function(value, element){
        return this.optional( element ) || /^[0-9|-]*$/.test(value);
    }, "숫자 및 '-' 만 입력 가능 합니다.");

    $.validator.addMethod( "googleMap", function(value, element){
        return this.optional( element ) || /^https:\/\/www.google.co.kr\/maps\/place\/.*@([0-9.]*),([0-9.]*).*$/.test(value);
    }, "잘못된 Google 공유 링크 입니다.");

    $.validator.addMethod( "summernote", function(value, element){
        return value.replace(/<(\/)?([a-zA-Z]*)(\s[a-zA-Z]*=[^>]*)?(\s)*(\/)?>/ig, "").trim().length > 0;
    }, "내용을 입력해 주세요");

    $.validator.addMethod( "notEqual", function(value, element, params){
        return this.optional( element ) || value != params;
    }, "필수 항목입니다.");

    $.validator.addMethod( "equal", function(value, element, params){
        return this.optional( element ) || value == params;
    }, "필수 항목입니다.");

    $.validator.addMethod( "caseTo", function(value, element, params){
        var res = false;
        if(typeof params == 'string'){
            if($(params).is(":blank")){
                res = $(element).is(":filled");
            }else{
                res = true;
            }
        }else if(typeof params == 'object'){
            //### TODO 추후 추가
        }
        return res;
    }, "필수 항목입니다.");

    $.validator.addMethod( "notEqualTo", function(value, element, params){
        var res = false;
        if(typeof params == 'string'){
            var tempStr =  $(params).val();
            var targetStr = $(element).val();
            if(typeof targetStr != 'undefined' && tempStr.length > 0 &&
                typeof targetStr != 'undefined' && targetStr.length > 0){
                return targetStr != tempStr;
            }else{
                return true;
            }
        }else if(typeof params == 'object'){
            //### TODO 추후 추가
        }
        return res;
    }, "동일한 값이 존재 합니다.");

    $.validator.addMethod( "existPassword", function(value, element, params){
        var isSuccess = false;
        var token = $('input[name=_token]').val();
        $.ajax({
            url: "/mypage/existing",
            type: "POST",
            data: {_token:token, password:value},
            async: false,
            success: function(result) {
                isSuccess = result.status == 'SUCCESS';
            },
        });
        return isSuccess;
    },"동일한 값이 존재 합니다.");

    //### Message Set
    $.extend( $.validator.messages, {
        required: "필수 항목입니다.",
        remote: "항목을 수정하세요.",
        email: "유효하지 않은 E-Mail주소입니다.",
        url: "유효하지 않은 URL입니다.",
        date: "올바른 날짜를 입력하세요.",
        dateISO: "올바른 날짜(ISO)를 입력하세요.",
        number: "유효한 숫자가 아닙니다.",
        digits: "숫자만 입력 가능합니다.",
        creditcard: "신용카드 번호가 바르지 않습니다.",
        equalTo: "같은 값을 다시 입력하세요.",
        extension: "올바른 확장자가 아닙니다.",
        maxlength: $.validator.format( "{0}자를 넘을 수 없습니다. " ),
        minlength: $.validator.format( "{0}자 이상 입력하세요." ),
        rangelength: $.validator.format( "문자 길이가 {0} 에서 {1} 사이의 값을 입력하세요." ),
        range: $.validator.format( "{0} 에서 {1} 사이의 값을 입력하세요." ),
        max: $.validator.format( "{0} 이하의 값을 입력하세요." ),
        min: $.validator.format( "{0} 이상의 값을 입력하세요." ),
    } );
})(jQuery);