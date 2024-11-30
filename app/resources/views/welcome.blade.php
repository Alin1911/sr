@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center display-4">We are strong!</h1>
            <p class="lead text-center">Welcome to our website. We believe in strength and perseverance.</p>
            
            <div class="text-center mt-5">
                <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
