
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
(function($){
    $(document).ready(function() {
        /**
         * Action : Target 영역 슬라이드 Toggle
         * -------------------------------------------------------------------------------------------------------------
         * Attr_name : action-slide
         * Attr_value : Target Selector Name
         */
        $('[action-slide]').on('click', function () {
            var element = $(this);
            var obj, target = element.attr('action-slide');
            if ($.isEmpty('#{0}'.format(target))) obj = $('#{0}'.format(target));
            else if ($.isEmpty('.{0}'.format(target))) obj = $('.{0}'.format(target));
            else if ($.isEmpty('[{0}]'.format(target))) obj = $('[{0}]'.format(target));
            else obj = $(target);
            $(obj).slideToggle(500, function () {
                element.children('i').toggleClass('fa-angle-double-down');
                element.children('i').toggleClass('fa-angle-double-up');
                $('[data-widget=collapse]').click();
            });
        });
    });
})(window.jQuery);