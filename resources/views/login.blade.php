@extends('layout')

@section('title', 'Login')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-push-4 col-xs-12" id="logSpace">
				<div id="logForm">
					<form action="/login" method="POST">
						{!! csrf_field() !!}
						<fieldset>
							<legend>Sign in</legend>
							<p class="text-danger" {!! (!session('msg')) ? 'style="display: none;"' : '' !!}>
								<i class="fa fa-exclamation-circle"></i> {{ session('msg') }}
							</p>
							<div class="form-group">
								<label for="user">User</label>
								<div class="input-group">
	  								<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input type="text" class="form-control" placeholder="User" name="user">
								</div>
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<div class="input-group">
	  								<span class="input-group-addon"><i class="fa fa-key"></i></span>
									<input type="password" placeholder="password" name="password" class="form-control">
								</div>
							</div>
							<input type="checkbox" name="recordar" value="true" id="recordar" checked>
							<label for="recordar">Remember me</label>
							<button class="btn btn-success btn-block">Login</button>
						</fieldset>					
					</form>
				</div>
			</div>			
		</div>
	</div>
@stop