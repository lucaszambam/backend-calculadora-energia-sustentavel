@extends('admin.layout')
@section('content')
<h1 class="h4 mb-3">Detalhes da Simulação #{{ $simulacao->id_simulacao }}</h1>

<div class="card p-3 mb-3">
  <h2 class="h6">Dados do Contato</h2>
  <p><strong>Nome:</strong> {{ $simulacao->nome_contato }}</p>
  <p><strong>Email:</strong> {{ $simulacao->email_contato ?? '-' }}</p>
  <p><strong>Telefone:</strong> {{ $simulacao->telefone_contato ?? '-' }}</p>
</div>

<div class="card p-3 mb-3">
  <h2 class="h6">Localidade</h2>
  <p><strong>Estado:</strong> {{ $simulacao->cidade->estado->nome ?? '' }} ({{ $simulacao->cidade->estado->sigla ?? '' }})</p>
  <p><strong>Cidade:</strong> {{ $simulacao->cidade->nome ?? '' }}</p>
</div>

<div class="card p-3 mb-3">
  <h2 class="h6">Parâmetros</h2>
  <p><strong>Segmento:</strong> {{ $simulacao->segmento->nome ?? '' }}</p>
  <p><strong>Tipo de Energia:</strong> {{ $simulacao->tipoEnergia->descricao ?? '' }}</p>
  <p><strong>Tipo de Instalação:</strong> {{ $simulacao->tipoInstalacao->descricao ?? '' }}</p>
</div>

<div class="card p-3 mb-3">
  <h2 class="h6">Resultados da Simulação</h2>
  <p><strong>Valor médio da conta:</strong> R$ {{ number_format($simulacao->valor_conta_medio,2,',','.') }}</p>
  <p><strong>Consumo estimado:</strong> {{ number_format($simulacao->consumo_kwh_estimado,2,',','.') }} kWh</p>
  <p><strong>Economia estimada:</strong> R$ {{ number_format($simulacao->economia_reais,2,',','.') }} ({{ number_format($simulacao->economia_percentual,1,',','.') }}%)</p>
  <p><strong>CO₂ evitado:</strong> {{ number_format($simulacao->co2_evitado,2,',','.') }} kg</p>
</div>

<div class="card p-3 mb-3">
  <h2 class="h6">Status</h2>
  <p><strong>Status do contato:</strong> {{ $simulacao->status_contato }}</p>
  <p><strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($simulacao->data_hora)->format('d/m/Y H:i') }}</p>
</div>

<a href="{{ route('admin.simulacoes.index') }}" class="btn btn-secondary">Voltar</a>
@endsection