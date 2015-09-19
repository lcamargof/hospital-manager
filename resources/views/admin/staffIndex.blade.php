@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
	<a class="btn btn-default btn-sm pull-right" href="#" id="newStaffBtn"><i class="fa fa-plus-circle fa-lg"></i> New member</a>
@stop
<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">		
		<div class="panel-heading"><i class="fa fa-user-md"></i> Doctors
			<div class="btnmenu pull-right">
				<div class="btn-group" >
		    		<button type="button" class="btn btn-default" id="doctorEditBtn"><i class="fa fa-edit"></i> Edit</button>
		    		<button type="button" class="btn btn-default" id="doctorDeleteBtn"><i class="fa fa-trash"></i> Remove</button>
				</div>
			</div>
	   </div>
  		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
	               <table id="table_doctors" class="table" width="100%">
	                  <thead>
	                     <tr>
	                        <th>id</th>
	                        <th>Name</th>
	                        <th>Id number</th>
	                        <th>specialization</th>
	                        <th>birth date</th>
	                        <th>hours</th>
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
<div class="col-md-12 xsno">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-stethoscope"></i> Nurses
			<div class="btnmenu pull-right">
				<div class="btn-group" >
		    		<button type="button" class="btn btn-default" id="nurseEditBtn"><i class="fa fa-edit"></i> Edit</button>
		    		<button type="button" class="btn btn-default" id="nurseDeleteBtn"><i class="fa fa-trash"></i> Remove</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
	               <table id="table_nurses" class="table" width="100%">
	                  <thead>
	                     <tr>
	                        <th>id</th>
	                        <th>Name</th>
	                        <th>Id number</th>
	                        <th>Birth date</th>
	                        <th>shift</th>
	                        <th>beds</th>
	                     </tr>
	                  </thead>
							<tbody></tbody>
	               </table>
	               <p class="text-center" id="table_loading2"><i class="fa fa-spinner fa-spin fa-4x"></i></p>
	           </div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newStaffModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-user-plus fa-lg"></i> <span id="staffAction">New</span> staff member</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-success alert-dismissible alert-hidden" id="newStaffAlert" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					<div id="alertBody">
						<strong>Success!!!</strong>.						
					</div>
					<ul></ul>
				</div>
				<form action="/staff" method="POST">
					{!! csrf_field() !!}
					<div class="btn-group btn-group-justified " data-toggle="buttons">
						<label class="btn btn-primary active" id="staffTypeDoctor">
							<input type="radio" name="type" checked value="doctor" autocomplete="off"><i class="fa fa-user-md fa-lg"></i>  Doctor
						</label>
						<label class="btn btn-warning" id="staffTypeNurse"> 
							<input type="radio" name="type" value="nurse"  autocomplete="off"><i class="fa fa-stethoscope fa-lg"></i> Nurse
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="Staff name">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="id_number">ID number</label>
								<input type="text" class="form-control" name="id_number" id="id_number" placeholder="ID Number">								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="birth_date">Birth date</label>
								<input type="text" class="form-control" id="birth_date" name="birth_date" placeholder="1990-05-05">								
							</div>
						</div>
						{{-- SPECIFIC DOCTOR DATA --}}
						<div id="newDoctorDiv">
							<div class="col-md-6">
								<div class="form-group">
									<label for="specialization">Specialization</label>
									<input type="text" id="specialization" name="specialization" class="form-control" placeholder="Doctor specialization">		
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="in">Time in</label>
									<select name="time_in" id="time_in" class="form-control">
										<option value="01:00:00">1 A:M</option>
										<option value="02:00:00">2 A:M</option>
										<option value="03:00:00">3 A:M</option>
										<option value="04:00:00">4 A:M</option>
										<option value="05:00:00">5 A:M</option>
										<option value="06:00:00">6 A:M</option>
										<option value="07:00:00">7 A:M</option>
										<option value="08:00:00" selected>8 A:M</option>
										<option value="09:00:00">9 A:M</option>
										<option value="10:00:00">10 A:M</option>
										<option value="11:00:00">11 A:M</option>
										<option value="12:00:00">12 P:M</option>
										<option value="13:00:00">1 P:M</option>
										<option value="14:00:00">2 P:M</option>
										<option value="15:00:00">3 P:M</option>
										<option value="16:00:00">4 P:M</option>
										<option value="17:00:00">5 P:M</option>
										<option value="18:00:00">6 P:M</option>
										<option value="19:00:00">7 P:M</option>
										<option value="20:00:00">8 P:M</option>
										<option value="21:00:00">9 P:M</option>
										<option value="22:00:00">10 P:M</option>
										<option value="23:00:00">11 P:M</option>
										<option value="00:00:00">12 A:M</option>	

									</select>							
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="time_out">Time out</label>
									<select name="time_out" id="time_out" class="form-control">
										<option value="01:00:00">1 A:M</option>
										<option value="02:00:00">2 A:M</option>
										<option value="03:00:00">3 A:M</option>
										<option value="04:00:00">4 A:M</option>
										<option value="05:00:00">5 A:M</option>
										<option value="06:00:00">6 A:M</option>
										<option value="07:00:00">7 A:M</option>
										<option value="08:00:00">8 A:M</option>
										<option value="09:00:00">9 A:M</option>
										<option value="10:00:00">10 A:M</option>
										<option value="11:00:00">11 A:M</option>
										<option value="12:00:00">12 P:M</option>
										<option value="13:00:00">1 P:M</option>
										<option value="14:00:00">2 P:M</option>
										<option value="15:00:00">3 P:M</option>
										<option value="16:00:00">4 P:M</option>
										<option value="17:00:00">5 P:M</option>
										<option value="18:00:00" selected>6 P:M</option>
										<option value="19:00:00">7 P:M</option>
										<option value="20:00:00">8 P:M</option>
										<option value="21:00:00">9 P:M</option>
										<option value="22:00:00">10 P:M</option>
										<option value="23:00:00">11 P:M</option>
										<option value="00:00:00">12 A:M</option>										
									</select>	
								</div>
							</div>
						</div>
						{{-- SPECIFIC NURSE DATA --}}
						<div id="newNurseDiv" style="display: none;">
							<div class="col-md-6">
								<div class="form-group">
									<label for="shift">Shift</label>
									<select name="shift" disabled id="shift" class="form-control">
										<option value="diurn">Diurn</option>
										<option value="nocturne">Nocturne</option>
									</select>									
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="beds">Avaible Beds</label>
									<div class="input-group">
										<select name="beds" id="beds" disabled class="form-control">
											@foreach($beds as $bed)
												<option value="{{$bed->id}}">{{$bed->identification}}</option>
											@endforeach
										</select>		
										<span class="input-group-btn">	
											<button class="btn btn-default" id="addBed" type="button"><i class="fa fa-plus"></i></button>
										</span>									
									</div>
								</div>					
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="shift">Selected beds</label>
									<div class="input-group">
										<select name="selectedBeds" disabled id="selectedBeds" class="form-control">
											<!-- SELECTED BEDS -->
										</select>		
										<span class="input-group-btn">	
											<button class="btn btn-default" id="minusBed" type="button"><i class="fa fa-minus"></i></button>
											<button class="btn btn-default" id="deleteBed" type="button"><i class="fa fa-trash"></i></button>
										</span>			
									</div>										
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveStaffMember">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="staffDeleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete staff member</h4>
			</div>
			<div class="modal-body">
				<h4 class="text-center">Are you sure you want to delete the selected staff member?</h4>
				<input type="hidden" id="dStaffType">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="staffDeleteBtn">Confirm</button>
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
		window.php = window.php || {};
		php.doctors = {!! $doctors !!};
		php.nurses = {!! $nurses !!};
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
	<script src="/js/staff.js"></script>
@stop