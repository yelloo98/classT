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
 * 강의 분야 검색하기 목록 출력 함수
 * 분야명 있는 경우에만 검색 가능하도록 처리
 * 이미 선택한 분야는 검색되지 않도록 처리
 * */
var searchLecture = function() {
    var searchWord = $("input[name=searchLectureWord]").val(); //검색 분야명
    var notInCode = $("#choiceLectureCodeList").val(); //이미 선택하여 검색목록에서 빠져야하는 분야 코드

    if(searchWord == "") {
        alert("분야명을 입력해주세요.");
        return false;
    }
    $.ajax({
        url: "/api/getLecture/"+searchWord+"/"+notInCode,
        type: "get",
        dataType: "json",
        success: function (data) {
            console.log(data);
            var rHtml = '';

            $.each(data, function(index, itemData) {
                rHtml+= '<span class="search-lecture" id="low-lecture-'+itemData.code+'"><input type="text" value="'+itemData.title+'" style="width:15%; margin-bottom:3px;" readonly><span class="search-lecture-choice" onclick="choiceLeture(\''+itemData.code+'\')">선택</span></span>';
            });

            $("#resultSearchLectureList").html(rHtml);
        },
        error: function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);

        }
    });

}

/**
 * 선택한 분야 버튼 색깔변경
 * @param obj : object자체 넘어옴
 * */
var setChoiceLecture = function(obj) {

    console.log("setChoiceLecture");
    var choice = obj.attr("data-choice");
    var code = (obj.data('code'));
    var title = (obj.data('title'));

    var choiceLectureCode = $("#choiceLectureCodeList").val().split(',');
    var choiceLectureTitle = $("#choiceLectureTitle").val().split(',');

    if(choiceLectureTitle[0]=="") choiceLectureTitle = [];
    if(choiceLectureCode[0] == "") choiceLectureCode = [];

    if(choiceLectureCode.length >= 15 && ((!obj.hasClass('on')) || (!obj.hasClass('lecture-code-choice'))) ) {
        alert("분야는 15개까지만 선택하실 수 있습니다.");
        return false;
    }

    console.log(choice);
    if(choice == 1) { //선택 해제
        choiceLectureCode.splice(choiceLectureCode.indexOf(code), 1);
        choiceLectureTitle.splice(choiceLectureTitle.indexOf(title),1);
    } else if(choiceLectureCode=="" || choiceLectureCode.includes(code) == false) {
        choiceLectureCode.push(code);
        choiceLectureTitle.push(title);
    }

    obj.attr('data-choice', ((choice==1) ? 0 : 1));
    if(obj.hasClass('on') && obj.hasClass('lecture-code-choice')){
        obj.removeClass('on').remove('lecture-code-choice');
    }else{
        obj.addClass('on').addClass('lecture-code-choice');
    }

    console.log(choiceLectureCode);
    $("#choiceLectureCodeList").val(choiceLectureCode.join(','));
    $("#choiceLectureTitle").val(choiceLectureTitle.join(','));
}

/**
 * 분야 검색 결과 목록에서 분야 선택시 강의분야 선택했던 프로세스처럼 처리하기
 * @param code : 강의 분야 코드
 * */
var choiceLeture = function(code) {
    setChoiceLecture($("a[data-code='"+code+"']"));

    $("#low-lecture-"+code).remove();
}