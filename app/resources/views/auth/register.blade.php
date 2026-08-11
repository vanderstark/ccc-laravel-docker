@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="row justify-content-center mt-5">
<div class="col-md-5">
<div class="card">
<div class="card-body p-4">
<h4 class="mb-3 text-center"><i class="bi bi-person-plus me-2"></i>Daftar Akun CCC</h4>
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ route('register') }}">
@csrf
<div class="mb-3">
<label class="form-label">Nama Lengkap</label>
<input name="name" class="form-control" value="{{ old('name') }}" required autofocus>
</div>
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
</div>
<div class="mb-3">
<label class="form-label">Peran</label>
<select name="role_id" class="form-select">
<option value="">-- Pilih Peran --</option>
@foreach($roles as $r)
<option value="{{ $r->id }}">{{ $r->nama }}</option>
@endforeach
</select>
</div>
<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Konfirmasi Password</label>
<input type="password" name="password_confirmation" class="form-control" required>
</div>
<button class="btn btn-primary w-100">Daftar</button>
</form>
<p class="text-center mt-3 small text-muted">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</div>
</div>
</div>
</div>
@endsection