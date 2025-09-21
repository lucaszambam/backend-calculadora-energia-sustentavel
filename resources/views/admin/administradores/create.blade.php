@extends('admin.layout')

@section('content')
<h1 class="h4 mb-3">Novo Administrador</h1>

<div class="card p-3">
  <form method="POST" action="{{ route('admin.administradores.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Nome</label>
      <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
      @error('nome') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">E-mail</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
      @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Senha</label>
      <input type="password" name="password" class="form-control" required>
      @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Confirme a Senha</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button class="btn btn-success">Salvar</button>
    <a href="{{ route('admin.administradores.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
