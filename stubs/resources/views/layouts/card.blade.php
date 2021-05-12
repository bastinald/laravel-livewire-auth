@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card">
                <h5 class="card-header">
                    @yield('title')
                </h5>
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endsection
