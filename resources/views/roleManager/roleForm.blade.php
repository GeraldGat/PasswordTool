@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@yield('cardHeader')</div>

                <div class="card-body">
                    <form method="POST" action="@yield('sendTo')">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="name">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $role->name ?? '' }}" required autocomplete="name">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label>{{ __('Permissions') }}</label>
                            @foreach($permissions as $permission)
                            <div class="input-group mb-3">
                                <input type="hidden" name="permissions[{{ $permission->id }}][id]" value="{{ $permission->id }}">
                                <input type="text" class="form-control" value="{{ $permission->name }}" disabled>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <input type="checkbox" name="permissions[{{ $permission->id }}][checked]" {{ isset($role) && $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection