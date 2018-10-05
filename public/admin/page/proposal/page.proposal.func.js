/** :: EnjoyWorks ::
 ***********************************************************************************************************************
 * @source  :
 * @project : ClassEum
 *----------------------------------------------------------------------------------------------------------------------
 * VER  DATE           AUTHOR          DESCRIPTION
 * ---  -------------  --------------  ---------------------------------------------------------------------------------
 * 1.0  2018/08/13     EnjoyWorks
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

/**
 * 클래스이음 전달 제안서 삭제하기
 * @param id : request_proposal 고유 아이디
 * */
var delProposalFile = function(id) {
    if(confirm("등록된 제안서를 삭제 하시겠습니까?") == true) {

        $.ajax({
            url: "/admin/proposal/ajax/delProposalFile/"+id,
            type: "GET",
            dataType: "json",
            async : false,
            success: function (data) {
                console.log(data);
                if(data.status = 200) {
                    alert(data.msg);
                    $(".proposal-download").css("display", "none");
                    $(".proposal-upload").css("display", "block");
                    $(".btn-save").css("display", "block");
                    return false;
                } else {
                    alert("failed");
                }
            },
            error: function (data, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(data);
                alert("삭제 실패!");
                return false;
            }
        });

    }
}