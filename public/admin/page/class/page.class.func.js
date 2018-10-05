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


/**
 * 강의하는 장소의 지역 코드 얻어오기
 * @param title : 지역명
 **/
var getCateAreaCode = function(title) {
    var areaCode = "";
    $.ajax({
        url: "/api/getCateArea/title/"+title,
        type: "get",
        dataType: "json",
        asyn : false,
        success: function (data) {
            console.log(data);
            areaCode = data.code;
        },
        error: function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);

        }
    });

    return areaCode;
}