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

	// users datatable (GLOBAL)
	usersTable = $('#table_users').on('init.dt', function() {
		$('#table_loading2').css('display', 'none');
	}).DataTable({
		data: php.users,
		deferRender: true,
		columns: [
			{
				data: 'id'
			}, {
				data: 'user'
			}, {
				data: 'role'
			}, {
				data: null,
				"render": function(data) {
					return (data.doctor) ? data.doctor.name : 'None';
				}
			}, {
				data: null,
				"render": function(data) {
					return (data.last_login) ? data.last_login : 'None';
				}
			}
		],
		"createdRow": function(row, data, index) {
			$(row).addClass('selectable');
		}
	});

	$('#userEditBtn').click(function(event) {
		event.preventDefault();
		var row = usersTable.row('.selected').data();

		if(row) {
			$('#password').val('');
			$('form').attr('action', '/users/'+row.id);
			$('#changePasswordModal').modal();
		} else {
			alert('Select a user');
		}
	});

	$('#saveNewPassword').click(function(event) {
		event.preventDefault();
		$form = $('form');
		$modal = $('#changePasswordModal');

		$.ajax({
			url: $form.attr('action'),
			type: $form.attr('method'),
			data: $form.serialize(),
		})
		.done(function(data) {
			if(data == 'success') {
				$modal.modal('hide');
				alert('Password changed.');
			} else {
				alert('An error happened, check the input.');
			}
		})
		.fail(function() {
			alert('An error happened, check the input.');
		})
	});
});