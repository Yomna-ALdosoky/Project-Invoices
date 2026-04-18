@extends('layouts.master2')

@section('title')
تسجيل الدخول
@stop

@section('css')
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}"
	rel="stylesheet">

<style>
	body {
		background: #f5f7fb;
	}

	.login-wrapper {
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		overflow: hidden;
	}

	/* Background Shapes */
	.bg-shape {
		position: absolute;
		border-radius: 50%;
		filter: blur(80px);
		opacity: 0.3;
	}

	.shape1 {
		width: 300px;
		height: 300px;
		background: #007bff;
		top: -50px;
		left: -50px;
	}

	.shape2 {
		width: 250px;
		height: 250px;
		background: #28a745;
		bottom: -50px;
		right: -50px;
	}

	.login-card {
		width: 100%;
		max-width: 420px;
		background: #fff;
		border-radius: 16px;
		padding: 30px;
		box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
		z-index: 2;
	}

	.invoice-title {
		font-weight: bold;
		color: #2c3e50;
	}

	.invoice-subtitle {
		color: #888;
		font-size: 14px;
	}

	.btn-main-primary {
		border-radius: 10px;
		font-weight: bold;
		padding: 10px;
	}
</style>
@endsection

@section('content')
<div class="login-wrapper">

	<!-- Background Shapes -->
	<div class="bg-shape shape1"></div>
	<div class="bg-shape shape2"></div>

	<!-- Login Card -->
	<div class="login-card">

		<!-- Logo + Title -->
		<div class="text-center mb-4">
			<a href="{{ url('/' . $page='Home') }}">
				<img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="ht-40 mb-2">
			</a>

			<h3 class="invoice-title">نظام الفواتير</h3>
			<p class="invoice-subtitle">إدارة فواتيرك بسهولة واحترافية</p>
		</div>

		<h5 class="text-center mb-4">تسجيل الدخول</h5>

		<form method="POST" action="{{ route('login') }}">
			@csrf

			<!-- Email -->
			<div class="form-group mb-3">
				<label>البريد الالكتروني</label>
				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
					value="{{ old('email') }}" required autocomplete="email" autofocus>

				@error('email')
				<span class="invalid-feedback">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>

			<!-- Password -->
			<div class="form-group mb-3">
				<label>كلمة المرور</label>

				<input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
					name="password" required autocomplete="current-password">

				@error('password')
				<span class="invalid-feedback">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>

			<!-- Remember -->
			<div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember')
					? 'checked' : '' }}>

				<label class="form-check-label" for="remember">
					{{ __('تذكرني') }}
				</label>
			</div>

			<!-- Button -->
			<button type="submit" class="btn btn-main-primary btn-block">
				{{ __('تسجيل الدخول') }}
			</button>

		</form>

	</div>

</div>
@endsection

@section('js')
@endsection