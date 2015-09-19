$(function() {
	// Table selected row event
	$('table > tbody').on('click', 'tr', function() {
		if ($(this).hasClass('selectable')) {
			if ($(this).hasClass('selected')) {
				$(this).removeClass('selected');
			} else {
				var $table = $(this).closest('table');
				if($table.hasClass('footable')) {
					$(this).addClass('selected').siblings('tr.selected').removeClass('selected');
				} else {
					$table.DataTable().$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
				}
			}
		}
	});

	if(php.role == 'master') {
		// Datepicker field
		$('#birth_date').pikaday({ 
			firstDay: 1,
			minDate: new Date('1920-01-01'),
	      maxDate: new Date('2010-12-31'),
	      defaultDate: new Date('1980-01-01'),
	      setDefaultDate: new Date('1980-01-01'),
	      yearRange: [1920,2020]
		});		
	}
	
	// Doctor datatable (GLOBAL)
	doctorsTable = $('#table_doctors').on('init.dt', function() {
		$('#table_loading').css('display', 'none');
	}).DataTable({
		data: php.doctors,
		deferRender: true,
		columns: [
			{
				data: 'id'
			}, {
				data: 'name'
			}, {
				data: 'id_number'
			}, {
				data: 'specialization'
			}, {
				data: 'birth_date'
			}, {
				data: null,
				"render": function(data) {
					return data.time_in + ' - ' + data.time_out;
				}
			}
		],
		"createdRow": function(row, data, index) {
			$(row).addClass('selectable');
		}
	});

	// Nurse datatable (GLOBAL)
	nursesTable = $('#table_nurses').on('init.dt', function() {
		$('#table_loading2').css('display', 'none');
	}).DataTable({
		data: php.nurses,
		deferRender: true,
		columns: [
			{
				data: 'id'
			}, {
				data: 'name'
			}, {
				data: 'id_number',
			}, {
				data: 'birth_date',
			}, {
				data: 'shift'
			}, {
				data: null,
				"render": function(data) {
					var beds = '';
					for (var i = data.beds.length - 1; i >= 0; i--) {
						beds += ', ' + data.beds[i].identification;
					};
					return beds.substr(1);
				}
			}
		],
		"createdRow": function(row, data, index) {
			$(row).addClass('selectable');
		}
	});

	// Open new staff modal
	$('#newStaffBtn').click(function(event) {
		cleanStaffModal();
		$('form').attr({
					method: 'POST',
					action: '/staff'
				});
		$('#newStaffModal').modal();
	});

	// Select doctor type
	$('#staffTypeDoctor').click(function(event) {
		if(!$(this).hasClass('active')) {
			showTypeFields('doctor');
		}
	});

	// Select nurse type
	$('#staffTypeNurse').click(function(event) {
		if(!$(this).hasClass('active')) {
			showTypeFields('nurse');
		}
	});

	// Open doctor edit modal
	$('#doctorEditBtn').click(function(event) {
		var data = doctorsTable.row('.selected').data(),
			$form = $('form');

		if(data) {
			if($('#staffTypeNurse').hasClass('active')) {
				$('#staffTypeNurse').removeClass('active').children('input').prop('checked', false);
				$('#staffTypeDoctor').addClass('active').children('input').prop('checked', true);
				showTypeFields('doctor');
			}	

			$form.find('#name').val(data.name).end()
				.find('#id_number').val(data.id_number).end()
				.find('#birth_date').val(data.birth_date).end()
				.find('#specialization').val(data.specialization).end()
				.find('#time_in').val(data.time_in).end()
				.find('#time_out').val(data.time_out).end()
				.attr({
					method: 'PUT',
					action: '/staff/'+data.id
				});

			$('#newStaffModal').modal();
		} else {
			alert('Choose a doctor');
		}
	});

	// Open nurse edit modal
	$('#nurseEditBtn').click(function(event) {
		var data = nursesTable.row('.selected').data(),
			$form = $('form');

		if(data) {
			if($('#staffTypeDoctor').hasClass('active')) {
				$('#staffTypeDoctor').removeClass('active').children('input').prop('checked', false);
				$('#staffTypeNurse').addClass('active').children('input').prop('checked', true);
				showTypeFields('nurse');
			}	

			$form.find('#name').val(data.name).end()
				.find('#id_number').val(data.id_number).end()
				.find('#birth_date').val(data.birth_date).end()
				.find('#specialization').val(data.shift).end()
				.attr({
					method: 'PUT',
					action: '/staff/'+data.id
				});

			$('#newStaffModal').modal();
		} else {
			alert('Choose a nurse');
		}
	});

	// Open doctor delete modal
	$('#doctorDeleteBtn').click(function(event) {
		event.preventDefault();
		var data = doctorsTable.row('.selected').data();
		if(data) {
			$('#dStaffType').val('doctor');
			$('#staffDeleteModal').modal();
		} else {
			alert('Choose a Doctor');
		}
	});

	// Open nurse delete modal
	$('#nurseDeleteBtn').click(function(event) {
		event.preventDefault();
		var data = nursesTable.row('.selected').data();
		if(data) {
			$('#dStaffType').val('nurse');
			$('#staffDeleteModal').modal();
		} else {
			alert('Choose a Nurse');
		}
	});

	// Disable staff member
	$('#staffDeleteBtn').click(function(event) {
		event.preventDefault();
		var type = $('#dStaffType').val(),
			 row = (type == 'doctor') ? doctorsTable.row('.selected') : nursesTable.row('.selected');

		$.post('/staff/'+row.data().id, {_method: 'delete', _token: $('input[name="_token"]').val(), type: type}, function(data, textStatus, xhr) {
			if(data.result == 'success') {
				row.remove().draw(false);
				$('#staffDeleteModal').modal('hide');
				alert(data.msg);
			} else {
				alert('Oops, try again.');
			}
		});
	});

	// POST Added or edited staff member
	$('#saveStaffMember').click(function(event) {
		event.preventDefault();
		var $submitBtn = $(this),
			$form = $('form'),
			$alert = $('#newStaffAlert'),
			errors = '',
			staffType = ($('#staffTypeDoctor').hasClass('active')) ? 'doctor' : 'nurse';
			$form.find('div.has-error').removeClass('has-error'),
			beds = [];

			$alert.css('display', 'none');

		$submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving').prop('disabled', true);

		if(staffType == 'nurse') {
			$.each($('#selectedBeds option'), function(index, val) {
				beds.push(val.value);
			});
		}

		$.ajax({
			url: $form.attr('action'),
			method: $form.attr('method'),
			data: $form.serialize()+'&beds='+JSON.stringify(beds),
		}).done(function(data) {
			if(data.result == 'success') {
				if ($form.attr('method') == 'PUT') {
					if(staffType == 'doctor') {
						doctorsTable.row('.selected').data(data.row).draw();
					} else {
						nursesTable.row('.selected').data(data.row).draw();
					}
				} else {
					if(staffType == 'doctor') {
						doctorsTable.rows.add(data.row).draw()
					} else {
						nursesTable.rows.add(data.row).draw();
					}
					// Clean form
					cleanStaffModal();
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

	// Asign bed to nurse
	$('#addBed').click(function(event) {
		event.preventDefault();
		var bed = $('#beds option:selected');
		var beds = $('#selectedBeds');

		if(bed.length) {
			if(!beds.find('option[value="'+bed.val()+'"]').length) {
				beds.append('<option value="'+bed.val()+'">'+bed.html()+'</option>');
			} else {
				alert('Bed already assigned');
			}
		} else {
			alert('Select a bed');
		}
	});

	// Delete a bed
	$('#minusBed').click(function(event) {
		event.preventDefault();
		var bed = $('#selectedBeds option:selected');

		if(bed.length) {
			bed.remove();
		} else {
			alert('Select a bed');
		}
	});

	// Remove all beds from nurse
	$('#deleteBed').click(function(event) {
		$('#selectedBeds').html('');
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

	// If an error happens...
	function errorDiv($alertDiv) {
		if ($alertDiv.hasClass('alert-success')) {
			$alertDiv.removeClass('alert-success').addClass('alert-danger');
		}
		$alertDiv					
			.children('#alertBody')
			.html('<strong>Error!!!</strong> Something went wrong, please try again.').end()
			.children('ul').html('').end()
			.show('fast', function() {
				$('html, body').animate({
					scrollTop: 0
				}, 400);
			});	
	}

	// Enable(show)/disable(hide) fields by selected type
	function showTypeFields(type) {
		var $nurseDiv = $('#newNurseDiv'), 
			 $doctorDiv = $('#newDoctorDiv');

		if(type == 'doctor') {
			$nurseDiv.fadeOut('400', function() {
				$doctorDiv
					.find('input').prop('disabled', false).end()
					.find('select').prop('disabled', false).end()
					.fadeIn('400');
			}).find('select').prop('disabled', true);
		} else {
			$doctorDiv.fadeOut('400', function() {
				$nurseDiv
					.find('select').prop('disabled', false).end()
					.fadeIn('400');
			}).find('input').prop('disabled', true).end()
			.find('select').prop('disabled', true);
		}
	}

	// Return form to original state
	function cleanStaffModal() {
		$('form')[0].reset();
		$('alert').css('display', 'none');
		if($('#staffTypeNurse').hasClass('active')) {
			$('#staffTypeNurse').removeClass('active').children('input').prop('checked', false);
			$('#staffTypeDoctor').addClass('active').children('input').prop('checked', true);
			showTypeFields('doctor');
		}
	}
});
