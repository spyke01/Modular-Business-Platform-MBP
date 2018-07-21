/*-------------------------------------------------------------------------*/
// Page Load Functions
/*-------------------------------------------------------------------------*/	
$(document).ready(function(){
	
	// TinyMCE
	if ( $("#hideMySEOWelcomeBanner").length ) { 
		$('#hideMySEOWelcomeBanner').click(function(){
			$.get(SITE_URL + '/ajax.php?action=showedMySEOTour');
	        $('#mySEOTour').hide();
	    });
	}
});
/*-------------------------------------------------------------------------*/	
// Our KnockOut code for the tasks view
/*-------------------------------------------------------------------------*/	
stateInfo = {
    OPEN: {
    	className: 'state_open',
    	icon: '1',
    	statusCode: '1',
    },
    COMPLETED: {
    	className: 'state_completed',
    	icon: 'M',
    	statusCode: '2',
    },
    SKIPPED: {
    	className: 'state_skipped',
    	icon: '3',
    	statusCode: '3',
    },
    REJECTED: {
    	className: 'state_rejected',
    	icon: 'V',
    	statusCode: '4',
    }
};
taskJoinedClasses = $.map(stateInfo, function(e){ return e['className']; }).join(' ');
    
function seoTask( id, item ) {
    var self = this;
    self.id = 'task_' + id;
    self.status = item.status;
    self.icon = stateInfo[item.status]['icon'];
    self.title = item.title;
    self.description = item.description;
    self.effort = item.effort;
    self.impact = item.impact;
    self.about = item.about;
    self.howto = item.howTo;
    self.notes = item.notes;
    self.classes  = 'task clearfix';
	self.classes += ' ' + stateInfo[item.status]['className'];	
}
 
function seoTasks(clientID) {
	// Data
	var self = this;
    self.clientID = clientID;
    self.tasks = ko.observableArray();
	self.catID = '88';
	self.status = 'open';
	self.showNoTasksText = ko.observable(true);
	self.catTitle = ko.observable('');
	self.catDescription = ko.observable('');

	// Operations
	self.getUrlParams = function (catID, status) {
		urlParams = '';
		if (catID) {
			urlParams += "&catID=" + catID;
		}
		if (status) {
			// This parameter is ignored currently since we just show/hide the status instead of hitting the DB each time
			urlParams += '&status=' + status;
		}
		return urlParams;
	};
	
	self.getTasks = function (catID, status) {
		self.catID = catID;
		self.status = status;
		
		// Get cat items
		$.getJSON(SITE_URL + '/ajax.php?action=getCatDetails&id=' + self.catID, function(data) {
			self.catTitle(data.title);
			self.catDescription(data.description);
		});
		
		// Get tasks
		var ajaxURL = SITE_URL + "/ajax.php?action=getSEOTasksJSON&id=" + clientID + "&";
		var url = ajaxURL + self.getUrlParams(catID, status);
		self.tasks.removeAll();
		
		$.getJSON(url, function (allData) {
			var tasks = allData.tasks;
			
			$.map(tasks, function(value, key) {
				self.tasks.push(new seoTask(key, value));
			});
			
			self.updateStatusBadgeCounts();
			self.updateShowNoTasksText();
		});
	};
	
	self.updateCatOpenTaskCounts = function () {
		jQuery.post(SITE_URL + '/ajax.php?action=getOpenTasksForCat&id=' + self.clientID + '&catID=' + self.catID, function(data) {
			$('#cat' + self.catID + ' .count').html( '(' + data + ' Open)' );
		});
	};
	
	self.updateStatusBadgeCounts = function () {
		$('#status_1 .label').html( $('#tasks .state_open').length );
		$('#status_2 .label').html( $('#tasks .state_completed').length );
		$('#status_3 .label').html( $('#tasks .state_skipped').length );
		$('#status_4 .label').html( $('#tasks .state_rejected').length );
	};
	
	self.updateShowNoTasksText = function () {
		state = self.status.toUpperCase();
		//alert(state);
		//alert( $('#tasks .' + stateInfo[state]['className']).length );
		if ( $('#tasks .' + stateInfo[state]['className']).length == 0 )
			self.showNoTasksText(true);
		else
			self.showNoTasksText(false);
	};
	
	self.saveNote = function (task) {
		var taskID = task.id.replace( 'task_', '' );
		
		jQuery.post(SITE_URL + '/ajax.php?action=saveTaskNotes&id=' + self.clientID + '&taskID=' + taskID, $('#' + task.id + ' .taskfunction_notes textarea').serialize(), function(data) {
			bootbox.alert('Notes saved...');
		});
	};
	
	self.updateStatus = function (taskID, status) {
		jQuery.post(SITE_URL + '/ajax.php?action=updateTaskStatus&id=' + self.clientID + '&taskID=' + taskID + '&status=' + status, function(data) {
			//bootbox.alert('Status saved...');
		});
		self.updateCatOpenTaskCounts();
	};
	
	// Click Handlers
	$( document ).on( "click", "#taskNav #catItems a", function ( event ) {
		event.preventDefault();
		var selectedCatID = $(this).attr('id').replace( 'cat', '' );
		self.getTasks( selectedCatID, self.status );
	});
	
	$( document ).on( "click", "#statusFilters a", function ( event ) {
		event.preventDefault();
		var selectedStatus = $(this).text().toLowerCase();
		selectedStatus = selectedStatus.substring(0, selectedStatus.indexOf(' ')); // Remove the badge stuff from the name
		self.status = selectedStatus;
		self.updateShowNoTasksText();
		
		//self.getTasks( self.catID, selectedStatus );
		// Dont get the tasks just show/hide items
		$('#tasks').removeClass('filter_open filter_completed filter_skipped filter_rejected').addClass('filter_' + selectedStatus);
	});
	
	$( document ).on( "click", ".taskfunctions a", function ( event ) {
	    event.preventDefault();
	    var func = $(event.target).data('function'),
	        popover = $(this).closest('.task').find('.taskfunction_' + func);
	    
	    //$(this).closest('.taskwrap').find('.taskfunction_' + func)
	    
	    if (popover.is(':visible')) {
	        popover.hide();
	    } else {
	    	popover.show();
	        $(this).closest('.task').find('.taskfunction').not(popover).hide();
	    }
	});
	
	$( document ).on( "click", ".closepopover", function ( event ) {
	    event.preventDefault();
	    $(this).parent().hide();
	});
	
	$( document ).on( "click", ".taskcheckbox", function ( event ) {
	    event.preventDefault();
	    $(this).closest('.task').find('.taskstatus').toggle();
	});
	
	$( document ).on( "click", ".taskstatus a", function ( event ) {
	    event.preventDefault();
	    var state = $(this).data('state'),
	    	statusCode = stateInfo[state]['statusCode'],
	    	icon = $(this).data('icon'),
	    	taskID = $(this).closest('.task').attr('id').replace( 'task_', '' );
	    
	    // Update checkbox icon
	    //alert( icon );
	    $('#tasks #task_' + taskID + '  .taskwrap .taskcheckbox').attr( 'data-icon', icon );
	    
	    // Update task class
	    //alert( stateInfo[state] );
	    $(this).closest('.task').removeClass( taskJoinedClasses ).addClass( stateInfo[state]['className'] );	    
	    $(this).closest('.task').find('.taskstatus').hide();
	    
	    // Save it
	    self.updateStatus( taskID, statusCode );
			
		self.updateStatusBadgeCounts();
		self.updateShowNoTasksText();
	});
	
	// Runtime Events
	self.getTasks( self.catID, self.status );
}

function generateSEOTasksTreeView( clientID, divID ) {	
	jQuery.post(SITE_URL + '/ajax.php?action=returnSEOTasksTreeViewData&id=' + clientID, function(data) {
		$('#' + divID).treeview({ 'data': data})
		
		$('#' + divID).on('nodeSelected', function(event, data) {
			//console.log(data);
			var selectedCatID = data.catID;
			seoClientTasksObj.getTasks( selectedCatID, seoClientTasksObj.status );
		});
	});
}