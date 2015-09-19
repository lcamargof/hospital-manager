@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
@stop
<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-bed"></i> Beds
			<div class="btnmenu pull-right">
				<div class="btn-group" >
		    		<button type="button" class="btn btn-default" id="assignPatientBtn"><i class="fa fa-edit"></i> Assign patient</button>
		    		<button type="button" class="btn btn-default" id="releasePatient"><i class="fa fa-remove"></i> Release patient</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
               <table id="table_beds" class="table" width="100%">
                  <thead>
                     <tr>
                        <th>id</th>
                        <th>Identification</th>
                        <th>Ward</th>
                        <th>Patient</th>
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
<div class="col-md-12 xsno">
	<div class="panel panel-default">		
		<div class="panel-heading"><i class="fa fa-wheelchair"></i> Wards</div>
  		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
	               <table id="table_wards" class="table" width="100%">
	                  <thead>
	                     <tr>
	                        <th>id</th>
	                        <th>Identification</th>
	                        <th>Capacity</th>
	                        <th>Beds</th>
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

<div class="modal fade" id="assignPatientModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Assign patient to bed</h4>
			</div>
			<div class="modal-body">
				{!! csrf_field() !!}
            <table id="table_patients" class="table" width="100%">
               <thead>
                  <tr>
                     <th>id</th>
                     <th>Name</th>
                     <th>Id Number</th>
                  </tr>
               </thead>
					<tbody></tbody>
            </table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="assignPatientConfirm">Assign</button>
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
		php.beds = {!! $beds !!};
		php.wards = {!! $wards !!};
		php.patients = {!! $patients !!};
	</script>
@stop

@section('css')
	@parent
	<link media="all" type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap.css">
@stop

@section('javascript')
	@parent
	<script src="/js/functions.js"></script>
	<script src="/js/beds.js"></script>
@stop