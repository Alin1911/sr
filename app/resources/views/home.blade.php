@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <h3 class="mb-4">Popular Events</h3>
            @foreach ($popularEvents as $event)
                <div class="col-4 col-sm-4 col-md-3 col-xl-2">
                    <a href="/event/{{ $event->id }}">
                        <div class="card mb-4">
                            <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}"
                                style="max-height: 400px;">
                            <div class="card-body">
                                <h6 class="caerd-title">{{ $event->title }}</h6>
                                <p> Score: {{ $event->popularity_score }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row justify-content-center">
            <h3 class="mb-4">Popular Events By Location</h3>
            @foreach ($popularPerformancesByHall as $performance)
                <div class="col-4 col-sm-4 col-md-3 col-xl-2">
                    <a href="/event/{{ $performance->event->id }}">
                        <div class="card mb-4">
                            <img src="{{ $performance->event->image_url }}" class="card-img-top"
                                alt="{{ $performance->event->name }}" style="max-height: 400px;">
                            <div class="card-body">
                                <h6 class="caerd-title">{{ $performance->event->title }}</h6>
                                <p>{{ $performance->hall->name }}</p>
                                <p>{{ $performance->starting_date->format('Y-m-d') }}</p>
                                <p> Score: {{ $performance->hall->popularity_score }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row justify-content-center">
            <h3 class="mb-4">Popular Events By Promoter</h3>
            @foreach ($popularPerformancesByPromoter as $performance)
                <div class="col-4 col-sm-4 col-md-3 col-xl-2">
                    <a href="/event/{{ $performance->event->id }}">
                        <div class="card mb-4">
                            <img src="{{ $performance->event->image_url }}" class="card-img-top"
                                alt="{{ $performance->event->name }}" style="max-height: 400px;">
                            <div class="card-body">
                                <h6 class="caerd-title">{{ $performance->event->title }}</h6>
                                <p>Promoter: {{ $performance->promoter->name }}</p>
                                <p>{{ $performance->starting_date->format('Y-m-d') }}</p>
                                <p> Score: {{ $performance->promoter->popularity_score }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
