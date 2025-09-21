<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin • Calculadora Energia Sustentável</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-3 border-bottom">
  <div class="container">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin • CES</a>

    @auth('web')
    <ul class="navbar-nav ms-3">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.simulacoes.*') ? 'active fw-bold' : '' }}" 
        href="{{ route('admin.simulacoes.index') }}">
        Simulações
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.parametros.*') ? 'active fw-bold' : '' }}" 
        href="{{ route('admin.parametros.index') }}">
        Parâmetros
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.administradores.*') ? 'active fw-bold' : '' }}" 
        href="{{ route('admin.administradores.index') }}">
        Administradores
        </a>
    </li>
    </ul>

    <form method="POST" action="{{ route('admin.logout') }}" class="ms-auto">
    @csrf
    <button class="btn btn-outline-danger btn-sm">Sair</button>
    </form>
    @endauth

  </div>
</nav>

<main class="container mb-5">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
</body>
</html>
