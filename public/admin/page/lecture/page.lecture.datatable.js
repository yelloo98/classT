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
            url: '/admin/lecture/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#scoreTable>tbody>tr>td");
                scoreDiv.eq(0).html(score.totalCnt);
                scoreDiv.eq(1).html(score.largeCnt);
                scoreDiv.eq(2).html(score.midCnt);
                scoreDiv.eq(3).html(score.smallCnt);
                //상태값 변경하기
                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'id', orderable: false, searchable: false}, //#No
            { data : 'large_cate',orderable: false, searchable: true}, //#대분류
            { data : 'mid_cat',orderable: false, searchable: true}, //#중분류
            { data : 's_cat',orderable: false, searchable: true}, //#소분류
            { data : 'created_at',orderable: false, searchable: false}, //등록일
            { //#수정
                data: "id",
                render: function (data, type, row) {
                    var html = '<button type="button" class="btn" onclick="javascript:lectureUpdate({0}); return false;">수정</button>';
                    return html.format(data);
                },
                className: "dt-center min-w40"
            },
            { //삭제
                data: "id",
                render: function (data, type, row) {
                    var html = '<button class="btn" onclick="javascript:delLecture({0}); return false;">삭제</button>';
                    return html.format(data);
                },
                className: "dt-center min-w40"
            }
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
    $("#btn_search").click(function() {
        var dt = window.dataTable;
        var mSearchType = $('select[name=searchType]').val();
        var mSearchVal = $('[name=searchWord]').val();
        var mSearchCat = $("input:radio[name=searchCat]:checked").val();

        {dt.search((mSearchType == 'all') ? mSearchVal : '');}
        {dt.column(1).search((mSearchType == 'mid_cat') ? mSearchVal : '');} //# 중분류 검색
        {dt.column(2).search((mSearchType == 's_cat') ? mSearchVal : '');} //# 소분류 검색

        {dt.column(3).search(mSearchCat);} //#분류 검색

        dt.draw();
    });

});