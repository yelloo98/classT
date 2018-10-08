$(function() {

    //# DataTable init
    window.dataTable = $("#table_list").dataTable({
        pageLength: 5,
        pagingType: "full_numbers",
        dom: "<t><p>",
        ordering: true,
        info: false,
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/partner/list',
            dataSrc: 'data',
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        order: [[ 0, "desc" ]], //첫 정렬을 order by id desc로 설정
        columns : [
            { data : 'id', orderable: true, searchable: false},
            { data : 'name',orderable: true, searchable: true},
            // { data : 'banner_img',orderable: false, searchable: false},
            {
                data: "banner_img",
                render: function (data, type, row) {
                    if(data==null) return data;
                    else {
                        var html = '<img src="{0}"/>';
                        return html.format(data);
                    }
                },
                className: "dt-center min-w40",
                orderable : false
            },
            {
                data: "id",
                render: function (data, type, row) {
                    var html = '<button type="button" class="btn" onclick="javascript:partnerUpdate({0}); return false;">수정</button>';
                    return html.format(data);
                },
                className: "dt-center min-w40",
                orderable : false
            },
            {
                data: "id",
                render: function (data, type, row) {
                    var html = '<button class="btn" onclick="javascript:delPartner({0}); return false;">삭제</button>';
                    return html.format(data);
                },
                className: "dt-center min-w40",
                orderable : false
            },
            { data : 'use_yn',orderable: false, searchable: false},
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
    $("#btn_search").click(function() {
        var dt = window.dataTable;
        var mSearchVal = $('[name=searchWord]').val();
        var mSearchUseYN = $("input:radio[name=searchUseYN]:checked").val();

        {dt.column(1).search(mSearchVal);} //협력사명

        {dt.column(2).search(mSearchUseYN);} //#배너 이미지 노출 여부 검색

        dt.draw();
    });

});