@section('actions')
	<a class="btn btn-default btn-sm pull-right" href="{{ URL::previous() }}"><i class="fa fa-reply fa-lg"></i> Return</a>
@stop

<div class="col-md-12 xsno panel-top">
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-user-group"></i> Users
			<div class="btnmenu pull-right">
				<div class="btn-group" >
		    		<button type="button" class="btn btn-default" id="userEditBtn"><i class="fa fa-edit"></i> Edit password</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
	               <table id="table_users" class="table" width="100%">
	                  <thead>
	                     <tr>
	                        <th>id</th>
	                        <th>User</th>
	                        <th>Role</th>
	                        <th>Doctor</th>
	                        <th>Last login</th>
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

<div class="modal fade" id="changePasswordModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Change password</h4>
			</div>
			<div class="modal-body">
				<form action="/users" method="PUT">
					{!! csrf_field() !!}
					<div class="form-group">
						<label for="password">New password</label>
						<input type="text" name="password" class="form-control" id="password" placeholder="Type the new password">
					</div>					
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveNewPassword">Save changes</button>
			</div>
		</div>
	</div>
</div>

@section('Constructor')
	@parent
	<script>
		php.users = {!! $users !!};
	</script>
@stop

@section('css')
	@parent
	<link media="all" type="text/css" rel="stylesheet" href="/css/dataTables.bootstrap.css">
@stop

@section('javascript')
	@parent
	<script src="/js/users.js"></script>
@stop