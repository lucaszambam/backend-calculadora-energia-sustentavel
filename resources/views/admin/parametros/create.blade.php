@extends('admin.layout')

@section('content')
<h1 class="h4 mb-3">Novo Parâmetro</h1>

<div class="row g-4">
  <form method="POST" action="{{ route('admin.parametros.store') }}">
    @csrf

    <div class="row">
      {{-- Coluna Tarifas --}}
      <div class="col-md-6">
        <fieldset class="mb-3 border p-3 rounded h-100">
          <legend class="float-none w-auto px-2 h6">Tarifas e CO₂</legend>

          <div class="mb-2">
            <label class="form-label">Estado</label>
            <select id="estado" name="id_estado" class="form-control" required>
              <option value="">Selecione o estado</option>
              @foreach($estados as $estado)
                <option value="{{ $estado->id_estado }}">{{ $estado->nome }} ({{ $estado->sigla }})</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Cidade</label>
            <select id="cidade" name="id_cidade" class="form-control" required disabled>
              <option value="">Selecione a cidade</option>
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Tarifa base (R$/kWh)</label>
            <input name="tarifa_base" class="form-control" value="{{ old('tarifa_base') }}" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Taxa distribuição (fração)</label>
            <input name="taxa_distribuicao" class="form-control" value="{{ old('taxa_distribuicao') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">CO₂ por kWh (kg/kWh)</label>
            <input name="co2_por_kwh" class="form-control" value="{{ old('co2_por_kwh') }}" required>
          </div>
        </fieldset>
      </div>

      {{-- Coluna Eficiências --}}
      <div class="col-md-6">
        <fieldset class="mb-3 border p-3 rounded h-100">
          <legend class="float-none w-auto px-2 h6">Eficiência por Segmento</legend>
          <div class="row g-2">
            @foreach($segmentos as $seg)
              <div class="col-md-12">
                <label class="form-label">{{ $seg->nome }} (0 a 1)</label>
                <input name="eficiencias[{{ $loop->index }}][valor]" class="form-control"
                       value="{{ old('eficiencias.'.$loop->index.'.valor') }}" required>
                <input type="hidden" name="eficiencias[{{ $loop->index }}][id_segmento]" value="{{ $seg->id_segmento }}">
              </div>
            @endforeach
          </div>
        </fieldset>
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-success">Salvar</button>
      <a href="{{ route('admin.parametros.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

{{-- Script para carregar cidades dinamicamente --}}
@push('head')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const estadoSelect = document.getElementById('estado');
    const cidadeSelect = document.getElementById('cidade');

    estadoSelect.addEventListener('change', function() {
        const estadoId = this.value;
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.disabled = true;

        if (estadoId) {
            fetch(`/api/cidades?estado_id=${estadoId}`)
              .then(res => res.json())
              .then(data => {
                  cidadeSelect.innerHTML = '<option value="">Selecione a cidade</option>';
                  data.forEach(c => {
                      cidadeSelect.innerHTML += `<option value="${c.id_cidade}">${c.nome}</option>`;
                  });
                  cidadeSelect.disabled = false;
              })
              .catch(() => {
                  cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
              });
        } else {
            cidadeSelect.innerHTML = '<option value="">Selecione a cidade</option>';
        }
    });
});
</script>
@endpush

@endsection
