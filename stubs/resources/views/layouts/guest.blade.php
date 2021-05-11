@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    @yield('title')
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
@endsection
