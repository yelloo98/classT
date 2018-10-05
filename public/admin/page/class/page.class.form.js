/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  :
 * @project : ClassEum
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/10/04
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

    var validate = {
        //### Validate: 사용자 정보 등록
        check_form: function (f) {
            return f.validate({
                wrapper: "div",
                rules: {},
                messages: {},
                submitHandler: function (form) {
                    return true;
                }
            });
        },
        input_mark: function () {
            $('input[name=class_time]').mask('XX', markOptions.hyphenNum);
        }
    };

    //### Ready
    $(document).ready(function(){
        window.validator = validate.check_form($('#form'));
        validate.input_mark();
    });

    //### Window Set Variable
    //if(window.enc && window.enc.app){window.enc.app.page = page;}
}));