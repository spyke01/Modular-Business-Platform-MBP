/*-------------------------------------------------------------------------*/
// Dashboard Functions
/*-------------------------------------------------------------------------*/	
function TTS_guages_dashboard() {
	var gauge_totalTickets = new JustGage({
		id: "gauge_totalTickets",
		value: 0,
		title: "Total Tickets"
	}); 

	var gauge_openTickets = new JustGage({
		id: "gauge_openTickets",
		value: 0,
		title: "open"
	}); 

	var gauge_onHoldTickets = new JustGage({
		id: "gauge_onHoldTickets",
		value: 0,
		title: "On Hold"
	}); 

	var gauge_closedTickets = new JustGage({
		id: "gauge_closedTickets",
		value: 0,
		title: "Closed"
	}); 
	
	setInterval(function() {
		$.getJSON(SITE_URL + '/ajax.php?action=getTicketCounts', function (json) { 
			gauge_totalTickets.refresh( json['total'] );
			gauge_openTickets.refresh( json['open'] );
			gauge_onHoldTickets.refresh( json['onHold'] );
			gauge_closedTickets.refresh( json['closed'] );
		});
	}, 2500);
}

/*-------------------------------------------------------------------------*/
// Graph Functions
/*-------------------------------------------------------------------------*/	
function TTS_graphs_ticketsByStatus( graphCTX ) {
	$.getJSON(SITE_URL + '/graphit.php?selectedGraph=ticketsByStatus&daterange=allTime', function(json) {
		var data = {
			type : 'bar',
			data: {
				labels: json['labels'],
				datasets: [{
					backgroundColor: graphs_fillColor,
					borderColor: graphs_strokeColor,
					data: json['data']
				}]
			}
		};
		new Chart(graphCTX, data );
	});
}
function TTS_graphs_ticketsByProblemCategory( graphCTX ) {
	$.getJSON(SITE_URL + '/graphit.php?selectedGraph=ticketsByProblemCategory&daterange=allTime', function(json) {
		var data = {
			type: 'bar',
			data: {
				labels: json['labels'],
				datasets: [{
					backgroundColor: graphs_fillColor,
					borderColor: graphs_strokeColor,
					data: json['data']
				}]
			}
		};
		new Chart(graphCTX, data );
	});
}