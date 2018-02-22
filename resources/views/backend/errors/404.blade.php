@extends('backend.layouts.guest')

@section('head')
	<style>
		html, body {
			height: 100%;
		}

		body {
			margin: 0;
			padding: 0;
			width: 100%;
			display: table;
			font-family: 'Lato';
		}

		.container {
			text-align: center;
			display: table-cell;
			vertical-align: middle;
		}

		.content {
			text-align: left;
			display: inline-block;
		}
        .headline {
            margin-top: -20px;
        }
	</style>
@endsection

@section('content')
	<div class="container">
	<div class="content">
	<div class="error-page">
		<h2 class="headline text-green">404</h2>
		<div class="error-content">
			<h3><i class="fa fa-warning text-green"></i> Oops! Page not found.</h3>
			<p>Sorry, the page you are looking for could not be found.
			Meanwhile, you may <a href="{{ backend_url('/dashboard') }}">return to dashboard</a>.</p>
		</div>
	</div>
	</div>
	</div>
@endsection
