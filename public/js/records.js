$(function() {
	/**
	*
	* ROLE == RECEPTIONIST
	*
	**/
	
	if(php.role == 'receptionist') {
		// Records table
		recordsTable = $('#table_records').on('init.dt', function() {
			$('#table_loading').css('display', 'none');
		}).DataTable({
			data: php.records,
			deferRender: true,
			columns: [
				{
					data: 'id'
				}, {
					data: 'type'
				}, {
					data: 'description'
				}, {
					data: null,
					render: function(data) {
						return data.patient.name+' - '+data.patient.id_number;
					}
				}, {
					data: null,
					"render": function(data) {
						if(data.doctor) {
							return data.doctor.name+' - '+data.doctor.id_number;
						} else {
							return 'None';
						}
					}
				}, {
					data: null,
					render: function(data) {
						return moment(data.date_to).format('MMMM Do YYYY, h a');
					}
				}, {
					data: null,
					render: function(data) {
						if(data.transaction) {
							return data.transaction.amount+'$ - '+data.transaction.method;
						} else {
							return "<strong>PENDING</strong>";
						}
					}
				}
			],
			"createdRow": function(row, data, index) {
				$(row).addClass('selectable');
			}
		});
		// Doctors table for selection
		doctorsTable = $('#table_doctors').DataTable({
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

		// Reset the time fields
		$('#table_doctors').on('click', 'tr', function() {
			$('#date').val('');
			$('#hour').html('');
		});

		// Select a doctor
		$('#doctorSelectBtn').click(function(event) {
			event.preventDefault();
			$('#table_doctors tr').removeClass('selected');
			$('#date').val('');
			$('#hour').html('');
			$('#recordNewModal').modal('hide');
			$('#doctorCalendarModal').modal();
		});

		// Select the doctor
		$('#selectedDoctorBtn').click(function(event) {
			event.preventDefault();
			var doctor = doctorsTable.row('.selected').data(),
				date = $('#hour option:selected').val();

			if(doctor && date) {
				$('#doctor').val(doctor.id+' - '+doctor.name);
				$('#date_to').val(date);
				$('#doctorCalendarModal').modal('hide');
				$('#recordNewModal').modal();
			} else {
				alert('Select the doctor and the date');
			}
		});

		// Show payment record modal
		$('#recordPayBtn').click(function(event) {
			var record = recordsTable.row('.selected').data();

			if(record) {
				$('#amount').val('');
				$('#recordPayModal').modal();
			} else {
				alert('Choose a record');
			}
		});

		// Pay record post
		$('#payRecordtConfirm').click(function(event) {
			var row = recordsTable.row('.selected');

			$.post('/transactions', {
				_token: $('input[name="_token"]').val(),
				amount: $('#amount').val(),
				method: $('#method').val(),
				record_id: row.data().id
			}, function(data, textStatus, xhr) {
				if(data.result == 'success') {
					row.data(data.row).draw();
					$('#recordPayModal').modal('hide');
					alert(data.msg);
				} else {
					alert(data.msg);
				}
			});		
		});
	} else {
		// Records table
		recordsTable = $('#table_records').on('init.dt', function() {
			$('#table_loading').css('display', 'none');
		}).DataTable({
			data: php.records,
			deferRender: true,
			columns: [
				{
					data: 'id'
				}, {
					data: 'type'
				}, {
					data: 'description'
				}, {
					data: null,
					render: function(data) {
						return data.patient.name+' - '+data.patient.id_number;
					}
				}, {
					data: null,
					render: function(data) {
						return moment(data.date_to).format('MMMM Do YYYY, h a');
					}
				}, {
					data: null,
					render: function(data) {
						if(data.transaction) {
							return data.transaction.amount+'$ - '+data.transaction.method;
						} else {
							return "<strong>PENDING</strong>";
						}
					}
				}
			],
			"createdRow": function(row, data, index) {
				$(row).addClass('selectable');
			}
		});
	}

	/**
	*
	* GENERAL
	*
	**/

	// Patients table for selection
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

	// Select the patient
	$('#selectedPatientBtn').click(function(event) {
		event.preventDefault();
		var patient = patientsTable.row('.selected').data();

		if(patient) {
			$('#patient').val(patient.id+' - '+patient.name+' ('+patient.id_number+')');
			$('#selectPatientModal').modal('hide');
			$('#recordNewModal').modal();			
		} else {
			alert('Select a patient');
		}
	});

	// Initialize date
	$('#date').pikaday({ 
		firstDay: 1,
		minDate: new Date('2015-06-01'),
      maxDate: new Date('2020-12-31'),
      yearRange: [2015,2020]
	});

	// Type select change
	$('#type').change(function(event) {
		if($('#type option:selected').val() == 'other') {
			$('#type_other').prop('disabled', false);
		} else {
			$('#type_other').prop('disabled', true);
		}
	});

	// Show the avaible hours by date (change)
	$('#date').change(function(event) {
		var doctor = (php.role == 'receptionist') ? doctorsTable.row('.selected').data() : php.doctor,
			date = $('#date').val();

		// If the date is valid and there is a doctor choosen
		if(moment(date).isValid() && doctor) {
			var avaible_hours = [],
			flag,
			options = '',
			time_in = Number(moment(doctor.time_in, 'HH:mm:ss').format('H'));
			time_out = Number(moment(doctor.time_out, 'HH:mm:ss').format('H'));
			// Get the records from that date and doctor
			records = $.grep(php.records, function(d, i){
				if(php.role == 'receptionist') 
					return (d.doctor 
						&& d.doctor.id == doctor.id 
						&& moment(d.date_to).format('YYYY-MM-DD') == date);
				else 
					return (moment(d.date_to).format('YYYY-MM-DD') == date);
			});

			// For each working hours of the doctor
			for (var i = time_out - 1; i >= time_in; i--) {
				flag = true; // Default flag

				// For each record of that day of the doctor
				for (var j = records.length - 1; j >= 0; j--) {
					// If there is a record of that time, then 
					if(Number(moment(records[j].date_to).format('H')) == i)
						flag = false; // False flag
				};
				// If flag true, then push the time to the array
				if(flag) {
					avaible_hours.push(i);
				}
			};
			// Check if there is hours avaible
			if(avaible_hours.length) {
				// Make the html for the avaible hours
				for (var i = avaible_hours.length - 1; i >= 0; i--) {
					options += "<option value='"
						+moment(date+' '+avaible_hours[i], 'YYYY-MM-DD H').format('YYYY-MM-DD HH:mm:ss')
						+"'>"+moment(avaible_hours[i], 'H').format('h a')
						+" - "+moment(avaible_hours[i], 'H').add(1, 'h').format('h a')
						+"</option>";
				};
				// Fill the select
				$('#hour').html(options);				
			} else {
				$('#hour').html('');
				alert('No avaible hours');	
			}
		} else {
			$('#hour').html('');
			alert('Choose a doctor and input a valid date');
		}
	});


	// Show select patient modal
	$('#selectPatientBtn').click(function(event) {
		event.preventDefault();
		$('#table_patients tr').removeClass('selected');
		$('#recordNewModal').modal('hide');
		$('#selectPatientModal').modal();
	});

	// Show new record modal
	$('#recordNewBtn').click(function(event) {
		$('form')[0].reset();
		$('form').attr({
			action: '/records',
			method: 'POST'
		});
		$('#recordNewModal').modal();
	});

	// Show edit record modal
	$('#recordEditBtn').click(function(event) {
		var data = recordsTable.row('.selected').data();

		if(data) {
			$('form').attr({
				action: '/records/'+data.id,
				method: 'PUT'
			}).find('#description').val(data.description).end()
			.find('#patient').val(data.patient.id+' - '+data.patient.name+' ('+data.patient.id_number+')').end()
			.find('#type').val(data.type).end()
			.find('#type_other').val(data.type_other).end()

			if(php.role == 'doctor') {
				$('#date').val(moment(data.date_to).format('YYYY-MM-DD')).trigger( "change" );
				$('#hour').append("<option value ='"
					+data.date_to+"'>"
					+moment(data.date_to).format('h a')
					+" - "+moment(data.date_to).add(1, 'h').format('h a')
					+"</option>");
				$('#hour').val(data.date_to);
					
			} else {
				$('#doctor').val(data.doctor.id+' - '+data.doctor.name);
				$('#date_to').val(data.date_to);
			}

			$('#recordNewModal').modal();			
		} else {
			alert('Choose a record');
		}
	});

	// Show delete record modal
	$('#recordDeleteBtn').click(function(event) {
		var record = recordsTable.row('.selected');

		if(record.data()) {
			$('#recordDeleteModal').modal();
		} else {
			alert('Choose a record');
		}
	});

	// Show record details
	$('#recordDetailBtn').click(function(event) {
		var data = recordsTable.row('.selected').data();

		if(data) {
			var optional = '';
			if(php.role == 'doctor') {
				optional = 	'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Result</dt>'
										+'<dd>'+data.result+'</dd>'
									+'</dl>'
								+'</div>';
			} else {
				optional = '<div class="col-sm-6">'
								+'<dl>'
									+'<dt>Doctor</dt>'
									+'<dd>'+data.doctor.name+'</dd>'
								+'</dl>'
							+'</div>';
			}

			var html =  '<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Type</dt>'
										+'<dd>'+data.type+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Other type</dt>'
										+'<dd>'+data.type_other+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Patient</dt>'
										+'<dd>'+data.patient.name+'</dd>'
									+'</dl>'
								+'</div>'
								+optional
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Date</dt>'
										+'<dd>'+data.date_to+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Description</dt>'
										+'<dd>'+data.description+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>';
			if(data.transaction) {
				html += '<div class="row">'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Amount</dt>'
										+'<dd>$'+data.transaction.amount+'</dd>'
									+'</dl>'
								+'</div>'
								+'<div class="col-sm-6">'
									+'<dl>'
										+'<dt>Method</dt>'
										+'<dd>'+data.transaction.method+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
							+'<div class="row">'
								+'<div class="col-sm-12">'
									+'<dl>'
										+'<dt>Date of payment</dt>'
										+'<dd>'+data.transaction.created_at+'</dd>'
									+'</dl>'
								+'</div>'
							+'</div>'
			}

			$('#recordDetailModal').find('.modal-body').html(html).end().modal();
		} else {
			alert('Choose a record');
		}
	});

	// Confirm delete record
	$('#deleteRecordBtn').click(function(event) {
		event.preventDefault();
		var row = recordsTable.row('.selected');

		$.post('/records/'+row.data().id, {_method: 'delete', _token: $('input[name="_token"]').val()}, function(data, textStatus, xhr) {
			if(data.result == 'success') {
				row.remove().draw(false);
				$('#recordDeleteModal').modal('hide');
				alert(data.msg);
			} else {
				alert(data.msg);
			}
		});
	});

	// Adding or editing the record
	$('#newRecordBtn').click(function(event) {
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
			data: {
				description: $form.find('#description').val(),
				type: $form.find('#type option:selected').val(),
				type_other: ($form.find('#type option:selected').val() == 'other') ? $form.find('#type_other').val() : '',
				date_to: (php.role == 'doctor') ? $form.find('#hour').val() : $form.find('#date_to').val(),
				doctor_id: (php.role == 'doctor') ? php.doctor.id : $form.find('#doctor').val().split(" ")[0],
				patient_id: $form.find('#patient').val().split(" ")[0],
				_token: $form.find('input[name="_token"]').val()
			},
		}).done(function(data) {
			if(data.result == 'success') {
				// If it's an update
				if ($form.attr('method') == 'PUT') {
					// Updating the row
					recordsTable.row('.selected').data(data.row).draw();
				} else { // If it's a new reg
					// Adding the row
					recordsTable.rows.add(data.row).draw()
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