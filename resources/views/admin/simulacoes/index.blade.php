@extends('admin.layout')
@section('content')
<h1 class="h4 mb-3">Simulações</h1>

<form class="row g-2 mb-3" method="GET">
  <div class="col-md-2">
    <label class="form-label">Data inicial</label>
    <input type="date" name="data_ini" value="{{ request('data_ini') }}" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Data final</label>
    <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="form-control">
  </div>
  <div class="col-md-1">
    <label class="form-label">UF</label>
    <input type="text" name="uf" value="{{ request('uf') }}" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Cidade</label>
    <input type="text" name="cidade" value="{{ request('cidade') }}" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Segmento</label>
    <input type="text" name="segmento" value="{{ request('segmento') }}" class="form-control" placeholder="Residencial...">
  </div>
  <div class="col-md-2">
    <label class="form-label">Tipo Energia</label>
    <input type="text" name="tipo_energia" value="{{ request('tipo_energia') }}" class="form-control" placeholder="Solar/Eólica">
  </div>
  <div class="col-md-1">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="">—</option>
      @foreach(['pendente','em andamento','concluído'] as $st)
        <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-12 text-end">
    <button class="btn btn-primary">Filtrar</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-sm align-middle">
  <thead><tr>
    <th>#</th><th>Data/Hora</th><th>Nome</th><th>Contato</th><th>Local</th>
    <th>Segmento</th><th>Energia</th><th>R$ Médio</th><th>Economia R$</th><th>Economia %</th><th>Status</th><th>Ações</th>
  </tr></thead>
  <tbody>
  @foreach($simulacoes as $s)
    <tr>
      <td>{{ $s->id_simulacao }}</td>
      <td>{{ \Carbon\Carbon::parse($s->data_hora)->format('d/m/Y H:i') }}</td>
      <td>{{ $s->nome_contato }}</td>
      <td>
        @if($s->email_contato) <div>{{ $s->email_contato }}</div> @endif
        @if($s->telefone_contato) <div>{{ $s->telefone_contato }}</div> @endif
      </td>
      <td>{{ $s->cidade->nome ?? '' }}/{{ $s->cidade->estado->sigla ?? '' }}</td>
      <td>{{ $s->segmento->nome ?? '' }}</td>
      <td>{{ $s->tipoEnergia->descricao ?? '' }}</td>
      <td>R$ {{ number_format($s->valor_conta_medio,2,',','.') }}</td>
      <td>R$ {{ number_format($s->economia_reais,2,',','.') }}</td>
      <td>{{ number_format($s->economia_percentual,1,',','.') }}%</td>
      <td><span class="badge text-bg-secondary">{{ $s->status_contato }}</span></td>
      <td>
        <form method="POST" action="{{ route('admin.simulacoes.updateStatus',$s->id_simulacao) }}" class="d-flex gap-1">
          @csrf @method('PATCH')
          <select name="status" class="form-select form-select-sm">
            @foreach(['pendente','em andamento','concluído'] as $st)
              <option value="{{ $st }}" @selected($s->status_contato===$st)>{{ $st }}</option>
            @endforeach
          </select>
          <button class="btn btn-sm btn-outline-primary">Atualizar</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>

{{ $simulacoes->links() }}
@endsection
