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
            url: '/admin/stats/company/list',
            dataSrc: function ( json ) {
                var score = json.score;
                var scoreDiv = $("#totalTable>tbody>tr>td");
                scoreDiv.eq(0).html(score.totalPay); //전체 매출액
                scoreDiv.eq(1).html(json.recordsTotal);  //전체기업수
                getChart(json.data);

                return json.data;
            },
            type: 'GET',
            headers: {
                'X-XSRF-TOKEN': $('meta[name=_csrf]').attr('content')
            }
        },
        columns : [
            { data : 'id', orderable: false, searchable: false},
            { data : 'company_name',orderable: false, searchable: true},
            { data : 'company_number',orderable: false, searchable: true},
            { data : 'order_cnt',orderable: false, searchable: false},
            { data : 'order_pay',orderable: false, searchable: true},
            { data : 'note',orderable: false, searchable: true},
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

    //# DataTable Search
    $("#btn_search").click(function() {
/*        var stDate = new Date(2015, 7, 27) ;
        var endDate = new Date(2015, 8, 1) ;

        var btMs = endDate.getTime() - stDate.getTime() ;
        var btDay = btMs / (1000*60*60*24) ;

console.log(btDay);
return false;*/
        var dt = window.dataTable;
        var mSearchType = $('select[name=searchType]').val();
        var mSearchVal = $('[name=searchWord]').val();
        var searchStartDt = $("#searchStartDt").val();
        var searchEndDt = $("#searchEndDt").val();

        {dt.search( (mSearchType=='all') ? mSearchVal : "");} //전체 검색

        {dt.column(1).search( (mSearchType=="company_name") ? mSearchVal : "" );} //기업명 검색

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
            charData[index] = [itemData.company_name, itemData.order_pay];
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