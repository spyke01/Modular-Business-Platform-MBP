var graphs_fillColor = 'rgba(220,220,220,0.5)',
    graphs_strokeColor = 'rgba(220,220,220,1)';

/*-------------------------------------------------------------------------*/
// Page Load Functions
/*-------------------------------------------------------------------------*/	
$(document).ready(function(){
	// Handle tabs
	$(".tabs").tabs();	
	
	// Edits are inline
	//$.fn.editable.defaults.mode = 'popup';

	// Navigation dropdown fix for IE6
	/*
	if(jQuery.browser.version.substr(0,1) < 7) {
		$('#nav li').hover(
			function() { $(this).addClass('iehover'); },
			function() { $(this).removeClass('iehover'); }
		);
	}
	*/
	
	// Autosize all textareas
	//$('textarea').autosize(); 
	
	// Add select2
	$('select.select2').select2(); 
	
	// TinyMCE
	if ( $(".tinymce").length ) { 
		tinymce.init({
			selector: ".tinymce",        
			relative_urls : false,
			remove_script_host : false,
			
			plugins: [
				"advlist autolink link image lists charmap hr anchor spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"table contextmenu directionality emoticons paste textcolor"
			],
			removed_menuitems: "newdocument",
			//content_css: "css/content.css",
			toolbar: "insertfile undo redo | styleselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media fullpage | forecolor backcolor emoticons", 
			style_formats: [
				{title: "Bold text", inline: "b"},
				{title: "Red text", inline: "span", styles: {color: "#ff0000"}},
				{title: "Red header", block: "h1", styles: {color: "#ff0000"}},
			],
			rel_list: [
				//{title: 'Lightbox', value: 'lightbox'},
				//{title: 'Table of contents', value: 'toc'},
				{title: 'Do Follow', value: 'follow'},
				{title: 'No Follow', value: 'nofollow'}
			],
			// update validation status on change
			onchange_callback: function(editor) {
				tinyMCE.triggerSave();
				$("#" + editor.id).valid();
			}
		});
	}
	
	if ( $(".tinymce_basic").length ) { 
		tinymce.init({
			selector: ".tinymce_basic",        
			relative_urls : false,
       		remove_script_host : false,
			
			plugins: [
				"advlist autolink link image lists charmap hr anchor spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"table contextmenu directionality emoticons paste textcolor"
			],
			removed_menuitems: "newdocument",
			menubar: false,
			toolbar: "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
			rel_list: [
				//{title: 'Lightbox', value: 'lightbox'},
				//{title: 'Table of contents', value: 'toc'},
				{title: 'Do Follow', value: 'follow'},
				{title: 'No Follow', value: 'nofollow'}
			],
			// update validation status on change
			onchange_callback: function(editor) {
				tinyMCE.triggerSave();
				$("#" + editor.id).valid();
			}
		});
	}
	
	if ( $(".tinymce_imageupload").length ) { 
		tinymce.init({
			selector: ".tinymce_imageupload",        
			relative_urls : false,
			remove_script_host : false,			
			plugins: [
				"image"
			],
			removed_menuitems: "newdocument",
			toolbar: "image",
			// update validation status on change
			onchange_callback: function(editor) {
				tinyMCE.triggerSave();
				$("#" + editor.id).valid();
			}
		});
	}	
	
	if ( $('.clearFormButton').length ) { 
		$('.clearFormButton').click(function () {
			bootbox.confirm('Are you sure you want to clear this form?', function(result) {
				if ( result == true ) {
					$(this).parents('form:first').clearForm();
				}
			});
		});
	}	
	
	if ( $("input.minicolors").length ) { 
		$('input.minicolors').minicolors({
			control: 'hue',
			defaultValue: $(this).attr('value') || '',
			inline: $(this).attr('data-inline') === 'true',
			letterCase: 'lowercase',
			opacity: $(this).attr('data-opacity'),
			position: 'bottom left',
			change: function(hex, opacity) {
				var log;
				try {
					log = hex ? hex : 'transparent';
					if( opacity ) log += ', ' + opacity;
					console.log(log);
				} catch(e) {}
			},
			theme: 'bootstrap'
		});
	}
	
	if ( $("input.icon-picker").length ) { 
		$('input.icon-picker').iconPicker();
	}
	
	if ( $("input.toggle").length ) { 
		$('input.toggle').bootstrapSwitch();
	}
	
	if ( $("ul#graphLinks").length ) {	
		var ctx = $("#graphsChart").get(0).getContext("2d");
		
		$('ul#graphLinks a').click(function ( e ) {
			e.preventDefault();
			$('#builtinGraphResponse').html( '' );
			$('#graphTitle').html( $(this).html() );
			
			var myNewChart = new Chart(ctx);
			window[ $(this).attr('id') ]( ctx ); // Call the function
		});
	}
	
	if ( $("div.selectionPreview").length ) {	
		$("div.selectionPreview").each(function() {
			// Determine our settings
			var id = this.id;
			var selectBox = id.replace('Preview', '');
			
			$('select#' + selectBox).change(function () {
				// Reset the preview box
				$('#' + id).attr('class', "selectionPreview");
				$('#' + id + ' img').attr('class', '').show();
				
				// Try and get a JS script to show the preview for the item
				$.getScript( SITE_URL + '/ajax.php?action=showSelectionPreview&selectionID=' + selectBox + '&value=' + $( this ).val() );		
			}).change(); // Trigger the change so we get an accurate preview on page load 
		});	
	}
	
	if ( $("#changePasswordLink").length ) {	
		$("#changePasswordLink").click(function (e) {
			e.preventDefault();
			bootbox.dialog({
				message: '<form name="changePasswordForm" id="changePasswordForm" action="" method="post" class="form-horizontal" role="form" onsubmit="return false;"><fieldset><div class="form-group"><label for="password" class="control-label col-lg-4">Password <sup>*</sup> </label> <div class="col-lg-8"><input type="password" name="password" id="password" class="form-control required" size="60" value="" /></div></div><div class="form-group"><label for="password2" class="control-label col-lg-4">Confirm Password <sup>*</sup> </label> <div class="col-lg-8"><input type="password" name="password2" id="password2" class="form-control required" size="60"   value="" /></div></div></fieldset></form>',
				title: "Change Password",
				buttons: {
					success: {
						label: "Change Password",
						className: "btn-success",
						callback: function() {
							jQuery.post(SITE_URL + '/ajax.php?action=changePassword', $("#changePasswordForm").serialize(), function(data) {
								bootbox.alert(data);
							});
						}
					},
					danger: {
						label: "Cancel",
						className: "btn-default"
					},
				}
			});
		});		
	}
	
	// Generate Password
	if ( $(".generatePasswordLink").length ) {
		$(".generatePasswordLink").click(function (e) {		
			var self = this;
			e.preventDefault();
				
			jQuery.post(SITE_URL + '/ajax.php?action=generatePassword', function(data) {
				bootbox.dialog({
					message: '<h2 class="generatedPassword">' + data + '</h2>',
					title: "Generate Password",
					buttons: {
						success: {
							label: '<span class="glyphicon glyphicon-check"></span> Use This Password',
							className: "btn-success",
							callback: function() {
								$(self).parent().parent().find(":password").val( $('.generatedPassword').html() ).effect("highlight", {}, 500);
							}
						},
						regen: {
							label: '<span class="glyphicon glyphicon-refresh"></span> Regenerate',
							className: "btn-info",
							callback: function() {
								updateGeneratedPassword('.generatedPassword');
								return false;
							}

						},
					}
				});
			});
		});
	}
	
	// Password strength
	if ( $(":password.showStrength").length ) {
		var options = {};
		options.ui = {
			container: "#pwd-container",
			showVerdictsInsideProgressBar: true,
			viewports: {
				progress: ".pwstrength_viewport_progress"
			}
		};
		options.common = {
			debug: true,
			onLoad: function () {
				$('#messages').text('Start typing password');
			}
		};
	    $(':password.showStrength').pwstrength(options);
	}
});	

/*-------------------------------------------------------------------------*/
// Ajax Functions
/*-------------------------------------------------------------------------*/	
function ajaxDeleteNotifier(spinDivID, action, text, row) {
	bootbox.confirm('Are you sure you want to delete this ' + text + '?', function(result) {
		if ( result == true ) {
			$('#' + spinDivID).toggle();	
			jQuery.get(action, function(data) { $('#' + row).hide(); });
		}
	});
}
function ajaxGetWithProgress(spinDivID, action) {
		$('#' + spinDivID).toggle();	
		jQuery.get(action, function(data) { $('#' + spinDivID).toggle(); });
}
function ajaxQuickDivUpdate(action, divID, spinnerHTML) {
	jQuery.get(action, function(data) {
		// Clear the current graph and show the new one
		$('#' + divID).html(spinnerHTML);
		$('#' + divID).html(data);
	});
}
$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form')
			return $(':input',this).clearForm();
		if (type == 'text' || type == 'password' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = -1;
	});
};
function returnSuccessMessage(itemName) { 
    return "<span class=\"greenText bold\">Successfully created " + itemName + "!</span>";
}

/*-------------------------------------------------------------------------*/
// Ajax Functions - Modules
/*-------------------------------------------------------------------------*/
function ajaxInstallModule(prefix) {
	var holderDivID = 'statusButtonHolder_' + prefix;
	
	$('#' + holderDivID).html('Loading...');
	jQuery.get(SITE_URL + '/ajax.php?action=installModule&prefix=' + prefix, function(data) {
		jQuery.get(SITE_URL + '/ajax.php?action=showModuleStatusButtons&prefix=' + prefix, function(data) {
			$('#' + holderDivID).html(data);
		});
	});
}
function ajaxUninstallModule(prefix) {
	var holderDivID = 'statusButtonHolder_' + prefix;
	
	$('#' + holderDivID).html('Loading...');
	jQuery.get(SITE_URL + '/ajax.php?action=uninstallModule&prefix=' + prefix, function(data) {
		jQuery.get(SITE_URL + '/ajax.php?action=showModuleStatusButtons&prefix=' + prefix, function(data) {
			$('#' + holderDivID).html(data);
		});
	});
}
function ajaxActivateModule(prefix) {
	var holderDivID = 'statusButtonHolder_' + prefix;
	
	$('#' + holderDivID).html('Loading...');
	jQuery.get(SITE_URL + '/ajax.php?action=activateModule&prefix=' + prefix, function(data) {
		jQuery.get(SITE_URL + '/ajax.php?action=showModuleStatusButtons&prefix=' + prefix, function(data) {
			$('#' + holderDivID).html(data);
		});
	});
}
function ajaxDeactivateModule(prefix) {
	var holderDivID = 'statusButtonHolder_' + prefix;
	
	$('#' + holderDivID).html('Loading...');
	jQuery.get(SITE_URL + '/ajax.php?action=deactivateModule&prefix=' + prefix, function(data) {
		jQuery.get(SITE_URL + '/ajax.php?action=showModuleStatusButtons&prefix=' + prefix, function(data) {
			$('#' + holderDivID).html(data);
		});
	});
}


/*-------------------------------------------------------------------------*/
// User related Functions
/*-------------------------------------------------------------------------*/
function updateGeneratedPassword(selector) {
	$(selector).html('Generating...');
	jQuery.get(SITE_URL + '/ajax.php?action=generatePassword', function(data) {
		$(selector).html(data);
	});
}

/*-------------------------------------------------------------------------*/
// Helper Functions
/*-------------------------------------------------------------------------*/
function addEditable( fields, options ) {	
	$.each(fields, function() {
		// Determine our settings
		var id = this;
		var table = id.replace(/edit-([a-zA-Z_]+?)-([0-9]+?)_(.*)/, '$1');
		var dbID  = id.replace(/edit-([a-zA-Z_]+?)-([0-9]+?)_(.*)/, '$2');
		var name  = id.replace(/edit-([a-zA-Z_]+?)-([0-9]+?)_(.*)/, '$3');
		
		default_options = {
			id        : 'cssID',
			cancel    : '<button class="btn btn-default btn-sm editable-cancel" type="button"><i class="glyphicon glyphicon-remove"></i></button>',
			submit    : '<button class="btn btn-primary btn-sm editable-submit" type="submit"><i class="glyphicon glyphicon-ok"></i></button>',					    
			indicator : indicatorImage,
			tooltip   : 'Click to edit...',
			style     : 'display: inline;',
			width     : 'none'
		};
		options = $.extend({}, default_options, options);
		if ( options.loadurl ) { options.loadurl += '&id=' + dbID; }
		
		$('#' + id).addClass('editableItemHolder').editable( 
			SITE_URL + '/ajax.php?action=updateitem&table=' + table + '&item=' + name + '&id=' + dbID, 
			options
		);
	});
}	
/*
function addEditable( fields, table ) {
	$.each(fields, function() {
		// Determine our settings
		id = this;
		dbID = id.replace(/edit-([0-9]+?)_(.*)/, '$1');
		name = id.replace(/edit-([0-9]+?)_(.*)/, '$2');
		
		$('#' + id).editable({
			type: 'text',
			pk: dbID,
			name: name,
			url: SITE_URL + '/ajax.php?action=updateitem&table=' + table,
			title: 'Enter Text',
		});
	});
}	
function addEditableSelect( fields, source ) {
	$.each(fields, function() {
		// Determine our settings
		id = '';
		name = '';
		table = '';
		
		$(this).editable({
			type: 'select',
			pk: id,
			name: name,
			source: source,
			url: SITE_URL + '/ajax.php?action=updateitem&table=' + table,
		});
	});
}
*/