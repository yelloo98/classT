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

/**
 * 강사 찾기 목록에서 강사 선택시 추천인으로 등록
 **/
var setRecommendTeacher = function(id, name) {
    $("input[name=recommend_user_id]").val(id);
    $("input[name=mentor_name]").val(name);
    $("#btn_search_close").click();
}

/**
 * 멘티 목록
 * @param id : users_teacher_id
 * */
var getMenteeList = function(id) {
    var resHtml = "";

    $.ajax({
        url: '/api/mentee/'+id,
        type: "GET",
        dataType: "json",
        async : false,
        success: function (data) {
            $.each(data, function(index, itemData) {
                resHtml +="<tr><td>" + itemData.id + "</td><td>" + itemData.name + "</td><td>" + itemData.email + "</td><td>" + "" + "</td></tr>";
            });
            $("#resultMenteeList").html("");
            $("#resultMenteeList").append(resHtml);

            $("#modal-mentee").modal('show');
        },
        error: function (data, textStatus, errorThrown) {
            console.log(textStatus);

        }
    });
}

/**
 * 추천인 강사 찾기
 * */
var searchTeacher = function() {
    var resHtml = "";

    $.ajax({
        url: "/api/searchTeacher/"+$("input[name=id]").val()+"/"+$("select[name=searchType]").val()+"/"+$("input[name=searchWord]").val()+"/true",
        type: "GET",
        dataType: "json",
        async : false,
        success: function (data) {
            $.each(data, function(index, itemData) {
                resHtml +="<tr><td><a href='javascript:setRecommendTeacher("+itemData.id + ", \""+ itemData.name+"\");'>" + itemData.name + "</a></td><td>" + itemData.email + "</td><td>" + lastSubString(itemData.phone,4) + "</td></tr>";
            });
            $("#resultSearchTeacher").html(resHtml);
        },
        error: function (data, textStatus, errorThrown) {
            console.log(textStatus);
        }
    });
}

/**
 * 선택한 프리미엄 의뢰의 추천강사로 등록시키기
 **/
var savePremiumRequest = function() {
    var request_id = $("input[name=choice_premium_id]:checked").val(); //의뢰 아이디
    var teacher_id = $("input[name=id]").val(); //강사아이디

    if(request_id!="" && request_id!=undefined) {
        if(confirm("해당 프리미엄 의뢰로 연결하시겠습니까?") == true) {
            $.ajax({
                url: "/admin/member/teacher/ajax/contactRequest/"+teacher_id+"/"+request_id,
                type: "get",
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    alert("프리미엄 의뢰 연결에 성공하였습니다.")
                    $("#btn_search").click();
                    $("#btn_close_contact").click();
                },
                error: function (data, textStatus, errorThrown) {
                    console.log('error');
                    console.log(data);

                }
            });

        }else {
            return false;
        }

    } else {
        alert("프리미엄 의뢰를 선택하세요.");
        return false;
    }

}