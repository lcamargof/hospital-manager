@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
	<a class="btn btn-default btn-sm pull-right" href="#" id="recordNewBtn"><i class="fa fa-plus-circle fa-lg"></i> New record</a>
@stop

<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-book"></i> Records
			<div class="btnmenu pull-right">
				<div class="btn-group" >
					<button type="button" class="btn btn-default" id="recordDetailBtn"><i class="fa fa-plus"></i> Detail</button>
		    		<button type="button" class="btn btn-default" id="recordEditBtn"><i class="fa fa-edit"></i> Edit</button>
		    		<button type="button" class="btn btn-default" id="recordDeleteBtn"><i class="fa fa-trash"></i> Remove</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
	               <table id="table_records" class="table" width="100%">
	                  <thead>
	                     <tr>
	                        <th>id</th>
	                        <th>Type</th>
	                        <th>Description</th>
	                        <th>Patient</th>
	                        <th>Date</th>
	                        <th>Payment</th>
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

<div class="modal fade" id="recordDetailModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Record details</h4>
			</div>
			<div class="modal-body">
				
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="recordNewModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">New record</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-success alert-dismissible alert-hidden" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					<div id="alertBody"></div>
					<ul></ul>
				</div>
				<form action="/records">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="type">Type</label>
								<select name="type" id="type" class="form-control">
									<option value="consult">Consult</option>
									<option value="radiography">Radiography</option>
									<option value="operation">Operation</option>
									<option value="therapy">Therapy</option>
									<option value="other">other</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="type_other">Other type</label>
								<input type="text" class="form-control" name="type_other" id="type_other" placeholder="Other type" disabled>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="patient">Patient</label>
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Select a patient" id="patient" name="patient" readonly>
									<span class="input-group-btn">
							        <button class="btn btn-default" id="selectPatientBtn" type="button"><i class="fa fa-search"></i></button>
							      </span>	
								</div>
							</div>
						</div>
		            <div class="col-md-6">
		            	<div class="form-group">
		            		<label for="date">Date</label>
								<div class="input-group">
								   <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" id="date" name="date" class="form-control" placeholder="YYYY-MM-DD" readonly>
								</div>
		            	</div>
		            </div>
		            <div class="col-md-6">
		            	<div class="form-group">
		            		<label for="hour">Hour</label>
		            		<select name="hour" id="hour" class="form-control">
		            			{{-- DYNAMIC SELECT --}}
		            		</select>
		            	</div>
		            </div>  
						<div class="col-md-12">
							<div class="form-group">
								<label for="description">Description</label>
								<input type="text" class="form-control" placeholder="Type a description" id="description" name="description">
							</div>
						</div>
					</div>
				</form>			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="newRecordBtn">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="recordDeleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete a record</h4>
			</div>
			<div class="modal-body">
				<h4 class="text-center">Are you sure you want to delete the selected record?</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="deleteRecordBtn">Confirm</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="selectPatientModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Patient selection</h4>
			</div>
			<div class="modal-body">
            <table id="table_patients" class="table" width="100%">
               <thead>
                  <tr>
                     <th>id</th>
                     <th>Name</th>
                     <th>Id number</th>
                  </tr>
               </thead>
					<tbody></tbody>
            </table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="selectedPatientBtn">Save changes</button>
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
		php.records = {!! $records !!};
		php.patients = {!! $patients !!};
		php.doctor = {!! $doctor !!};
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
	<script src="/js/records.js"></script>
@stop