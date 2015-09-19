@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
@stop

<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-medkit"></i> Patients
			<div class="btnmenu pull-right">
				<div class="btn-group" >
					<button type="button" class="btn btn-default" id="patientHistoryBtn"><i class="fa fa-book"></i> Patient History</button>
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
	                        <th>Birth date</th>
	                        <th>Gender</th>
									<th>Blood Type</th>
	                        <th>Allergies</th>
	                        <th>Address</th>
	                        <th>Observations</th>
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

<div class="modal fade" id="patientHistoryModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Patient history</h4>
			</div>
			<div class="modal-body">
            <table id="table_records" class="table" width="100%">
               <thead>
                  <tr>
                     <th>id</th>
                     <th>Type</th>
                     <th>Description</th>
                     <th>Date</th>
                     <th>Results</th>
                  </tr>
               </thead>
					<tbody></tbody>
            </table>								
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
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
	<script src="/js/docpatients.js"></script>
@stop