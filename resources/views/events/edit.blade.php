@extends('layouts.main')
@section('title', "Editar " . $event->title)
@section('content')
<div class="col-md-6 offset-md-3" id="events-create-container">
  <h1 class="mt-5">Editando: {{$event->title}}</h1>
  <form action="/events/update/{{$event->id}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <div class="form-group pb-3">
      <label for="image" class="mb-2">Imagem do Evento:</label>
      <input type="file" name="image" id="image" class="form-control" required>
      <img src="/img/events/{{$event->image}}" alt="{{$event->title}}" class="img-preview">
    </div>
    <div class="form-group pb-3">
      <label for="title" class="mb-2">Evento:</label>
      <input type="text" name="title" id="title" class="form-control" placeholder="Nome do evento" value="{{$event->title}}" required maxlength="60">
    </div>
    <div class="form-group pb-3">
      <label for="date" class="mb-2">Data do evento:</label>
      <input type="date" name="date" id="date" class="form-control" placeholder="Nome do evento" value="{{date('Y-m-d', strtotime($event->date))}}" required maxlength="60">
    </div>
    <div class="form-group pb-3">
      <label for="city" class="mb-2">Cidade:</label>
      <input type="text" name="city" id="city" class="form-control" placeholder="Local do evento" value="{{$event->city}}" required>
    </div>
    <div class="form-group pb-3">
      <label for="private" class="mb-2">O evento é privado?</label>
      <select name="private" id="private" class="form-control" required>
        <option value="0">Não</option>
        <option value="1" {{$event->private == 1 ? "selected='selected'" : ""}}>Sim</option>
      </select>
    </div>
    <div class="form-group pb-3">
      <label for="description" class="mb-2">Descrição:</label>
      <textarea name="description" id="description" class="form-control" placeholder="O que vai acontecer no evento?" required>{{$event->description}}</textarea>
    </div>
    <div class="form-group pb-3">
      <label for="items" class="mb-2">Adicione items de infraestrutura:</label>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="items[]" id="items" value="Cadeiras"> Cadeiras
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="items[]" id="items" value="Palco"> Palco
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="items[]" id="items" value="Cerverja grátis"> Cerverja grátis
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="items[]" id="items" value="Open food"> Open food
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="items[]" id="items" value="Brindes"> Brindes
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Editar Evento</button>
  </form>
</div>
@endsection
