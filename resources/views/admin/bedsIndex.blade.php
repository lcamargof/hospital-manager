@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
@stop
<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">		
		<div class="panel-heading"><i class="fa fa-wheelchair"></i> Wards
			<div class="btnmenu pull-right">
				<div class="btn-group" >
					<button type="button" class="btn btn-default" id="wardNewBtn"><i class="fa fa-plus-circle"></i> New</button>
		    		<button type="button" class="btn btn-default" id="wardEditBtn"><i class="fa fa-edit"></i> Edit</button>
		    		<button type="button" class="btn btn-default" id="wardDeleteBtn"><i class="fa fa-trash"></i> Remove</button>
				</div>
			</div>
	   </div>
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
<div class="col-md-12 xsno">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-bed"></i> Beds
			<div class="btnmenu pull-right">
				<div class="btn-group" >
					<button type="button" class="btn btn-default" id="bedNewBtn"><i class="fa fa-plus-circle"></i> New</button>
		    		<button type="button" class="btn btn-default" id="bedEditBtn"><i class="fa fa-edit"></i> Edit</button>
		    		<button type="button" class="btn btn-default" id="bedDeleteBtn"><i class="fa fa-trash"></i> Remove</button>
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

<div class="modal fade" id="newItemModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-plus"></i> New</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-success alert-dismissible alert-hidden" id="newItemAlert" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					<div id="alertBody">
						<strong>Success!!!</strong> the item was created successfully.				
					</div>
					<ul></ul>
				</div>
				<form action="/beds" method="POST">
					{!! csrf_field() !!}
					<input type="hidden" name="type" id="type">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="identification">Identification</label>
								<input type="text" class="form-control" id="identification" name="identification" placeholder="Identification name">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group" id="bedField">
								<label for="ward_id">Ward</label>
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="0">None</option>
								</select>							
							</div>
						</div>
						<div class="col-md-6" id="wardField" style="display: none;">
							<div class="form-group">
								<label for="capacity">Capacity</label>
								<input type="number" class="form-control" name="capacity" id="capacity">						
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveItemBtn">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="itemDeleteModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete staff member</h4>
			</div>
			<div class="modal-body">
				<h4 class="text-center">Are you sure you want to delete the selected <span>type</span>?</h4>
				<input type="hidden" id="itemType">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="itemDeleteBtn">Confirm</button>
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
		php.beds = {!! $beds !!};
		php.wards = {!! $wards !!};
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