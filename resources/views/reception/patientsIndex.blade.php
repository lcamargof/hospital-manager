@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
	<a class="btn btn-default btn-sm pull-right" href="#" id="patientNewBtn"><i class="fa fa-plus-circle fa-lg"></i> New patient</a>
@stop

<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-medkit"></i> Patients
			<div class="btnmenu pull-right">
				<div class="btn-group" >
					<button type="button" class="btn btn-default" id="patientDetailBtn"><i class="fa fa-plus"></i> Detail</button>
		    		<button type="button" class="btn btn-default" id="patientEditBtn"><i class="fa fa-edit"></i> Edit</button>
		    		<button type="button" class="btn btn-default" id="patientDeleteBtn"><i class="fa fa-trash"></i> Remove</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
	               <table id="table_patients" class="table" width="100%">
	                  <thead>
	                     <tr>
	                        <th>id</th>
	                        <th>Name</th>
	                        <th>Id number</th>
	                        <th>Phone</th>
	                        <th>Ward</th>
	                        <th>Bed</th>
	                     </tr>
	                  </thead>
							<tbody></tbody>
	               </table>
	               <p class="text-center" id="table_loading"><i class="fa fa-spinner fa-spin fa-4x"></i></p>
	           </div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="patientDetailModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Patient details</h4>
			</div>
			<div class="modal-body">
				
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="patientNewModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">New patient</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-success alert-dismissible alert-hidden" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					<div id="alertBody"></div>
					<ul></ul>
				</div>
				<form action="/patients">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" name="name" id="name" placeholder="Type the patient name" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="id_number">Id number</label>
								<input type="text" name="id_number" id="id_number" placeholder="Type the patient id number" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="sex">Gender</label>
								<select name="gender" id="gender" class="form-control">
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="birth_date">Birth date</label>
								<input type="text" name="birth_date" id="birth_date" placeholder="1993-04-03" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="blood_type">Blood type</label>
								<select name="blood_type" id="blood_type" class="form-control">
									<option value="O-">O-</option>
									<option value="O+">O+</option>
									<option value="A-">A-</option>
									<option value="A+">A+</option>
									<option value="B+">B+</option>
									<option value="AB-">AB-</option>
									<option value="AB+">AB+</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="text" name="phone" id="phone" placeholder="Type the patient phone" class="form-control">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="address">Address</label>
								<textarea name="address" id="address" class="form-control" rows="3"></textarea>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="allergies">Allergies</label>
								<textarea name="allergies" id="allergies" class="form-control" rows="3"></textarea>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="observations">Observations</label>
								<textarea name="observations" id="observations" class="form-control" rows="3"></textarea>	
							</div>
						</div>
					</div>
				</form>			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="newPatientBtn">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="patientDeleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete patient</h4>
			</div>
			<div class="modal-body">
				<h4 class="text-center">Are you sure you want to delete the selected patient?</h4>
				<input type="hidden" id="itemType">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="deletePatientBtn">Confirm</button>
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
		php.patients = {!! $patients !!};
		php.beds = {!! $beds !!};
	</script>
@stop

@section('css')
	@parent
	<link media="all" type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap.css">
	<link media="all" type="text/css" rel="stylesheet" href="/css/pikaday.css">
@stop

@section('javascript')
	@parent
	<script src="/js/moment.min.js"></script>
	<script src="/js/pikaday.js"></script>
	<script src="/js/pikaday.jquery.js"></script>
	<script src="/js/functions.js"></script>
	<script src="/js/patients.js"></script>
@stop