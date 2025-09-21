@extends('admin.layout')
@section('content')
<h1 class="h4 mb-3">Parâmetros</h1>

<form class="row g-2 mb-3" method="GET">
  <div class="col-md-1"><label class="form-label">UF</label>
    <input name="uf" value="{{ request('uf') }}" class="form-control">
  </div>
  <div class="col-md-3"><label class="form-label">Cidade</label>
    <input name="cidade" value="{{ request('cidade') }}" class="form-control">
  </div>
  <div class="col-md-2 align-self-end">
    <button class="btn btn-primary">Filtrar</button>
  </div>
</form>

<a href="{{ route('admin.parametros.create') }}" class="btn btn-success mb-3">+ Inserir Parâmetro</a>

<div class="table-responsive">
<table class="table table-sm">
  <thead><tr>
    <th>#</th><th>Cidade/UF</th><th>Tarifa Base</th><th>Taxa Dist.</th><th>CO₂/kWh</th><th></th>
  </tr></thead>
  <tbody>
  @foreach($params as $p)
    <tr>
      <td>{{ $p->id_parametro }}</td>
      <td>{{ $p->cidade->nome }} / {{ $p->cidade->estado->sigla }}</td>
      <td>{{ number_format($p->tarifa_base,4,',','.') }}</td>
      <td>{{ number_format($p->taxa_distribuicao,4,',','.') }}</td>
      <td>{{ number_format($p->co2_por_kwh,4,',','.') }}</td>
      <td>
          <a href="{{ route('admin.parametros.edit', $p->id_parametro) }}" class="btn btn-sm btn-primary">Editar</a>
          <form action="{{ route('admin.parametros.destroy', $p->id_parametro) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este parâmetro?')">Excluir</button>
          </form>
        </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>

{{ $params->links() }}
@endsection
