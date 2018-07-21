$(document).ready(function(){
	$ssw_point = $('<div id="small-screen-width-point" style="position:absolute;top:-10000px;width:10px;height:10px;background:#fff;"></div>');
	$tsw_point = $('<div id="tablet-screen-width-point" style="position:absolute;top:-10000px;width:10px;height:10px;background:#fff;"></div>');
	$('body').append($ssw_point).append($tsw_point);

	/*
	* Detect screen size.
	* 
	* @param  {jQuery Object} $ssw_point
	* @param  {jQuery Object} $tsw_point
	* @return {String}
	*/
	
	window.getScreenSize = function($ssw_point, $tsw_point) {
		if ($ssw_point.is(':visible')) {
			return 'small';
		} else if ($tsw_point.is(':visible')) {
			return 'tablet';
		} else {
			return 'desktop';
		}
	};
	
	window.elHasClass = function(el, selector) {
		return (" " + el.className + " ").indexOf(" " + selector + " ") > -1;
	};
	
	window.elRemoveClass = function(el, selector) {
		return el.className = (" " + el.className + " ").replace(" " + selector + " ", ' ').trim();
	};
	
	// Handle showing/hiding the main menu
	$('#main-menu-toggle').click( function() {
	    var cls, collapse,
	    	screen = window.getScreenSize($('#small-screen-width-point'), $('#tablet-screen-width-point'));
	    
	    cls = screen === 'small' || screen === 'tablet' ? 'mme' : 'mmc';
	    if ( $('body').hasClass(cls) ) {
			$('body').removeClass(cls);
	    } else {
	    	$('body').addClass(cls);
	    }
		if (cls !== 'mmc') { 
			$('#main-navbar-collapse').stop().removeClass('in collapsing').addClass('collapse')[0].style.height = '0px';
			return $('#main-navbar .navbar-toggle').addClass('collapsed');
		}
	});
	
	// Handle main menu dropdowns
	$('.mm-dropdown > a').click( function(event) {
		event.preventDefault();
		var $parent = $(this).parent('.mm-dropdown');
		
	    if ( $parent.hasClass( 'open' ) ) {
		    $parent.removeClass( 'open' )
	    } else {
		    $parent.addClass( 'open' )
	    }
	});

    // Propagate
    $('body').on('click', '.dropdown.open .dropdown-menu', function(e){
        e.stopPropagation();
    });

    if($('#main-navbar-notifications .notification').length > 4){
        $('#main-navbar-notifications').slimScroll({ height: 250 });
    }

    $('.mark-all-read').click( function(event) {
        event.preventDefault();

        if(confirm('Are you sure you want to mark all notifications as read?') == false) return;

        var el = $(this);

        $.ajax({
            url: el.attr('href') ? el.attr('href') : el.attr('data-href'),
            type: "POST",
            success: function (json){
                var data = $.parseJSON(json);

                if(data['success']){
                    $('.notifications-count').text('0');
                    $('.mark-all-read').fadeOut();

                    var items = $('.widget-notifications').find('.notification');
                    var delay = 0;

                    items.each(function(){
                        var item = $(this);
                        setTimeout(function(){
                            item.addClass('animated fadeOutRightBig').delay(1000).queue(function(){
                                item.remove();
                            });
                        }, delay += 150);
                    });

                    $('#main-navbar-notifications').closest('.slimScrollDiv').height(0);

                    if($('#notificationsTable')){
                        $('#notificationsTable tbody tr').removeClass('unread').addClass('read').find('.mark-read').remove();
                    }
                }
            }
        });
    });

    $('.mark-read').click( function(event) {
        event.preventDefault();

        var el = $(this);

        $.ajax({
            url: el.attr('href'),
            type: "POST",
            success: function (json){
                var data = $.parseJSON(json);

                if(data['success']){
                    var countEl = $('.notifications-count').first();
                    var newCount = parseInt(countEl.text()) - 1;
                    var trEl = el.closest('tr');
                    var id = parseInt(trEl.attr('id'));

                    // must change all elements
                    $('.notifications-count').text(newCount);

                    if(newCount == 0){
                        $('.mark-all-read').fadeOut();
                    }

                    $('#notification-item-' + id).remove();
                    trEl.removeClass('unread').addClass('read');
                    el.remove();
                }
            }
        });

    });

});
