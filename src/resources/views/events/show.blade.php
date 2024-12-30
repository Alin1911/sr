@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-3">
                <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}" style="max-height: 500px;">
            </div>
            <div class="col-9 px-5">
                <h2>{{ $event->title }}</h2>
                <p>{!! $event->description !!}</p>
            </div>
        </div>
        @foreach ($event->performances as $p)
            @if ($p->isActive())
                <div class="row shadow rounded  my-3 py-3">
                    <div class="col-3 text-center">
                        {{ $p->hall->name }}
                    </div>
                    <div class="col-3 text-center">
                        {{ $p->starting_date->format('Y-m-d H:i') }}
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
