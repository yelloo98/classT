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
//## >> Function  :
//##
//######################################################################################################################
(function( factory ) {
    if ( typeof define === "function" && define.amd ) {
        define( ["jquery"], factory );
    } else if (typeof module === "object" && module.exports) {
        module.exports = factory( require( "jquery" ) );
    } else {
        factory( jQuery );
    }
}(function( $ ) {"use strict";

    //### Object
    var action = {
        href: {},
        init: {},
        change:{},
        auto:{
        },
    }

    //### Ready
    $(document).ready(function(){
        hrefAction(action.href);
        initAction(action.init);
        autoAction(action.auto);

        //# 분야 선택시
        $(".choiceLecture").click(function () {
            setChoiceLecture($(this));
        });
    });

    //### Change
    $(document).on('change', '[set-change]', function() {
        var changeName = $(this).attr('set-change');
        action.change[changeName](this);
    });

    //강사 스케줄 로드
    $(".datepicker-inline").remove();

    var date = new Date();
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear();
    console.log(new Date(y, m, 18));
    console.log(new Date('2018-08-13 00:00:00'));

    //# 켈린더 init
    CommCalendar.init("#calendar", null);

    //# 스케쥴 데이터 조회
    $.ajax({
        url: '/api/schedule/'+$("input[name=id]").val(),
        type: 'GET',
        success: function(result){
            console.log(result);
            CommCalendar.setData("#calendar", result, false);
        }
    });

    $("#submit_btn").click(function() { //수정 버튼 클릭시
        $("#form").submit();
    });

    $(".all").click(function(){ //출강단체, 출강업종, 대상직급 전체 checkbox선택시
        $(".check_"+$(this).attr("id").replace('_all', "")).prop("checked", $(this).prop("checked"));
    });

    $("#place_bargain").click(function () { //출강지역 협의가능 선택시 선택되어져 있는 체크박스 해제 & 비활성화
        if($("#place_bargain").prop("checked") == true) {
            $(".check_area").prop("checked", false);
            $(".check_area").attr('disabled', true);
        } else {
            $(".check_area").attr('disabled', false);
        }
    });

}));