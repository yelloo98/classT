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
            url: '/admin/question/basicList',
            dataSrc: 'data',
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'id', orderable: false, searchable: false},
            { data : 'question',orderable: false, searchable: false},
            { data : 'question_topic',orderable: false, searchable: false},
            {
                data: "answer_type",
                render: function (data, type, row) {
                    if(data == 'NUM') {
                        return '객관식5';
                    } else if(data == 'STAR') {
                        return '별 찍기';
                    } else if(data == 'CONTENT') {
                        return '문자열';
                    } else {
                        data
                    }
                },
                className: "dt-center min-w40"
            },
            { data : 'answer_score',orderable: false, searchable: false},
            {
                data: "id",
                render: function (data, type, row) {

                    var html = '<button type="button" class="btn" onclick="javascript:questionUpdate({0}, \'basic\'); return false;">수정</button>';
                    return html.format(data);
                },
                className: "dt-center min-w40"
            },
            {
                data: "id",
                render: function (data, type, row) {
                    var html = '<button class="btn" onclick="javascript:delQuestion({0}); return false;">삭제</button>';
                    return html.format(data);
                },
                className: "dt-center min-w40"
            },
            { data : 'created_at',orderable: false, searchable: false}
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

        dt.draw();
    });

});