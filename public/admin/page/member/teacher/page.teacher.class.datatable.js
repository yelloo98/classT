$(function() {
    var company_id = $("input[name=id]").val();

    //# DataTable init
    window.classData = $("#class_list").dataTable({
        pageLength: 10,
        pagingType: "full_numbers",
        dom: "<t><p>",
        ordering: false,
        info: false,
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: {
            url : '/admin/member/teacher/class/list',
            data : function ( d ) {
                d.company_id= company_id;
            },
            dataSrc: function ( json ) {
                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'id', orderable: false, searchable: false},
            { data : 'title',orderable: false, searchable: true},
            { data : 'company_name',orderable: false, searchable: true},
            { data : 'teacher_name',orderable: false, searchable: true},
            { data : 'teacher_email',orderable: false, searchable: false},
            { data : 'request_status',orderable: false, searchable: true},
            { data : 'created_at',orderable: false, searchable: true},
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

    //# DataTable List Click
    $('#class_list tbody').on('click', 'tr', function () { //행 클릭시
        var dt = window.classData;
        var data = dt.row( this ).data();

        //# 상세 페이지 이동
        location.href = '/admin/class/detail/'+data.id;
    });

});