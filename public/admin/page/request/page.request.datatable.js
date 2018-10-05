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
            url: '/admin/request/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#scoreTable>tbody>tr>td");
                scoreDiv.eq(0).html(score.totalCnt);
                scoreDiv.eq(1).html(score.registerCnt);
                scoreDiv.eq(2).html(score.p_requestCnt);
                scoreDiv.eq(3).html(score.p_revirwCnt);
                scoreDiv.eq(4).html(score.p_sendCnt);
                scoreDiv.eq(5).html(score.c_requestCnt);
                scoreDiv.eq(6).html(score.c_callCnt);
                scoreDiv.eq(7).html(score.c_confirmCnt);
                scoreDiv.eq(8).html(score.c_payment);

                //상태값 변경하기
                return json.data;
            },

/*            dataSrc: function ( json ) {
                return json.data;
            },*/
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },

        columns : [
            { data : 'id', orderable: false, searchable: false},
            { data : 'title',orderable: false, searchable: true},
            { data : 'company_name',orderable: false, searchable: true},
            { data : 'request_deadline',orderable: false, searchable: false},
            { data : 'teacher_name',orderable: false, searchable: true},
            { data : 'teacher_email',orderable: false, searchable: true},
            { data : 'request_status_str',orderable: false, searchable: true},
            { data : 'created_at',orderable: false, searchable: true}
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

        //날짜 검색
        if($("#searchStartDt").val()!="" && $("#searchEndDt").val()!=""){
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
        location.href = '/admin/request/detail/'+data.id;
    });

});