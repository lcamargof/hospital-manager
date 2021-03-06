@extends('../layout')

@section('title', $location)

@section('css')
	@parent
	<link media="all" type="text/css" rel="stylesheet" href="/css/datatables.responsive.css">
@stop

@section('content')
	<div class="reset" id="menu-container">
		<ul class="menuadmin reset">
			<li {!! ($location == 'home') ? 'class="current"' : '' !!}>
				<a href="/home"><i class="fa fa-home fa-2x" id="home-icon"></i> Home</a>
			</li>
			<li {!! ($location == 'staff') ? 'class="current"' : '' !!}>
				<a href="/staff"><i class="fa fa-users"></i> Staff</a>
			</li>
			<li {!! ($location == 'beds') ? 'class="current"' : '' !!}>
				<a href="/beds"><i class="fa fa-bed"></i> Beds</a>
			</li>
			<li {!! ($location == 'users') ? 'class="current"' : '' !!}>
				<a href="/users"><i class="fa fa-key"></i> Users</a>
			</li>
		</ul>
	</div>
<div class="fluid-container">
	<div class="row">
		<div class="col-md-12 col-sm-4 top-bar">
			<div class="col-xs-4 col-sm-8 reset">
				<h2 class="reset hidden-xs">Welcome</h2>
				<button class="btn btn-default" id="show-menu"><i class="fa fa-bars"></i> Menu</button>
			</div>
			<div class="col-xs-8 col-sm-4 act-btn">
				@yield('actions')
			</div>
		</div>
	</div>
	<div class="row content">
		<div class="col-lg-12">
			{!! $content !!}
		</div>
	</div>
</div>
@stop

@section('javascript')
	@parent
	<script src="/js/jquery.dataTables.min.js"></script>
	<script src="/js/dataTables.bootstrap.js"></script>
@stop