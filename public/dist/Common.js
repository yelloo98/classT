var CommCalendar = {
    option : {},
    /**
     * Calendar init
     * * -----------------------------------------------------------------------------------------------------------------
     * @param obj : Calendar 선택자
     * @param option : 옵션
     */
    init : function(obj, option) {
        if(option == null) {
            CommCalendar.option = {
                height : 650,
                header    : {
                    left  : 'title',
                    center: '',
                    right : ''
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week : 'week',
                    day  : 'day',
                    title : 'title'
                },
                eventClick: function(calEvent, jsEvent, view) {
                    var schedulaID = calEvent.id;
                    //alert("상세 페이지 이동 : /admin/schedule/detail/"+schedulaID);
                },
                selectable: true,
                // timeFormat: 'HH:mm',
                //          titleFormat: {month: 'yyyy년 M월'},
                events    : [],
                removeEvents : false
            };
        } else {
            CommCalendar.option = option;
        }

        CommCalendar.option['monthNames'] = ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"];
        CommCalendar.option['monthNamesShort'] = ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"];
        CommCalendar.option['dayNames'] = ["일요일","월요일","화요일","수요일","목요일","금요일","토요일"];
        CommCalendar.option['dayNamesShort'] = ["일","월","화","수","목","금","토"];

        $(obj).fullCalendar(CommCalendar.option);
    },
    /**
     * Calendar set data
     * * -----------------------------------------------------------------------------------------------------------------
     * @param obj : Calendar 선택자
     * @param data : 데이터
     * @param isReset : 켈린더 초기화 유무
     */
    setData : function(obj, data, isReset) {
        var calObject = $(obj);
        if(isReset == true) calObject.fullCalendar('destroy').fullCalendar(this.option); //데이터 삭제후 다시 넣기
        calObject.fullCalendar( 'addEventSource', data );
    }
};

/**
 * 날짜 계산 함수
 * @param day : 두 날짜사이의 차이
 * @param type : 일기준인지, 월기준인지
 * */
var setCalcDate = function(day, type) {
    var today = new Date(); //오늘날짜

    $('#searchStartDt').datepicker('setDate', today);

    if (type == 'M') {
        today.setMonth(today.getMonth() + day);
    } else {
        today.setDate(today.getDate() + day);
    }

    $('#searchEndDt').datepicker('setDate', today);
}

/**
 * 문자열 뒤에서부터 num 글자만큼 추출하기
 * @param str : 자를 문자열
 * @param num : 추출할 글자수
 * */
var lastSubString = function(str, num) {
    return str.substring(str.length - num);
}

// dist/Common.js

/**
 * 첨부파일 다운로드
 * @param id
 * @param fileName : 클래스이음 첨부파일명
 * @param type : 파일 저장 위치
 * */
var fileDownload = function(id, fileName, type) {
    location.href = '/api/download/'+id+'/'+fileName+'/'+type;

    return false;
}

/**
 * request detail, class detail에서 사용
 * 추천강사 강의 의뢰 진행 상태값 변경하기
 * @param recommend_id : 추천강사 테이블 고유아이디
 * @param changeStatus : 변경될 상태값
 * @param request_type : 요청상태
 **/
var changeRequestStatus = function(recommend_id, changeStatus, request_type) {
    console.log("request_type : " + request_type);
    var key = 0;
    if(changeStatus == 2) key = 0;
    else if(changeStatus == 4) key = 1;
    else if(changeStatus == 6) key = 2;


    if( (changeStatus == 2 || changeStatus == 4) && ( $("#status").val() >= 4 )) {
        alert("이미 다른 강사로 강의진행중입니다.");
        return false;
    }

    var strAray = [["제안서를 요청하시겠습니까?\n요청강사가 있는 경우 해당강사를 삭제할 수 없으며,\n다른 강사에게 제안서를 요청하실 수 없습니다.", "제안서 요청 완료!"],
        ["제안서를 기업에게 발송하시겠습니까?", "제안서발송완료!"],
        ["강사에게 출강요청을 하시겠습니까?", "출강요청 완료!"]];

    if(confirm(strAray[key][0]) == true) {
        $.ajax({
            url: "/api/changeRequestStatus/"+recommend_id+"/"+changeStatus,
            type: "GET",
            dataType: "json",
            async : false,
            success: function (data) {
                console.log(data);
                if(data.status = 200) {
                    alert(strAray[key][1]);
                    $("#status").val(changeStatus);
                    setRecommendTeacherList(request_type); //추천강사 목록 함수 재호출
                } else {
                    alert("failed");
                }
            },
            error: function (data, textStatus, errorThrown) {
                alert("실패");
                return false;
            }
        });
    }
}

/**
 * 추천강사, 요청강사 목록 뿌리기
 * @param request_type - premium, company
 * */
var setRecommendTeacherList = function(request_type) {
    var requestId = $("input[name=id]").val();
    var data = getRecommendTeacherList(requestId, '0');

    var resHtml = "";
    $("#recommend_teacher_list").html("");

    var rowStr = "";
    $.each(data, function(index, itemData) {
        rowStr = "<tr>";

        rowStr +="<td>"+itemData.name+"</td>"; //이름

        if(request_type == 'premium') {
            rowStr +="<td>"+  ((itemData.status == 1)?("<button class='btn' onclick='changeRequestStatus("+itemData.id+", 2, \""+request_type+"\"); return false;'>제안요청</button>") : "요청됨")+"</td>"; //제안요청버튼
        } else {
            rowStr +="<td>요청됨</td>"; //제안요청버튼
        }

        rowStr +="<td>" + ((itemData.proposal_name != null) ? ("<a href='/api/download/" + itemData.id + "/" +itemData.proposal_name+"/PROPOSAL_TEACHER'>다운로드</a>") : "-") +"</td>"; //제안서 있는 경우에만 다운로드

        //제안서 발송
        if(itemData.status >=4) {
            rowStr +="<td>발송완료</td>";
        } else if(itemData.status == 3) {
            rowStr +=("<td><button class='btn' onclick='changeRequestStatus("+itemData.id+", 4, \""+request_type+"\"); return false;'>발송</button></td>");
        } else {
            rowStr +="<td>-</td>";
        }

        rowStr +="<td>" + ((itemData.p_send_dt != null) ? itemData.p_send_dt : "-") +"</td>"; //제안서 발송일
        rowStr +="<td>" + ((itemData.c_request_dt != null) ? itemData.c_request_dt : "-"  )+"</td>"; //강의요청일자

        if(request_type == 'premium') {
            //출강요청 버튼
            if (itemData.status >= 6) {
                rowStr += "<td>요청완료</td>";
            } else if (itemData.status == 5) {
                rowStr += ("<td><button class='btn' onclick='changeRequestStatus(" + itemData.id + ", 6, \""+request_type+"\"); return false;'>출강요청</button></td>");
            } else {
                rowStr += "<td>-</td>";
            }

            rowStr += "<td>" + ((itemData.c_call_dt != null) ? itemData.c_call_dt : "-") + "</td>"; //출강요청일자
        }

        rowStr +="<td>" + ((itemData.c_confirm_dt != null) ? itemData.c_confirm_dt : "-") +"</td>"; //강의확정일자
        if(request_type == 'premium') {
            rowStr +="<td>"+ ((itemData.status == 1) ? ("<button class='btn' onclick='delRecommendTeacher("+itemData.id+")'>X</button>") : "삭제불가")+"</td>"; //강사 삭제
        }

        rowStr += "</tr>";

        resHtml += rowStr;
    });

    $("#recommend_teacher_list").html(resHtml);
}

/**
 * 추천 강사 목록 가져오기 추천상태 or 거절상태
 * @param requestId
 * @param status
 * */
var getRecommendTeacherList = function(requestId, status) {
    //TODO ajax 호출해서 리턴
    var datas = [];
    $.ajax({
        url: "/api/recommendTeacherList/"+requestId+"/"+status,
        type: "GET",
        dataType: "json",
        async : false,
        success: function (data) {
            console.log(data);
            datas = data;
        },
        error: function (data, textStatus, errorThrown) {
            console.log(textStatus);
            alert("추천 강사 목록을 얻어오는데 실패하였습니다.");
            return false;
            // console.log(errorThrown);

        }
    });

    return datas;

}