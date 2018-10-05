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

/**
 * 주소 검색 함수
 * @param name : 주소값 들어갈 input value name
 **/
function searchAddress(name) {
    new daum.Postcode({
        oncomplete: function(data) {
            console.log(data);
            // 각 주소의 노출 규칙에 따라 주소를 조합한다.
            // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
            var fullAddr = data.address; // 최종 주소 변수
            var extraAddr = ''; // 조합형 주소 변수

            // 기본 주소가 도로명 타입일때 조합한다.
            if(data.addressType === 'R'){
                //법정동명이 있을 경우 추가한다.
                if(data.bname !== ''){
                    extraAddr += data.bname;
                }
                // 건물명이 있을 경우 추가한다.
                if(data.buildingName !== ''){
                    extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
            }

            var areaCode = '';
            if(data.sido != "") {
                areaCode = getCateAreaCode(data.sido);
            }

            $("input[name='"+name+"']").val(fullAddr);

            // 주소 정보를 해당 필드에 넣는다.
            //document.getElementById("sample5_address").value = fullAddr;
            // 주소로 상세 정보를 검색

        }
    }).open();
}

/**
 * 지도 띄우기 함수
 * @param address : 주소
 * @param title : 주소명
 **/
var loadMap = function (address, title) {
    console.log(address);
    // address = "서울특별시 강북구 인수봉로 8길 5 현영빌라 B동";
    title = "주소명";

    var mapContainer = document.getElementById('map'), // 지도를 표시할 div
        mapOption = {
            center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
            level: 5 // 지도의 확대 레벨
        };

    // 지도를 생성합니다
    var map = new daum.maps.Map(mapContainer, mapOption);

    // 주소-좌표 변환 객체를 생성합니다
    var geocoder = new daum.maps.services.Geocoder();

    // 주소로 좌표를 검색합니다
    geocoder.addressSearch(address, function(result, status) {

        // 정상적으로 검색이 완료됐으면
        if (status === daum.maps.services.Status.OK) {

            var coords = new daum.maps.LatLng(result[0].y, result[0].x);

            // 결과값으로 받은 위치를 마커로 표시합니다
            var marker = new daum.maps.Marker({
                map: map,
                position: coords
            });

            // 인포윈도우로 장소에 대한 설명을 표시합니다
            var infowindow = new daum.maps.InfoWindow({
                content: '<div style="width:150px;text-align:center;padding:6px 0;">'+title+'</div>'
            });
            infowindow.open(map, marker);

            // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
            map.setCenter(coords);
        }
    });
}