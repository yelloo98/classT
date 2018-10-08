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
if (typeof String.prototype.format === 'undefined') {
    String.prototype.format = function () {
        var a = this;
        for (var k in arguments) {
            a = a.replace("{" + k + "}", arguments[k]);
        }
        return a;
    };
}

/**
 * 해당 강의 분류 삭제
 * */
var delLecture = function(id) {
    if(confirm("해당 강의 분류를 삭제하시겠습니까?") == true) {
        $.ajax({
            url: "/admin/lecture/ajax/delLecture/"+id,
            type: "get",
            dataType: "json",
            success: function (data) {
                console.log(data);
                alert("삭제 완료!");
            },
            error: function (data, textStatus, errorThrown) {
                console.log('error');
                console.log(data);

            }
        });

        $("#btn_search").click();

    }
}

/**
 * 강의 분야 수정 버튼
 * */
var lectureUpdate = function(id) {

    $.ajax({
        url : "/admin/lecture/ajax/getLecture/"+id,
        type : "get",
        dataType : "json",
        asyn : false,
        success : function (data) {
            console.log(data);
            if(data.s_cat != "") { //소분류 수정인 경우
                $("input[name=s_id]").val(data.id);
                $("input[name=s_title]").val(data.s_cat);

                setLargeCateLIst("s_large_cate", data.large_cate);
                setMidCateList(data.large_cate, data.mid_code);

                $("#s_large_cate").attr("disabled", true);
                $("#s_mid_cate").attr("disabled", true);

                $("#modal-lecture-small").modal('show');
            } else { //중분류 수정인 경우
                $("input[name=m_id]").val(data.id);
                $("input[name=m_title]").val(data.mid_cat);

                setLargeCateLIst("m_large_cate", data.large_cate);

                $("#m_large_cate").attr("disabled", true);

                $("#modal-lecture-mid").modal('show');
            }
        },
        error : function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);
        }
    });

}

/**
 * 강의 분야 저장
 * @param lectureType : 분야 형태 - 소분류인지 중분류인지
 * */
var saveLecture = function(lectureType) {
    if(confirm("강의분야를 등록하시겠습니까?") == true) {

        var token = $("input[name=_token]").val();
        var id = $("input[name="+lectureType+"_id]").val();
        var title = $("input[name="+lectureType+"_title]").val();
        var large_cate = $("select[name="+lectureType+"_large_cate] :checked").val(); //대분류
        var mid_cate = $("select[name=s_mid_cate] :checked").val(); //중분류 코드값 - 소분류 등록인 경우에만 사용
console.log(token + ' // ' + title + " // " + id + " // " + large_cate + "  // " + mid_cate + " // " + lectureType);

        $.ajax({
            url: "/admin/lecture/ajax/saveLecture",
            type: "post",
            dataType: "json",
            async : false,
            data : {
                id : id,
                title : title,
                large_cate : large_cate,
                mid_cate : mid_cate,
                lectureType : lectureType,
                _token : token
            },
            success: function (data) {
                console.log(data);
                alert(data.msg);
                $("#btn_search").click(); //테이블 리프레시
                $(".close").click(); //모달창 닫기
            },
            error: function (data, textStatus, errorThrown) {
                console.log('error');
                console.log(data);
            }
        });
    }
}

/**
 * 강의 분야 대분류 셋팅
* */
var setLargeCateLIst = function (objName, selLargeCate) {

    $("#"+objName).empty();

    $.ajax({
        url: "/admin/lecture/ajax/getLargeCate",
        type: "get",
        dataType: "json",
        asyn : false,
        processData : false,
        contentType : false,
        success: function (data) {
            console.log(data);
            var option = "";
            var selected = "";
            $.each(data, function(index, itemData) {
                selected = (itemData.large_cate == selLargeCate) ? 'selected' : '';
                option = $("<option value='"+itemData.large_cate+"' "+selected+">"+itemData.large_title+"</option>");
                $("#"+objName).append(option);
            });
        },
        error: function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);
        }
    });
}

/**
 * 소분류시 - 선택한 대분류의 중분류 목록 셋팅
 * @param large_cate : 관리자가 선택한 대분류 값
 * @param selMidCate : 수정시 들어가져 있는 중분류 값
 **/
var setMidCateList = function (large_cate, selMidCate) {

    $("#s_mid_cate").empty();
    $.ajax({
        url: "/admin/lecture/ajax/getMidCate/"+large_cate,
        type: "get",
        dataType: "json",
        async : false,
        processData : false,
        contentType : false,
        success: function (data) {
            console.log(data);
            var option = "";
            var selected = "";
            $.each(data, function(index, itemData) {
                selected = (itemData.code == selMidCate) ? 'selected' : '';
                option = $("<option value='"+itemData.code+"' "+selected+">"+itemData.title+"</option>");
                $("#s_mid_cate").append(option);
            });
        },
        error: function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);
        }
    });
}