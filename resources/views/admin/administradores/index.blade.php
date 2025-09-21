@extends('admin.layout')

@section('content')
<h1 class="h4 mb-3">Administradores</h1>

<a href="{{ route('admin.administradores.create') }}" class="btn btn-success mb-3">+ Novo Administrador</a>

@if($admins->count())
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($admins as $admin)
        <tr>
          <td>{{ $admin->id_admin }}</td>
          <td>{{ $admin->nome }}</td>
          <td>{{ $admin->email }}</td>
          <td>
            @if(auth('web')->id() != $admin->id_admin)
                <form method="POST" action="{{ route('admin.administradores.destroy',$admin->id_admin) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir administrador?')">
                        Excluir
                    </button>
                </form>
            @else
                <span class="text-muted">—</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $admins->links() }}
@else
  <div class="alert alert-info">Nenhum administrador cadastrado.</div>
@endif
@endsection
