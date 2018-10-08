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
            url: '/admin/review/student/list',
            dataSrc: 'data',
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        order: [[ 0, "desc" ]], //첫 정렬을 order by id desc로 설정
        columns : [
            { data : 'class_request_id', orderable: true, searchable: false},
            { data : 'company_name',orderable: true, searchable: true},
            { data : 'teacher_name',orderable: true, searchable: true},
            { data : 'class_title',orderable: true, searchable: true},
            { data : 'etc_number',orderable: true, searchable: false},
            {
                data: "satisfaction",
                render: function (data, type, row) {
                    return data + " / 5";
                },
            },
            {
                data: "rating",
                render: function (data, type, row) {
                    return data + " / 5";
                },
            },
            { data : 'class_start_dt',orderable: true, searchable: false}
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

        if(mSearchType == 'all'){ //# 전체검색
            {dt.search(mSearchVal);}
        } else if(mSearchType == 'class_title'){ //# 제목검색
            {dt.column(1).search(mSearchVal);}
        }else if(mSearchType == 'teacher_name'){ //# 강사명 검색
            {dt.column(2).search(mSearchVal);}
        }else if(mSearchType == 'company_name'){ //# 기업명 검색
            {dt.column(3).search(mSearchVal);}
        }

        dt.draw();
    });

    //# DataTable List Click
    $('#table_list tbody').on('click', 'tr', function () {
        var dt = window.dataTable;
        var data = dt.row( this ).data();
        //# 상세 페이지 이동
        location.href = '/admin/review/student/detail/'+data.class_request_id;
    });

});