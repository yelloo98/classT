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
            ready: function(){

            }
        },
    }

    //### Ready
    $(document).ready(function(){
        hrefAction(action.href);
        initAction(action.init);
        autoAction(action.auto);
    });

    //### Change
    $(document).on('change', '[set-change]', function() {
        var changeName = $(this).attr('set-change');
        action.change[changeName](this);
    });


}));
