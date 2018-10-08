$(function() {

    //# DataTable init
    window.dataTable = $("#table_list").dataTable({
        paging : false,
        dom: "<t><p>",
        ordering: false,
        info: false,
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/stats/teacher/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#totalTable>tbody>tr>td");
                scoreDiv.eq(0).html(json.recordsTotal + "건"); //전체 강의 건수
                scoreDiv.eq(1).html(score.teacherTotal + "명");  //전체강사수
                getChart(json.data);

                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'rank', orderable: false, searchable: false},
            { data : 'name',orderable: false, searchable: true},
            { data : 'account_number',orderable: false, searchable: true},
            { data : 'order_cnt',orderable: false, searchable: false},
            { data : 'order_pay',orderable: false, searchable: true},
            { data : 'note',orderable: false, searchable: true},
        ],
        initComplete : function() {}
    }).api();

    //# DataTable Search
    $("#btn_search").click(function() {
        var dt = window.dataTable;
        var mSearchType = $('select[name=searchType]').val();
        var mSearchVal = $('[name=searchWord]').val();
        var searchStartDt = $("#searchStartDt").val();
        var searchEndDt = $("#searchEndDt").val();

        {dt.search( (mSearchType=='all') ? mSearchVal : "");} //전체 검색

        {dt.column(1).search( (mSearchType=="name") ? mSearchVal : "" );} //강사명 검색

        if(searchStartDt!=""&&searchEndDt!="" && (searchStartDt<=searchEndDt)) { //날짜 검색
            {dt.column(2).search(searchStartDt);}
            {dt.column(3).search(searchEndDt);}
        }

        dt.draw();
    });

    /**
     * 막대 차트 생성
     **/
    function getChart(data) {
        var charData = [];

        $.each(data, function(index, itemData) {
            charData[index] = [itemData.name, itemData.order_cnt];
        });

        var bar_data = {
            data :  charData,
            color: '#3c8dbc'
        };
        $.plot('#bar-chart', [bar_data], {
            grid  : {
                borderWidth: 1,
                borderColor: '#f3f3f3',
                tickColor  : '#f3f3f3'
            },
            series: {
                bars: {
                    show    : true,
                    barWidth: 0.5,
                    align   : 'center'
                }
            },
            xaxis : {
                mode      : 'categories',
                tickLength: 0
            }
        });
    }

});