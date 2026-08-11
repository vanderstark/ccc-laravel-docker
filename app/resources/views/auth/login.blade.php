@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="row justify-content-center mt-5">
<div class="col-md-5">
<div class="card">
<div class="card-body p-4">
<h4 class="mb-3 text-center"><i class="bi bi-shield-lock me-2"></i>Masuk ke CCC</h4>
@if($errors->any())
<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ route('login') }}">
@csrf
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
</div>
<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>
<div class="form-check mb-3">
<input type="checkbox" name="remember" class="form-check-input" id="remember">
<label class="form-check-label" for="remember">Ingat saya</label>
</div>
<button class="btn btn-primary w-100">Masuk</button>
</form>
<p class="text-center mt-3 small text-muted">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
</div>
</div>
</div>
</div>
@endsection