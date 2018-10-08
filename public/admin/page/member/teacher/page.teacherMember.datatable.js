$(function() {

    //# DataTable init
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
            url: '/admin/member/teacher/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#scoreTable>tbody>tr>td");
                scoreDiv.eq(0).html(json.recordsTotal); //전체
                scoreDiv.eq(1).html(score.completeCnt);  //가입완료
                scoreDiv.eq(2).html(score.waitCnt); //가입대기
                scoreDiv.eq(3).html(score.holdCnt); //가입보류
                scoreDiv.eq(4).html(score.disabledCnt); //비활성화

                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        order : [[0, 'DESC']],
        columns : [
            { data : 'id', orderable: true, searchable: false},
            { data : 'grade', orderable: true, searchable: false},
            { data : 'email',orderable: false, searchable: true},
            { data : 'name',orderable: true, searchable: true},
            { data : 'phone',orderable: true, searchable: false},
            { data : 'recommend_teacher_yn',orderable: true, searchable: true},
            { data : 'status',orderable: true, searchable: true},
            { data : 'created_at',orderable: true, searchable: false}
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
        var mSearchType = $('select[name=searchType]').val();
        var mSearchVal = $('[name=searchWord]').val();
        var searchStatus = $("input:radio[name=searchStatus]:checked").val();
        searchStatus  = (searchStatus =='on')?'':searchStatus ;

        {dt.search( (mSearchType=='all') ? mSearchVal : "" );}

        {dt.column(1).search( (mSearchType == 'email') ? mSearchVal : "" );} //# 이메일 검색
        {dt.column(2).search( (mSearchType == 'name') ? mSearchVal : "" );} //# 이름 검색
        {dt.column(3).search( (mSearchType == 'phone') ? mSearchVal : "" );} //# 이메일 검색
        {dt.column(4).search(searchStatus );} //#회원 상태 검색

        dt.draw();
    });

    //# DataTable List Click
    $('#table_list tbody').on('click', 'tr', function () {
        var dt = window.dataTable;
        var data = dt.row( this ).data();
        //# 상세 페이지 이동
        location.href = '/admin/member/teacher/detail/'+data.id;
    });

});