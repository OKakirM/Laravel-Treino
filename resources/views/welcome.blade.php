@extends('layouts.main')
@section('title', "HDC Events")
@section('content')
<div class="col-md-12" id="search-container">
  <h1>Busque um Evento</h1>
  <form action="/" method="GET">
    <input type="text" id="search" name="search" class="form-control" placeholder="Procurar">
  </form>
</div>
<div id="events-container" class="col-md-12">
  @if ($search)
    <h2>Buscando por: {{$search}}</h2>
  @else
    <h2>Próximos Eventos</h2>
    <p class="subtitle">Veja os eventos dos próximos dias</p>
  @endif
  <div id="cards-container" class="row">
    @foreach ($event as $event)
      <div class="card col-md-3">
        <img src="/img/events/{{$event->image}}" alt="{{ $event->title }}">
        <div class="card-body">
          <p class="card-date">{{date("d/m/Y", strtotime($event->date))}}</p>
          <h5 class="card-title">{{$event->title}}</h5>
          <p class="card-description">{{$event->description}}</p>
          <p class="card-participants">X Participantes</p>
          <a href="/events/{{$event->id}}" id="card-btn" class="btn btn-primary">Saber Mais</a>
        </div>
      </div>
    @endforeach
    @if(is_countable($event) && count($event) == 0 && $search)
      <p class="alert alert-danger m-0 rounded-0 text-center">Nenhum evento chamado <b>{{$search}}</b> foi encontrando!</p>
    @elseif(is_countable($event) && count($event) == 0)
      <p class="alert alert-danger m-0 rounded-0 text-center">Não há eventos disponiveis!</p>
    @endif
  </div>
</div>
@endsection
