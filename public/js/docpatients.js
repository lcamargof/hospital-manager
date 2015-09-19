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
				data: 'birth_date'
			}, {
				data: 'gender'
			}, {
				data: 'blood_type'
			}, {
				data: 'allergies'
			}, {
				data: 'address'
			}, {
				data: 'observations'
			}
		],
		"createdRow": function(row, data, index) {
			$(row).addClass('selectable');
		}
	});

	recordsTable = null;

	$('#patientHistoryBtn').click(function(event) {
		var data = patientsTable.row('.selected').data();
		if(data) {
			// Records datatable
			if(recordsTable) {
				recordsTable.destroy();
			}

			recordsTable = $('#table_records').DataTable({
				data: data.records,
				deferRender: true,
				columns: [
					{
						data: 'id'
					}, {
						data: null,
						render: function(d) {
							if(d.type == 'other') 
								return d.type_other;
							else 
								return d.type;
						}
					}, {
						data: 'description'
					}, {
						data: 'date_to'
					}, {
						data: 'results'
					}
				]
			});

			$('#patientHistoryModal').modal();
		} else {
			alert('Choose a patient.');
		}
	});
});