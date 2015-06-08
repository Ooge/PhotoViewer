Ooge.admin = {
    handlers: {},
    init: function() {
        // Ajax for the data that gets browser information about users
        $.ajaxSetup({xhrFields: {withCredentials: true}, data:{ 'ry_csrf_token': $.cookie('ry_csrf_cookie') }});
        $.ajax({
			url: Ooge.base_url('ajax/stats/browser_data'),
			type: 'POST',
			success: function(data) {
				if(data.success){ // Compile the found data into a pie chart
                    $('#browser_chart').highcharts({
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
                            data: data.results // Data from the AJAX request
                        }]
                    });
				}
			},
			error: function() {
                $('#browser_chart').html('<b style="text-align:center;">Unable to load data at this time. Try again later!</b>')
			}
		});
    }
};
Ooge.admin.init();
