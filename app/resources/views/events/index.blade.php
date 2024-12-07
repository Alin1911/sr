@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3 class="mb-4">Popular Events</h3>
        @foreach ($popularEvents as $event)
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card mb-4">
                <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}" style="max-height: 400px;">
                <div class="card-body">
                    <h6 class="caerd-title">{{ $event->title }}</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row justify-content-center">
        <h3 class="mb-4">Popular Events By Location</h3>
        @foreach ($popularPerformancesByHall as $performance)
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card mb-4">
                <img src="{{ $performance->event->image_url }}" class="card-img-top" alt="{{ $performance->event->name }}" style="max-height: 400px;">
                <div class="card-body">
                    <h6 class="caerd-title">{{ $performance->event->title }}</h6>
                    <p>{{ $performance->hall->name }}</p>
                    <p>{{ $performance->starting_date->format("Y-m-d") }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
