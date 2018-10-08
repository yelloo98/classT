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
            url: '/admin/stats/classeum/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#totalTable>tbody>tr>td");
                scoreDiv.eq(0).html(score.totalPay); //전체 매출액

                getChart(json.data);

                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'dt', orderable: false, searchable: false},
            { data : 'order_pay',orderable: false, searchable: true},
            { data : 'note',orderable: false, searchable: true},
        ],
        initComplete : function() {}
    }).api();

    //# DataTable Search
    $("#btn_search").click(function() {
        var dt = window.dataTable;
        var mSearchType = $('input[name=searchType]:checked').val(); //통계 형태(일별,월별,연별)
        var searchStartDt = $("#searchStartDt").val();
        var searchEndDt = $("#searchEndDt").val();

        {dt.column(1).search(mSearchType);} //통계 형태 검색

        if(searchStartDt!=""&&searchEndDt!="" && searchStartDt<=searchEndDt) { //날짜 검색
            var diff = getDateDiff(searchStartDt, searchEndDt);

            if(mSearchType == "day" && diff> 31) {
                alert("일별통계인경우 두 날짜의 차이가 한달을 넘을 수 없습니다.");
                return false;
            }

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
            charData[index] = [itemData.dt, itemData.order_pay];
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

    /**
     * 날짜 차이 구하기
     * @param startDtStr : 시작일
     * @param endDtStr
     * */
    function getDateDiff(startDtStr, endDtStr) {
        var startDt = startDtStr.split("-");
        var endDt = endDtStr.split("-");
        var diff = 0;

        startDt = new Date(Number(startDt[0]), Number(startDt[1])-1, Number(startDt[2]));
        endDt = new Date(Number(endDt[0]), Number(endDt[1])-1, Number(endDt[2]));
        diff = (endDt.getTime() - startDt.getTime()) / (1000*60*60*24) ;

        return diff;
    }

});