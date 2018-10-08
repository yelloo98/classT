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
        order : [[0, 'DESC']],
        ajax: {
            url: '/admin/qna/list',
            dataSrc: 'data',
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'id', orderable: true, searchable: true},
            { data : 'writer_type', orderable: false, searchable: true},
            { data : 'title',orderable: false, searchable: true},
            { data : 'writer_name',orderable: false, searchable: true},
            { data : 'responded_yn',orderable: false, searchable: true},
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
    //# DataTable List Click
    $('#table_list tbody').on('click', 'tr', function () {
        var dt = window.dataTable;
        var data = dt.row( this ).data();
        //# 상세 페이지 이동
        location.href = '/admin/qna/detail/'+data.id;
    });

    $("#btn_search").click(function() {
        var dt = window.dataTable;
        var mSearchType = $('select[name=searchType]').val();
        var mSearchVal = $('[name=searchWord]').val();

        var mSearchResponseYN = $("input:radio[name=searchResponseYN]:checked").val();
        var mSearchWriterType = $("input:radio[name=searchWriterType]:checked").val();

        if(mSearchType == 'all') { //# 전체검색
            {dt.search(mSearchVal);}
        } else if(mSearchType == 'title') { //# 제목검색
            {dt.column(1).search(mSearchVal);}
        } else if(mSearchType == 'writer_name') { //# 작성자 검색
            {dt.column(2).search(mSearchVal);}
        }

        {dt.column(3).search(mSearchResponseYN);} //# 응답 상태

        {dt.column(4).search(mSearchWriterType);} //# 작성자 타입
        console.log(mSearchWriterType);

        dt.draw();

    });
});