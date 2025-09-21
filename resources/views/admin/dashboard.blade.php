@extends('admin.layout')
@section('content')
<h1 class="h4 mb-4">Dashboard</h1>
<div class="row g-3">
  <div class="col-md-3"><div class="card p-3"><div>Total</div><strong class="fs-4">{{ $kpis['total'] }}</strong></div></div>
  <div class="col-md-3"><div class="card p-3"><div>Pendentes</div><strong class="fs-4">{{ $kpis['pendentes'] }}</strong></div></div>
  <div class="col-md-3"><div class="card p-3"><div>Em andamento</div><strong class="fs-4">{{ $kpis['em_andamento'] }}</strong></div></div>
  <div class="col-md-3"><div class="card p-3"><div>Concluídos</div><strong class="fs-4">{{ $kpis['concluidos'] }}</strong></div></div>
</div>
@endsection
