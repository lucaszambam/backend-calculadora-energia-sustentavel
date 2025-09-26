@extends('admin.layout')
@section('content')
<h1 class="h4 mb-3">
  Editar Parâmetros — {{ $param->cidade->nome }}/{{ $param->cidade->estado->sigla }}
</h1>

<div class="row g-4">
  <form method="POST" action="{{ route('admin.parametros.update',$param->id_parametro) }}">
    @csrf @method('PUT')

    <div class="row">
      {{-- Coluna Tarifas --}}
      <div class="col-md-6">
        <fieldset class="mb-3 border p-3 rounded h-100">
          <legend class="float-none w-auto px-2 h6">Tarifas e CO₂</legend>

          <div class="mb-2">
            <label class="form-label">Estado</label>
            <input class="form-control" value="{{ $param->cidade->estado->nome }}" disabled>
          </div>

          <div class="mb-2">
            <label class="form-label">Cidade</label>
            <input class="form-control" value="{{ $param->cidade->nome }}" disabled>
          </div>

          <div class="mb-2">
            <label class="form-label">Tarifa base (R$/kWh)</label>
            <input name="tarifa_base" class="form-control"
                   value="{{ old('tarifa_base',$param->tarifa_base) }}" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Taxa distribuição (fração)</label>
            <input name="taxa_distribuicao" class="form-control"
                   value="{{ old('taxa_distribuicao',$param->taxa_distribuicao) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">CO₂ por kWh (kg/kWh)</label>
            <input name="co2_por_kwh" class="form-control"
                   value="{{ old('co2_por_kwh',$param->co2_por_kwh) }}" required>
          </div>
        </fieldset>
      </div>

      {{-- Coluna Eficiências --}}
      <div class="col-md-6">
        <fieldset class="mb-3 border p-3 rounded h-100">
          <legend class="float-none w-auto px-2 h6">Eficiências</legend>

          @foreach($segmentos as $seg)
            <h6 class="mt-3">{{ $seg->nome }}</h6>
            <table class="table table-bordered align-middle text-center">
              <thead>
                <tr>
                  <th>Energia \ Instalação</th>
                  @foreach($instalacoes as $inst)
                    <th>{{ $inst->descricao }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($energias as $en)
                  <tr>
                    <td>{{ $en->descricao }}</td>
                    @foreach($instalacoes as $inst)
                      @php
                        $val = old(
                          "eficiencias.{$seg->id_segmento}.{$en->id_tipo_energia}.{$inst->id_tipo_instalacao}",
                          $ef[$seg->id_segmento][$en->id_tipo_energia][$inst->id_tipo_instalacao] ?? ''
                        );
                      @endphp
                      <td>
                        <input type="number" step="0.01" min="0" max="1"
                               name="eficiencias[{{ $seg->id_segmento }}][{{ $en->id_tipo_energia }}][{{ $inst->id_tipo_instalacao }}]"
                               value="{{ $val }}"
                               class="form-control text-center" required>
                      </td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endforeach
        </fieldset>
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-success">Salvar</button>
      <a href="{{ route('admin.parametros.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
