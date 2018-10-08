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
        href: {
            //# 강의분야 등록 or 수정
            mid_lecture_upload : function(t){
                $("input[name=m_id]").val("");
                $("input[name=m_title]").val("");

                //분류 select box 비활성화 해제
                $("#m_large_cate").attr("disabled", false);

                setLargeCateLIst("m_large_cate", "");

                $("#modal-lecture-mid").modal('show');
            },
            small_lecture_upload : function (t) {
                $("input[name=s_id]").val("");
                $("input[name=s_title]").val("");

                //분류 select box 비활성화 해제
                $("#s_large_cate").attr("disabled", false);
                $("#s_mid_cate").attr("disabled", false);

                //대분류 선택한 후 중분류 선택할 수 있도록 중분류 값 없애기
                $("#s_mid_cate").empty();
                setLargeCateLIst("s_large_cate", "");

                $("#modal-lecture-small").modal('show');
            }

        },
        init: {},
        change:{},
        auto:{
            ready: function(){

            }
        },
    }

    //### Ready
    $(document).ready(function(){
        hrefAction(action.href);
        initAction(action.init);
        autoAction(action.auto);

        $("#s_large_cate").change(function(){
            setMidCateList($("#s_large_cate :checked").val(), "");
        });
    });

    //### Change
    $(document).on('change', '[set-change]', function() {
        var changeName = $(this).attr('set-change');
        action.change[changeName](this);
    });


}));
