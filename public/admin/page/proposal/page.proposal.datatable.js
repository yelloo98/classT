$(function() {

    //# DataTable init
    var data;
    window.dataTable = $("#table_list").dataTable({
        pageLength: 10,
        pagingType: "full_numbers",
        dom: "<t><p>",
        ordering: true,
        info: false,
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/proposal/list',
            type : 'GET',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#scoreTable>tbody>tr>td");
                scoreDiv.eq(0).html(json.recordsTotal);
                scoreDiv.eq(1).html(score.completeCnt);
                scoreDiv.eq(2).html(score.incompleteCnt);

                return json.data;
            },
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        order: [[ 0, "desc" ]], //첫 정렬을 order by id desc로 설정
        columns : [
            { data : 'id', orderable: true, searchable: false},
            { data : 'company_name',orderable: true, searchable: true},
            { data : 'teacher_name',orderable: true, searchable: true},
            { data : 'class_title',orderable: true, searchable: true},
            {
                data: "response_yn",
                render: function (data, type, row) {
                    if(data == 'Y') return '전달 완료';
                    else return '미완료';
                },
                className: "dt-center min-w40",
                orderable : false
            },
            { data : 'request_dt',orderable: true, searchable: true},
            { data : 'response_dt',orderable: true, searchable: false}
        ],
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
        $("#table_list").css("width", "100%");
    });

    //# DataTable Search
    $("#btn_search").click(function(){
        var dt = window.dataTable;
        var mSearchType = [];

        $(".search-type:checked").each(function () {
            mSearchType.push($(this).val());
        });

        var mSearchVal = $('[name=searchWord]').val();
        var SearchStatus = $("input:radio[name=searchStatus]:checked").val();

        if(mSearchType.length == 0) { //# 전체검색
            {dt.search(mSearchVal);}
        } else {
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

        if($("#searchStartDt").val()!="" && $("#searchEndDt").val()!=""){ //날짜 검색
            {dt.column(5).search($("#searchStartDt").val());}
            {dt.column(6).search($("#searchEndDt").val());}
        }

        dt.draw();
    });

    //# DataTable List Click
    $('#table_list tbody').on('click', 'tr', function () {
        var dt = window.dataTable;
        var data = dt.row( this ).data();
        //# 상세 페이지 이동
        location.href = '/admin/proposal/detail/'+data.id;
    });

});