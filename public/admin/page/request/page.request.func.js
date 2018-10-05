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
 * 강사 찾기 리스트 출력
 * */
var getFindTeacherList = function () {
    var request_id = $("input[name=id]").val();
    var resHtml = "";

    $.ajax({
        url: "/admin/request/ajax/searchTeacher/"+request_id,
        type: "GET",
        dataType: "json",
        async : false,
        success: function (data) {
            console.log('success');
            console.log(data);
            $.each(data, function(index, itemData) {
                resHtml +="<tr><td>" + itemData.id + "</td><td>" + itemData.name + "</td><td>" + itemData.email + "</td><td>" + lastSubString(itemData.phone, 4) + "</td><td><button onclick='setRecommendTeacher("+itemData.id+")' class='btn'>등록</button></td></td></tr>";
            });
            $("#resultSearchTeacher").html("");
            $("#resultSearchTeacher").append(resHtml);
        },
        error: function (data, textStatus, errorThrown) {
            console.log(textStatus);
        }
    });

}

/**
 * 강사찾기 모달 띄우기
 * 추천강사 수가 3명 미만인지 확인하기 ==> 제한 없음
 * */
var mSearchTeacher = function() {
    /*if($(".recommend").length >= 3) {
        console.log($(".recommend").length);
        alert("추천 강사 찾기는 3명까지만 가능합니다.");
        return false;
    } else {
        getFindTeacherList();
        $("#modal-teacher").modal('show');
    }*/

    getFindTeacherList();
    $("#modal-teacher").modal('show');
}

/**
 * 선택한 추천 강사 등록
 * @param teacherId : 강사아이디
 **/
var setRecommendTeacher = function(teacherId) {
    var request_id = $("input[name=id]").val(); //프리미엄 요청 테이블 아이디
    var _token = $("input[name=_token]").val();
    if(confirm("해당 강사를 추천강사로 등록하시겠습니까?") == true) {
        $.ajax({
            url: "/admin/request/ajax/insertRecommendTeacher",
            type: "POST",
            dataType: "json",
            data : {
                users_teacher_id : teacherId,
                class_request_id : request_id,
                _token : _token
            },
            async : false,
            success: function (data) {
                console.log(data);
                if(data.status = 200) {
                    alert("추천강사 등록 성공");
                    setRecommendTeacherList('premium');
                } else {
                    alert("failed");
                }
            },
            error: function (data, textStatus, errorThrown) {
                alert("추천강사 등록 실패");
                return false;
            }
        });
    }

    $("#btn_close").click();
}


//1:의뢰요청|2:제안서요청|3:제안서리뷰|4:제안서발송|5:강의요청|6:출강요청|7:강의확정

/**
 * 추천강사 삭제하기
 * soft delete
 * @param recommend_id : 추천강사 테이블 고유 아이디
 * */
var delRecommendTeacher = function(recommend_id) {
    if(confirm("해당 추천강사를 삭제하시겠습니까?") == true) {
        $.ajax({
            url: "/admin/request/ajax/delRecommendTeacher/"+recommend_id,
            type: "GET",
            dataType: "json",
            async : false,
            success: function (data) {
                console.log(data);
                if(data.status = 200) {
                    alert("추천강사 삭제 성공!");
                    setRecommendTeacherList('premium'); //추천강사 목록 함수 재호출
                } else {
                    alert("failed");
                }
            },
            error: function (data, textStatus, errorThrown) {
                alert("삭제 실패!");
                return false;
            }
        });
    }
}