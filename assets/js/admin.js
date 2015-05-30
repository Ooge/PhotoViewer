Ooge.admin = {
    handlers: {},
    init: function() {
        $.ajax({
			url: Ooge.base_url('ajax/stats/browser_data'),
			type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
			success: function(data) {
				if(data.success){
                    $('#browser_chart').text(data.results);
                    /*$('#browser_chart').highcharts({
                        credits: {
                            enabled: false
                        },
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: null,
                        },
                        title: {
                            text: 'Common Browser Types'
                        },
                        tooltop: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false,
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Browser Used',
                            data: data.results
                        }]
                    });*/
				}
			},
			error: function() {

			}
		});
    }
};
Ooge.admin.init();
