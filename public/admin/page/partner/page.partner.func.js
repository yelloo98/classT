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
 * 해당 협력사 삭제
 * */
var delPartner = function(id) {
    if(confirm("해당 협력사를 삭제하시겠습니까?") == true) {
        $.ajax({
            url: "/admin/partner/ajax/delPartner/"+id,
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
 * 협력사 수정 버튼
 * */
var partnerUpdate = function(id) {

    $.ajax({
        url : "/admin/partner/ajax/getPartner/"+id,
        type : "get",
        dataType : "json",
        success : function (data) {
            console.log(data);
            var data = data.data;
            $("input[name=id]").val(id);
            $("input[name=name]").val(data.name);
            $("input[name=banner_url]").val(data.banner_url);
            $("input[name=banner_img]").val('');
            $("#banner_img").val(data.banner_img);
            $("select[name=use_yn]").val(data.use_yn);
            $("#modal-partner").modal('show');
        },
        error : function (data, textStatus, errorThrown) {
            console.log('error');
            console.log(data);
        }
    });


}

var savePartner = function() {
    if(confirm("협력사를 등록하시겠습니까?") == true) {
        // var params = $("#uploadForm").serialize();
        var formData = new FormData();

        formData.append("id", $("input[name=id]").val());
        formData.append("name", $("input[name=name]").val());
        formData.append("use_yn", $("select[name=use_yn]").val());
        formData.append("banner_url", $("input[name=banner_url]").val());
        formData.append("_token", $("input[name=_token]").val());
        formData.append("banner_img", $("input[name=banner_img]")[0].files[0]);

        console.log($("input[name=banner_img]")[0].files[0]);

        $.ajax({
            url: "/admin/partner/ajax/savePartner",
            type: "post",
            dataType: "json",
            processData : false,
            contentType : false,
            data : formData,
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