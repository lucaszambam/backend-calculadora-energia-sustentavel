@extends('admin.layout')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-4">
    <h1 class="h4 mb-3">Login Administrativo</h1>
    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
        @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <button class="btn btn-primary w-100">Entrar</button>
    </form>
  </div>
</div>
@endsection
