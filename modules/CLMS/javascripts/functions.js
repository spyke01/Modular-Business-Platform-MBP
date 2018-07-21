/*-------------------------------------------------------------------------*/
// Ajax Functions
/*-------------------------------------------------------------------------*/	
invoicesProductRowNumber = 0;
ordersProductRowNumber = 0;

var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();

function ajaxDeleteInvoicePaymentNotifier(spinDivID, action, text, row, invoiceID) {
	bootbox.confirm('Are you sure you want to delete this ' + text + '?', function(result) {
		if ( result == true ) {
			$('#' + spinDivID).toggle();	
			jQuery.get(action, function(data) { $('#' + row).hide(); });
			// Update the invoice to show the payment removal
			jQuery.get(SITE_URL + '/ajax.php?action=reprintInvoice&id=' + invoiceID, function(data) {
				$('#updateMeViewInvoice').html(data);
			});
		}
	});
}

// Invoice Functions
function invoicesAddProductRow(linkObj) {
	if (linkObj) {
		spinnerObj = $(linkObj).parent().parent().find('span.spinner');
		spinnerObj.toggle(); 	
	}
	jQuery.get(SITE_URL + '/ajax.php?action=returnInvoiceProductTableRowHTML&id=' + invoicesProductRowNumber, function(data) {
		$('#addInvoiceProductsTable > tbody:last').append(data);
		invoicesProductRowNumber++;
		if (linkObj) spinnerObj.toggle();
	});
}

function invoicesRemoveProductRow(linkObj) { 
	bootbox.confirm('Are you sure you want to delete this invoice line?', function(result) {
		if ( result == true ) {
			$(linkObj).parent().parent().remove(); 
			$(linkObj).parent().parent().find('span.spinner').toggle(); 
		}
	});
}

function updateInvoiceLineTotalAmount(invoiceProductID, spinnerHTML) { 
    $('#' + invoiceProductID + '_totalDue').html(spinnerHTML);
	jQuery.get(SITE_URL + '/ajax.php?action=getInvoiceLineTotal&id=' + invoiceProductID, function(data) {
		$('#' + invoiceProductID + '_lineTotal').html(data);
	});
}

function updateInvoiceSubtotalAmount(invoiceID, spinnerHTML) { 
    $('#' + invoiceID + '_totalDue').html(spinnerHTML);
	jQuery.get(SITE_URL + '/ajax.php?action=getInvoiceSubtotal&id=' + invoiceID, function(data) {
		$('#' + invoiceID + '_subtotal').html(data);
	});
}

function updateInvoiceTotalDueAmount(invoiceID, spinnerHTML) { 
    $('.' + invoiceID + '_totalDue').html(spinnerHTML);
	jQuery.get(SITE_URL + '/ajax.php?action=getInvoiceTotalDue&id=' + invoiceID, function(data) {
		$('.' + invoiceID + '_totalDue').html(data);
	});
}

function updateInvoiceTotals(invoiceID, spinnerHTML) { 
    updateInvoiceSubtotalAmount(invoiceID, spinnerHTML);
    updateInvoiceTotalDueAmount(invoiceID, spinnerHTML);
}

// Calendar Functions
var calendar = $('#updateMeAppointments #calendar').fullCalendar({
	editable: true,
	header: {
	    left: 'prev,next today',
	    center: 'title',
	    right: 'month,agendaWeek,agendaDay'
	},
	
	events: SITE_URL + '/ajax.php?action=getCalendarAppointments',
	
	eventClick: function(calEvent, jsEvent, view) {	
		alert('Event: ' + calEvent.title);
		alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
		alert('View: ' + view.name);
		
		// change the border color just for fun
		$(this).css('border-color', 'red');	
	},
	// Convert the allDay from string to boolean
	eventRender: function (event, element, view) {
	    if (event.allDay === 'true') {
		   event.allDay = true;
	    } else {
		   event.allDay = false;
	    }
	},
	selectable: true,
	selectHelper: true,
	select: function (start, end, allDay) {
		$('#appointmentModal').modal();
	    calendar.fullCalendar('unselect');
	},
	
	editable: true,
	eventDrop: function (event, delta) {
	    start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
	    end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
	    $.ajax({
		   url: SITE_URL + '/ajax.php?action=updateAppointment',
		   data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
		   type: "POST",
		   success: function (json) {
			  alert("Updated Successfully");
		   }
	    });
	},
	eventResize: function (event) {
	    start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
	    end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
	    $.ajax({
		   url: SITE_URL + '/ajax.php?action=updateAppointment',
		   data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
		   type: "POST",
		   success: function (json) {
			  alert("Updated Successfully");
		   }
	    });
	
	}
});

$('#appointmentModal .modal-footer .btn-primary').click( function() {
	    var title = $('#appointmentModal #title').val();
	    if (title) {
		   start = $.fullCalendar.formatDate(start, "yyyy-MM-dd HH:mm:ss");
		   end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:ss");
		   $.ajax({
			  url: SITE_URL + '/ajax.php?action=addAppointment',
			  data: 'title=' + title + '&start=' + start + '&end=' + end,
			  type: "POST",
			  success: function (json) {
				 alert('Added Successfully');
			  }
		   });
		   calendar.fullCalendar('renderEvent', {
				 title: title,
				 start: start,
				 end: end,
				 allDay: allDay
			  },
			  true // make the event "stick"
		   );
	    }
});

/*-------------------------------------------------------------------------*/
// Graph Functions
/*-------------------------------------------------------------------------*/	
function CLMS_graphs_invoicedVsPaid( graphCTX ) {
	$.getJSON(SITE_URL + '/graphit.php?selectedGraph=invoicedVsPaid&daterange=allTime', function(json) {
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
function CLMS_graphs_invoicesByStatus( graphCTX ) {
	$.getJSON(SITE_URL + '/graphit.php?selectedGraph=invoicesByStatus&daterange=allTime', function(json) {
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
function CLMS_graphs_invoicesByClient( graphCTX ) {
	$.getJSON(SITE_URL + '/graphit.php?selectedGraph=invoicesByClientCategory&daterange=allTime', function(json) {
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