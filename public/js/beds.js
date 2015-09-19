$(function() {
	switch(php.role) {
		case 'master':
		 	// wards datatable (GLOBAL)
			wardsTable = $('#table_wards').on('init.dt', function() {
				$('#table_loading').css('display', 'none');
			}).DataTable({
				data: php.wards,
				deferRender: true,
				columns: [
					{
						data: 'id'
					}, {
						data: 'identification'
					}, {
						data: 'capacity'
					}, {
						data: null,
						"render": function(data) {
							var beds = '';
							for (var i = data.beds.length - 1; i >= 0; i--) {
								beds += ', ' + data.beds[i].identification;
							};

							if(beds)
								return beds.substr(2);
							else
								return 'none';
						}
					}
				],
				"createdRow": function(row, data, index) {
					$(row).addClass('selectable');
				}
			});

			// beds datatable (GLOBAL)
			bedsTable = $('#table_beds').on('init.dt', function() {
				$('#table_loading2').css('display', 'none');
			}).DataTable({
				data: php.beds,
				deferRender: true,
				columns: [
					{
						data: 'id'
					}, {
						data: 'identification'
					}, {
						data: null,
						"render": function(data) {
							if(data.ward) {
								return data.ward.identification;
							} else {
								return 'none';
							}
						}
					}
				],
				"createdRow": function(row, data, index) {
					$(row).addClass('selectable');
				}
			});
		break;

		case 'receptionist':
		 	// wards datatable (GLOBAL)
			wardsTable = $('#table_wards').on('init.dt', function() {
				$('#table_loading').css('display', 'none');
			}).DataTable({
				data: php.wards,
				deferRender: true,
				columns: [
					{
						data: 'id'
					}, {
						data: 'identification'
					}, {
						data: 'capacity'
					}, {
						data: null,
						"render": function(data) {
							var beds = '';
							for (var i = data.beds.length - 1; i >= 0; i--) {
								beds += ', ' + data.beds[i].identification;
							};

							if(beds)
								return beds.substr(2);
							else
								return 'none';
						}
					}
				],
				"createdRow": function(row, data, index) {
					$(row).addClass('selectable');
				}
			});

			// beds datatable (GLOBAL)
			bedsTable = $('#table_beds').on('init.dt', function() {
				$('#table_loading2').css('display', 'none');
			}).DataTable({
				data: php.beds,
				deferRender: true,
				columns: [
					{
						data: 'id'
					}, {
						data: 'identification'
					}, {
						data: null,
						"render": function(data) {
							if(data.ward) {
								return data.ward.identification;
							} else {
								return 'none';
							}
						}
					}, {
						data: null,
						render: function(data) {
							if(data.patient) {
								return data.patient.id_number + ' - ' + data.patient.name;
							} else {
								return '<strong>AVAIBLE</strong>';
							}
						}
					}
				],
				"createdRow": function(row, data, index) {
					$(row).addClass('selectable');
				}
			});

			patientsTable = $('#table_patients').DataTable({
				data: php.patients,
				deferRender: true,
				columns: [
					{
						data: 'id'
					}, {
						data: 'name'
					}, {
						data: 'id_number'
					}
				],
				"createdRow": function(row, data, index) {
					$(row).addClass('selectable');
				}
			});
		break;
	}

	// Assign patient to bed
	$('#assignPatientBtn').click(function(event) {
		var row = bedsTable.row('.selected').data();

		if(row) {
			$('#assignPatientModal').modal();
		} else {
			alert('Choose a bed');
		}
	});

	// Confirm assignation
	$('#assignPatientConfirm').click(function(event) {
		event.preventDefault();
		var row = bedsTable.row('.selected'),
			 patient = patientsTable.row('.selected').data();

		if(patient) {
			$.ajax({
				url: '/beds/'+row.data().id,
				type: 'PUT',
				data: {
					_token: $('input[name="_token"]').val(),
					patient: patient.id,
					action: 'assign'
				},
			})
			.done(function(data) {
				if(data.result == 'success') {
					row.data(data.row).draw();
					$modal.modal('hide');
					alert(data.msg);
				} else {
					alert('An error happened, check the input.');
				}
			})
			.fail(function() {
				alert('An error happened, check the input.');
			})			
		} else {
			alert('Choose a patient');
		}		
	});

	// Confirm assignation
	$('#releasePatient').click(function(event) {
		event.preventDefault();
		var row = bedsTable.row('.selected');

		if(row.data()) {
			$.ajax({
				url: '/beds/'+row.data().id,
				type: 'PUT',
				data: {
					_token: $('input[name="_token"]').val(),
					action: 'release'
				},
			})
			.done(function(data) {
				if(data.result == 'success') {
					row.data(data.row).draw();
					alert(data.msg);
				} else {
					alert('An error happened, check the input.');
				}
			})
			.fail(function() {
				alert('An error happened, check the input.');
			})			
		} else {
			alert('Choose a bed');
		}		
	});

	// Open new bed modal
	$('#bedNewBtn').click(function(event) {
		cleanItemModal('bed');
		$('form').attr({
					method: 'POST',
					action: '/beds'
				});
		$('#newItemModal').modal();
	});

	// Open new ward modal
	$('#wardNewBtn').click(function(event) {
		cleanItemModal('ward');
		$('form').attr({
					method: 'POST',
					action: '/beds'
				});
		$('#newItemModal').modal();
	});

	// Open ward edit modal
	$('#wardEditBtn').click(function(event) {
		var data = wardsTable.row('.selected').data(),
			$form = $('form');

		if(data) {
			cleanItemModal('ward');

			$form.find('#identification').val(data.identification).end()
				.find('#capacity').val(data.capacity).end()
				.attr({
					method: 'PUT',
					action: '/beds/'+data.id
				});

			$('#newItemModal').modal();
		} else {
			alert('Choose a ward');
		}
	});

	// Open bed edit modal
	$('#bedEditBtn').click(function(event) {
		var data = bedsTable.row('.selected').data(),
			$form = $('form');

		if(data) {
			cleanItemModal('bed');

			$form.find('#identification').val(data.identification).end()
				.find('#ward_id').val(data.ward_id).end()
				.attr({
					method: 'PUT',
					action: '/beds/'+data.id
				});

			$('#newItemModal').modal();
		} else {
			alert('Choose a bed');
		}
	});

	// Open ward delete modal
	$('#wardDeleteBtn').click(function(event) {
		event.preventDefault();
		if(wardsTable.row('.selected').data()) {
			$('#itemType').val('ward');
			$('#itemDeleteModal').find('h4').find('span').html('ward');
			$('#itemDeleteModal').modal();
		} else {
			alert('Choose a Ward');
		}
	});

	// Open bed delete modal
	$('#bedDeleteBtn').click(function(event) {
		event.preventDefault();
		if(bedsTable.row('.selected').data()) {
			$('#itemType').val('bed');
			$('#itemDeleteModal').find('h4').find('span').html('bed');
			$('#itemDeleteModal').modal();
		} else {
			alert('Choose a Bed');
		}
	});

	// Delete item
	$('#itemDeleteBtn').click(function(event) {
		event.preventDefault();
		var type = $('#itemType').val(),
			 row = (type == 'ward') ? wardsTable.row('.selected') : bedsTable.row('.selected');

		$.post('/beds/'+row.data().id, {_method: 'delete', _token: $('input[name="_token"]').val(), type: type}, function(data, textStatus, xhr) {
			if(data.result == 'success') {
				row.remove().draw(false);
				$('#itemDeleteModal').modal('hide');
				alert(data.msg);
			} else {
				alert('Oops, try again.');
			}
		});
	});

	// POST Added or edited staff member
	$('#saveItemBtn').click(function(event) {
		event.preventDefault();
		var $submitBtn = $(this),
			$form = $('form'),
			$alert = $('.alert').css('display', 'none'),
			errors = '',
			type = $('#type').val();

		$('div.form-group').removeClass('has-error');
		$submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving').prop('disabled', true);

		$.ajax({
			url: $form.attr('action'),
			method: $form.attr('method'),
			data: $form.serialize(),
		}).done(function(data) {
			if(data.result == 'success') {
				// If it's an update
				if ($form.attr('method') == 'PUT') {
					// Updating the row
					if(type == 'ward') {
						wardsTable.row('.selected').data(data.row).draw();
					} else {
						php.wards = data.ward;
						for (var i = wardsTable.data().length - 1; i >= 0; i--) {
							wardsTable.row(i).data(data.ward[i]);
						};
						wardsTable.rows().draw();
						bedsTable.row('.selected').data(data.row).draw();
					}
				} else { // If it's a new reg
					// Adding the row
					if(type == 'ward') {
						wardsTable.rows.add(data.row).draw()
					} else {
						if(data.ward) {
							wardsTable.data(data.ward).draw();
						}
						bedsTable.rows.add(data.row).draw();
					}

					// Clean the form
					cleanItemModal(type);
				}
				// Showing the alert
				alertState($alert, true, data.msg, null);
			} else {
				// An error...
				alertState($alert, false, '<strong>Error!!!</strong> Something went wrong, please try again.', null);
			}
		}).fail(function(response) {
			if (response.status == 422) {
				var data = JSON.parse(response.responseText);
				$.each(data.errors, function(index, val) {
					$('#' + index).closest('div.form-group').addClass('has-error');
					errors += '<li>' + val + '</li>';
				});
				// Showing the errors
				alertState($alert, false, '<strong>Errors!!!</strong> Verify your input information.', errors);
			} else {
				alertState($alert, false, '<strong>Error!!!</strong> Something went wrong, please try again.', null);
			}
		}).always(function() {
			$alert.show('400', function() {
				// Button enabled
				$submitBtn.html('Save changes').prop('disabled', false);
			});
		});
	});

	// Return form to original state
	function cleanItemModal(type) {
		$('form')[0].reset();
		$('.alert').css('display', 'none');
		$('div.form-group').removeClass('has-error');
		$('#type').val(type);

		if(type == 'bed') {
			var wards = '';
			for (var i = 0; i < php.wards.length; i++) {
				if(Number(php.wards[i].capacity) > php.wards[i].beds.length)
					wards += '<option value="' +php.wards[i].id+ '">'+ php.wards[i].identification+ '</option>';
			};
			$('#ward_id').html('<option val="">None</option>' + wards);			
		}

		if(type == 'ward' && $('#wardField').css('display') == 'none') {
			$('#wardField').css('display', 'block').find('input').prop('disabled', false);
			$('#bedField').css('display', 'none').find('select').prop('disabled', true);
		} else if(type == 'bed' && $('#bedField').css('display') == 'none') {
			$('#bedField').css('display', 'block').find('select').prop('disabled', false);
			$('#wardField').css('display', 'none').find('input').prop('disabled', true);			
		}
	}
});