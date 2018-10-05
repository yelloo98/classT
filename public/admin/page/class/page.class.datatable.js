$(function() {

    //# DataTable init
    window.dataTable = $("#table_list").dataTable({
        pageLength: 10,
        pagingType: "full_numbers",
        dom: "<t><p>",
        ordering: false,
        info: false,
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/class/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#scoreTable>tbody>tr>td");
                scoreDiv.eq(0).html(score.totalCnt);
                scoreDiv.eq(1).html(score.registerCnt);
                scoreDiv.eq(2).html(score.p_revirwCnt);
                scoreDiv.eq(3).html(score.p_sendCnt);
                scoreDiv.eq(4).html(score.c_requestCnt);
                scoreDiv.eq(5).html(score.c_confirmCnt);
                scoreDiv.eq(6).html(score.c_payment);

                //상태값 변경하기
                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'id', orderable: false, searchable: false},
            { data : 'title',orderable: false, searchable: true},
            { data : 'company_name',orderable: false, searchable: true},
            { data : 'class_pay',orderable: false, searchable: true},
            { data : 'teacher_name',orderable: false, searchable: true},
            { data : 'teacher_email',orderable: false, searchable: false},
            { data : 'request_status',orderable: false, searchable: true},
            { data : 'created_at',orderable: false, searchable: true},
            { data : 'created_at',orderable: false, searchable: true}
        ],
        createdRow: function( row, data, dataIndex ) {
            var html = addCustomRow(data);
            $(row).html('<td colspan=20>'+html+'</td>');
        },
        language: {
            "paginate": {
                "first": "<<",
                "previous": "<",
                "last": ">>",
                "next": ">"
            }
        },
        initComplete : function() {}
    }).api();

    $('#table_list').on( 'draw.dt', function () {
        var dt = window.dataTable;

        $(".totalCnt").text('총 ' + dt.page.info().recordsTotal + '건');
    } );

    //# DataTable Search
    $("#btn_search").click(function(){
        var dt = window.dataTable;

        var mSearchType = [];

        $(".search-type:checked").each(function () {
            mSearchType.push($(this).val());
        });

        var mSearchVal = $('[name=searchWord]').val();
        var SearchStatus = $("input:radio[name=searchStatus]:checked").val();
        SearchStatus = (SearchStatus=='on')?'':SearchStatus;

        if(mSearchType.length == 0) { //# 전체검색
            {dt.search(mSearchVal);}
        } else {
            {dt.search("");}
            if(mSearchVal!="") {
                {dt.column(1).search( (($.inArray('title', mSearchType) >= 0) ? mSearchVal : "") );}
                {dt.column(2).search( ($.inArray('teacher_name', mSearchType) >= 0) ? mSearchVal : "" );}
                {dt.column(3).search( ($.inArray('company_name', mSearchType) >= 0) ? mSearchVal : "" );}
            } else {
                alert("검색어를 입력해주세요!");
                return false;
            }
        }

        {dt.column(4).search(SearchStatus);} //요청 상태 검색

        if($("#searchStartDt").val()!="" && $("#searchEndDt").val()!=""){
            {dt.column(5).search($("#searchStartDt").val());}
            {dt.column(6).search($("#searchEndDt").val());}
        }

        dt.draw();
    });

    var addCustomRow = function(data){
        var html = '';
        html += '<div style="padding: 4px; background-color: #2e383c; ">';
        html += '   <div style="color: white; float: left; width: 50%;">'+data.title+'</div>';
        html += '   <div style="color: white; float: left; width: 16%;">'+data.company_name+'</div>';
        html += '   <div style="color: white; float: left; width: 16%;">'+data.created_at+'</div>';
        html += '   <div style="color: white; float: left; width: 16%;">';
        html += '       <button style="color: black;" onclick="javascript:moveFunction(\''+data.id+'\')">상세보기</button>';
        html += '   </div>';


        html += '    <table style="width: 100%; margin-top: 3px;">';
        html += '        <tr style="background-color: #e2e2e2; ">';
        html += '           <td>강사명</td>';
        html += '           <td>강사아이디</td>';
        html += '           <td>강의료</td>';
        html += '           <td>상태</td>';
        html += '           <td>등록일</td>';
        html += '           <td>비고</td>';
        html += '        </tr>';

        for(var item in data.teacher_list) {
            var info = data.teacher_list[item];
            html += '        <tr style="border-bottom: 1px solid">';
            html += '           <td style="width: 16%">'+info.teacher_name+'</td>';
            html += '           <td style="width: 16%">'+info.teacher_email+'</td>';
            html += '           <td style="width: 16%">'+info.teacher_time_pay+'/'+info.teachercount_pay+'</td>';
            html += '           <td style="width: 16%">'+info.status+'</td>';
            html += '           <td style="width: 16%">'+info.created_at+'</td>';
            html += '           <td style="width: 16%">비고</td>';
            html += '        </tr>';
        }
        html += '    </table>';
        html += '</div>';

        return html;
    };
});

//# 페이지 이동
var moveFunction = function(id) {
    location.href = '/admin/class/detail/'+id;
};