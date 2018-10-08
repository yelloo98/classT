$(function() {

    //# DataTable init
    window.contactDataTable = $("#table_list").dataTable({
        pageLength: 10,
        pagingType: "full_numbers",
        dom: "<t><p>",
        ordering: false,
        info: false,
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/member/teacher/contactPremium/'+$("input[name=id]").val(),
            dataSrc: 'data',
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },

        columns : [
            { data : 'id', orderable: false, searchable: false},
            { data : 'title',orderable: false, searchable: false},
            { data : 'company_name',orderable: false, searchable: false},
            { data : 'request_deadline',orderable: false, searchable: false},
            { data : 'request_status',orderable: false, searchable: false},
            { data : 'created_at',orderable: false, searchable: false},
            {
                data: "id",
                render: function (data, type, row) {
                    var html = '<input type="radio" class="minimal" name="choice_premium_id" value="{0}"/>';
/*                    var html = '<button type="button" class="btn" onclick="javascript:choicePremiumRequest({0}); return false;">선택</button>';*/
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
    $("#btn_contact").click(function(){ //프리미엄 의뢰연결 버튼 누를때마다 테이블 데이터 재요청
        var dt = window.contactDataTable;
        console.log('btn contact click');

        dt.draw();

        $("#modal-contact").modal('show');
    });
});