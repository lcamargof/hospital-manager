@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
@stop
<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">		
		<div class="panel-heading"><i class="fa fa-user-md"></i> Doctors</div>
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
		<div class="panel-heading"><i class="fa fa-stethoscope"></i> Nurses</div>
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

@section('Constructor')
	@parent
	<script>
		php.doctors = {!! $doctors !!};
		php.nurses = {!! $nurses !!};
	</script>
@stop

@section('css')
	@parent
	<link media="all" type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap.css">
@stop

@section('javascript')
	@parent
	<script src="/js/staff.js"></script>
@stop