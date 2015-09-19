$(function() {
	// Patients datatable
	patientsTable = $('#table_patients').on('init.dt', function() {
		$('#table_loading').css('display', 'none');
	}).DataTable({
		data: php.patients,
		deferRender: true,
		columns: [
			{
				data: 'id'
			}, {
				data: 'name'
			}, {
				data: 'id_number'
			}, {
				data: 'phone'
			}, {
				data: null,
				"render": function(data) {
					if(data.bed && data.bed.ward) {
						return data.bed.ward.identification;
					} else {
						return 'None';
					}
				}
			}, {
				data: null,
				render: function(data) {
					if(data.bed) {
						return data.bed.identification;
					} else {
						return 'None';
					}
				}
			}
		],
		"createdRow": function(row, data, index) {
			$(row).addClass('selectable');
		}
	});

	if(php.role == 'receptionist') {
		$('#birth_date').pikaday({ 
			firstDay: 1,
			minDate: new Date('1920-01-01'),
	      maxDate: new Date('2010-12-31'),
	      defaultDate: new Date('1980-01-01'),
	      setDefaultDate: new Date('1980-01-01'),
	      yearRange: [1920,2020]
		});	
	}

	// Show patient *NEW* modal
	$('#patientNewBtn').click(function(event) {
		$('form')[0].reset();
		$('form').attr({
			action: '/patients',
			method: 'POST'
		});
		$('#patientNewModal').modal();
	});

	// Show patient *DETAIL* modal
	$('#patientDetailBtn').click(function(event) {
		var data = patientsTable.row('.selected').data();

		if(data) {
			var html =  '<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Name</dt>'
										+'<dd>'+data.name+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>ID number</dt>'
										+'<dd>'+data.id_number+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Gender</dt>'
										+'<dd>'+data.gender+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Birth date</dt>'
										+'<dd>'+data.birth_date+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Blood type</dt>'
										+'<dd>'+data.blood_type+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Phone</dt>'
										+'<dd>'+data.phone+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Address</dt>'
										+'<dd>'+data.address+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Allergies</dt>'
										+'<dd>'+data.allergies+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-12">'
									+'<dl>'
										+'<dt>Observations</dt>'
										+'<dd>'+data.observations+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>';

			$('#patientDetailModal').find('.modal-body').html(html).end().modal();
		} else {
			alert('Choose a patient');
		}
	});

	// Show patient *EDIT* modal
	$('#patientEditBtn').click(function(event) {
		var data = patientsTable.row('.selected').data();

		if(data) {
			$('form').attr({
				action: '/patients/'+data.id,
				method: 'PUT'
			})
			.find('#name').val(data.name).end()
			.find('#id_number').val(data.id_number).end()
			.find('#gender').val(data.gender).end()
			.find('#birth_date').val(data.birth_date).end()
			.find('#blood_type').val(data.blood_type).end()
			.find('#phone').val(data.phone).end()
			.find('#address').val(data.address).end()
			.find('#allergies').val(data.allergies).end()
			.find('#observations').val(data.observations);

			$('#patientNewModal').modal();
		} else {
			alert('Choose a patient');
		}
	});

	// Show patient *DELETE* modal
	$('#patientDeleteBtn').click(function(event) {
		var data = patientsTable.row('.selected').data();

		if(data) {
			$('#patientDeleteModal').modal();
		} else {
			alert('Choose a patient');
		}
	});

	// Remove a patient
	$('#deletePatientBtn').click(function(event) {
		event.preventDefault();
		var row = patientsTable.row('.selected');

		$.post('/patients/'+row.data().id, {_method: 'delete', _token: $('input[name="_token"]').val()}, function(data, textStatus, xhr) {
			if(data.result == 'success') {
				row.remove().draw(false);
				$('#patientDeleteModal').modal('hide');
				alert(data.msg);
			} else {
				alert(data.msg);
			}
		});
	});

	// Create or Edit the patient
	$('#newPatientBtn').click(function(event) {
		event.preventDefault();
		var $submitBtn = $(this),
			$form = $('form'),
			$alert = $('.alert').css('display', 'none'),
			errors = '';

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
					patientsTable.row('.selected').data(data.row).draw();
				} else { // If it's a new reg
					// Adding the row
					patientsTable.rows.add(data.row).draw()
					// Clean the form
					$form[0].reset();
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
});