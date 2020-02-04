@extends('layouts.base')

@section('bodyClasses', 'authBody')

@section('pageBody')

<div class="row justify-content-center">
    <div class="col-md-4 mt-5">
        <div class="card text-white bg-dark-transparent">
            <div class="card-header">
                @yield('cardHeader')
            </div>

            <div class="card-body">
                @yield('cardBody')
            </div>
        </div>
    </div>
</div>

@endsection