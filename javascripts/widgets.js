var mbpWidgets = {
  init: function() {
    var rem, widgetAreas = $('div.widgets-sortables');

    $('#widgets-rightCol').children('.ui-widget').children('.header').click(function() {
      mbpWidgets.collapse(this);
    });

    $('#widgets-rightCol').children('.box').children('.box-header').click(function() {
      mbpWidgets.collapse(this);
    });

    $('#available-widgets').children('.box-header').click(function() {
      mbpWidgets.collapse(this);
    });

    $(document).on('click', 'a.widget-action', function() {
      var css = {}, widget = $(this).closest('div.widget'), inside = widget.children('.widget-inside');

      if (inside.is(':hidden')) {
        inside.slideDown('fast');
      } else {
        inside.slideUp('fast');
      }
      return false;
    });

    $(document).on('click', 'input.widget-control-save', function() {
      mbpWidgets.save($(this).closest('div.widget'), 0, 1, 0);
      return false;
    });

    $(document).on('click', 'a.widget-control-remove', function() {
      mbpWidgets.save($(this).closest('div.widget'), 1, 1, 0);
      return false;
    });

    $(document).on('click', 'a.widget-control-close', function() {
      mbpWidgets.close($(this).closest('div.widget'));
      return false;
    });

    widgetAreas.children('.widget').each(function() {
      if ($('p.widget-error', this).length) {
        $('a.widget-action', this).click();
      }
    });

    $('#widget-list').children('.widget').draggable({
      connectToSortable: 'div.widgets-sortables',
      handle: '> .widget-top > .widget-title',
      distance: 2,
      helper: 'clone',
      zIndex: 5,
      containment: 'document',
      start: function(e, ui) {
        ui.helper.find('div.widget-description').hide();
      },
      stop: function(e, ui) {
        if (rem) {
          $(rem).hide();
        }
        rem = '';
      },
    });

    widgetAreas.sortable({
      placeholder: 'widget-placeholder',
      items: '> .widget',
      handle: '> .widget-top > .widget-title',
      cursor: 'move',
      distance: 2,
      containment: 'document',
      start: function(e, ui) {
        ui.item.children('.widget-inside').hide();
      },
      stop: function(e, ui) {
        if (ui.item.hasClass('ui-draggable') && ui.item.data('draggable')) {
          ui.item.draggable('destroy');
        }

        if (ui.item.hasClass('deleting')) {
          mbpWidgets.save(ui.item, 1, 0, 1); // delete widget
          ui.item.remove();
          return;
        }

        var add = ui.item.find('input.create').val(),
          n = ui.item.find('input.instance_number').val(),
          id = ui.item.find('input.widget-id').val(),
          sb = $(this).attr('id');

        if (add) {
          ui.item.html(ui.item.html().replace(/<[^<>]+>/g, function(m) { return m.replace(/__i__|%i%/g, n); }));
          ui.item.attr('id', ui.item.find('input.widget-id').val()); // The id doesn't changed so pull the proper value from the hidden field
          n++;
          $('div#widget-' + id).find('input.instance_number').val(n); // This will set our next instance value

          mbpWidgets.save(ui.item, 0, 0, 1);
          ui.item.find('input.create').val('');
          ui.item.find('a.widget-action').click();
          return;
        }
        mbpWidgets.saveOrder(sb);
      },
      receive: function(e, ui) {
        if (!$(this).is(':visible')) {
          $(this).sortable('cancel');
        }
      },
    }).sortable('option', 'connectWith', 'div.widgets-sortables').parent().filter('.closed').children('.widgets-sortables').sortable('disable');

    $('#available-widgets').droppable({
      tolerance: 'pointer',
      accept: function(o) {
        return $(o).parent().attr('id') != 'widget-list';
      },
      drop: function(e, ui) {
        ui.draggable.addClass('deleting');
        $('#removing-widget').hide().children('span').html('');
      },
      over: function(e, ui) {
        ui.draggable.addClass('deleting');
        $('div.widget-placeholder').hide();

        if (ui.draggable.hasClass('ui-sortable-helper')) {
          $('#removing-widget').show().children('span')
            .html(ui.draggable.find('div.widget-title').children('h4').html());
        }
      },
      out: function(e, ui) {
        ui.draggable.removeClass('deleting');
        $('div.widget-placeholder').show();
        $('#removing-widget').hide().children('span').html('');
      },
    });
  },

  saveOrder: function(sb) {
    if (sb) {
      $('#' + sb).closest('div.widgets-holder-wrap').find('img.ajax-feedback').css('visibility', 'visible');
    }

    var a = {
      action: 'widgets-order',
      widgetAreas: [],
    };

    $('div.widgets-sortables').each(function() {
      a['widgetAreas[' + $(this).attr('id') + ']'] = $(this).sortable('toArray').join(',');
    });

    $.post(SITE_URL + '/ajax.php', a, function() {
      $('.ajax-feedback img').css('visibility', 'hidden');
    });
  },

  save: function(widget, del, animate, order) {
    var sb = widget.closest('div.widgets-sortables').attr('id'), data = widget.find('form').serialize(), a;
    widget = $(widget);
    $('.ajax-feedback img', widget).css('visibility', 'visible');
    $('.ajax-feedback span', widget).html('');

    a = {
      action: 'save-widget',
      area: sb,
    };

    if (del) {
      a['delete_widget'] = 1;
    }

    data += '&' + $.param(a);

    $.post(SITE_URL + '/ajax.php', data, function(r) {
      var id;

      if (del) {
        if (animate) {
          order = 0;
          widget.slideUp('fast', function() {
            $(this).remove();
            mbpWidgets.saveOrder();
          });
        } else {
          widget.remove();
        }
      } else {
        $('.ajax-feedback img').css('visibility', 'hidden');
        if (r && r.length > 2) {
          $('.ajax-feedback span', widget).html(r);
        }
      }
      if (order) {
        mbpWidgets.saveOrder();
      }
    });
  },

  close: function(widget) {
    widget.children('.widget-inside').slideUp('fast');
  },

  collapse: function(itemToCollapse) {
    var c = $(itemToCollapse).siblings('.widgets-sortables'),
      p = $(itemToCollapse).parent();
    if (!p.hasClass('closed')) {
      c.sortable('disable');
      p.addClass('closed');
    } else {
      p.removeClass('closed');
      c.sortable('enable').sortable('refresh');
    }
  }
};