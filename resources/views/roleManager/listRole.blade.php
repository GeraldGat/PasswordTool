@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('All role') }}</div>

                <div class="card-body">
                    <div class="row justify-content-between">
                        @forelse($roles as $role)
                        @if($role->name != "Admin")
                        <div class="col-md-6 mb-2">
                            <a class="btn btn-light form-control" href="{{ route('editRoleForm', $role->id) }}">{{ $role->name }}</a>
                        </div>
                        @endif
                        @empty
                        <div class="col-12">
                            <p>{{  __('No role') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection