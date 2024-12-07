@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3 class="mb-4">Events</h3>
        @foreach ($events as $event)
        <div class="col-4 col-sm-4 col-md-3 col-xl-2">
            <div class="card mb-4">
                <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}" style="max-height: 400px;">
                <div class="card-body">
                    <h6 class="caerd-title">{{ $event->title }}</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection