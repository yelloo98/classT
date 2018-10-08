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
            url: '/admin/review/company/list',
            dataSrc: 'data',
            type: 'GET',
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
            { data : 'etc_number',orderable: true, searchable: false},
            {
                data: "score",
                render: function (data, type, row) {
                    var html = '';
                    for(var i=1; i<=5; i++) {
                        if(data+0.5 < i){
                            html +='<i class="fa fa-star-o"></i>';
                        } else if( (data - 0.5) == (i-1) ) {
                            html +='<i class="fa fa-star-half-empty"></i>';
                        } else {
                            html +='<i class="fa fa-star"></i>';
                        }
                    }
                    return html;
                },
                orderable: true
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

        {dt.search((mSearchType == 'all') ? mSearchVal : "");} //전체검색
        {dt.column(1).search( (mSearchType == 'class_title') ? mSearchVal : "");} //# 제목검색
        {dt.column(2).search( (mSearchType == 'teacher_name') ? mSearchVal : "");} //# 강사명 검색
        {dt.column(3).search( (mSearchType == 'company_name') ? mSearchVal : "");} //# 기업명 검색

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
        location.href = '/admin/review/company/detail/'+data.id;
    });

});