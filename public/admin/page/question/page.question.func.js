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
 * 수강생 평가 등록
 * @param evaluate_type : 질문형태 - one(일회성), basic(기본)
 **/
var questionUpload = function (evaluate_type) {
    $("input[name=id]").val("");
    $("input[name=question]").val("");
    $("input[name=answer_score]").val("");
    $("select[name=answer_type]").val("NUM");
    $("input[name=question_topic]").val('');

    $("input[name=question_topic]").attr('disabled', ((evaluate_type == 'one') ? true : false));
    $("input[name=evaluate_type]").val(evaluate_type);

    $("#modal-question").modal('show');
}

/**
 * 수강생 평가항목 삭제
 * */
var delQuestion = function(id) {
    if(confirm("해당 평가항목을 삭제하시겠습니까?") == true) {
        $.ajax({
            url: "/admin/question/ajax/delQuestion/"+id,
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
 * 수강생 평가항목 수정 버튼
 * @param id
 * @param evaluate_type : 평가 형식 - one(일회성), basic(기본)
 * */
var questionUpdate = function(id, evaluate_type) {

    $.ajax({
        url : "/admin/question/ajax/getQuestion/"+id,
        type : "get",
        dataType : "json",
        success : function (data) {
            console.log(data);
            var data = data.data;
            $("input[name=id]").val(id);
            $("input[name=question]").val(data.question);
            $("select[name=answer_type]").val(data.answer_type);
            $("input[name=answer_score]").val(data.answer_score);
            $("input[name=question_topic]").val(data.question_topic);

            $("input[name=question_topic]").attr('disabled', ((evaluate_type == 'one') ? true : false));
            $("input[name=evaluate_type]").val(evaluate_type);

            $("#modal-question").modal('show');
        },
        error : function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);
        }
    });


}

/**
 * 수강생 평가항목 insert or update
 */
var saveQuestion = function() {
    if(confirm("평가항목을 등록하시겠습니까?") == true) {
        var params = $("#uploadForm").serialize();

        console.log(params);

        $.ajax({
            url: "/admin/question/ajax/saveQuestion",
            type: "post",
            dataType: "json",
            data : params,
            success: function (data) {
                console.log(data);
                alert("등록 완료!");
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