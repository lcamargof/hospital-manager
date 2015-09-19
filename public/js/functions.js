$(function() {
	// Table selected row event
	$('table > tbody').on('click', 'tr', function() {
		if ($(this).hasClass('selectable')) {
			if ($(this).hasClass('selected')) {
				$(this).removeClass('selected');
			} else {
				var $table = $(this).closest('table');
				$table.DataTable().$('tr.selected').removeClass('selected');
				$(this).addClass('selected');
			}
		}
	});
});

function alertState($alert, state, msg, errors) {
	// Alert state
	if(state) {
		if ($alert.hasClass('alert-danger')) {
			$alert.removeClass('alert-danger').addClass('alert-success');
		}
	} else {
		if ($alert.hasClass('alert-success')) {
			$alert.removeClass('alert-success').addClass('alert-danger');
		}			
	}

	// Alert msg
	$alert.children('#alertBody').html(msg);

	// Error list
	if(errors) {
		$alert.children('ul').html(errors);
	} else {
		$alert.children('ul').html('');
	}
}