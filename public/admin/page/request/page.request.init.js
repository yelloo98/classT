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

        setRecommendTeacherList('premium'); //추천강사 목록 뿌리기
        // setRefuseTeacherList(); //거절강사 목록 뿌리기


        //Date picker
        $('input[name=class_start_dt]').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('input[name=class_end_dt]').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('input[name=class_start_time]').timepicker({
            showMeridian: false,
            showInputs: false
        });

        $("#class_start_dt").change(function() { //시작일 선택시
            if($("#class_end_dt").val() == "") {
                $("#class_end_dt").val($("#class_start_dt").val())
            }

            if($("#class_start_dt").val() > $("#class_end_dt").val())  {
                alert("시작일은 종료일보다 클 수 없습니다.");
                $("#class_start_dt").val("");
            }
        });

        $("#class_end_dt").change(function () {
           if($("#class_start_dt").val() > $("#class_end_dt").val())  {
               alert("시작일은 종료일보다 클 수 없습니다.");
               $("#class_end_dt").val("");
           }
        });

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

    $("#submit_btn").click(function() {
        $("#form").submit();
    });

}));