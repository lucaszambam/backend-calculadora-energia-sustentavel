@extends('admin.layout')
@section('content')
<h1 class="h4 mb-3">
  Editar Parâmetros — {{ $param->cidade->nome }}/{{ $param->cidade->estado->sigla }}
</h1>

<div class="row g-4">
  <div class="col-md-5">
    <div class="card p-3">
      <h2 class="h6">Tarifas e CO₂</h2>
      <form method="POST" action="{{ route('admin.parametros.update',$param->id_parametro) }}">
        @csrf @method('PUT')
        <div class="mb-2">
          <label class="form-label">Tarifa base (R$/kWh)</label>
          <input name="tarifa_base" class="form-control" value="{{ old('tarifa_base', $param->tarifa_base) }}" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Taxa distribuição (fração)</label>
          <input name="taxa_distribuicao" class="form-control" value="{{ old('taxa_distribuicao', $param->taxa_distribuicao) }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">CO₂ por kWh (kg/kWh)</label>
          <input name="co2_por_kwh" class="form-control" value="{{ old('co2_por_kwh', $param->co2_por_kwh) }}" required>
        </div>
        <button class="btn btn-primary">Salvar parâmetros</button>
      </form>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card p-3">
      <h2 class="h6">Eficiência por Segmento</h2>
      <form method="POST" action="{{ route('admin.parametros.updateEficiencias',$param->id_parametro) }}">
        @csrf @method('PUT')
        <div class="row g-2">
          @foreach($segmentos as $seg)
            <div class="col-md-6">
              <label class="form-label">{{ $seg->nome }} (0 a 1)</label>
              <input name="eficiencias[{{ $loop->index }}][valor]" class="form-control"
                     value="{{ old('eficiencias.'.$loop->index.'.valor', $ef[$seg->id_segmento] ?? '') }}" required>
              <input type="hidden" name="eficiencias[{{ $loop->index }}][id_segmento]" value="{{ $seg->id_segmento }}">
            </div>
          @endforeach
        </div>
        <div class="mt-3">
          <button class="btn btn-primary">Salvar eficiências</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
