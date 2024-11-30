@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @foreach ($events as $event)
        <div class="col-12 col-md-4">
            <div class="card mb-4">
                <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}">
                <div class="card-body">
                    <h5 class="caerd-title">{{ $event->title }}</h5>
                    {{-- <p class="card-text">{!! $event->description !!}</p> --}}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
