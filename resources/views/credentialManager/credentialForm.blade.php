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

                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $credentialGroup->for ?? '' }}" required autocomplete="name">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">{{ __('Username') }}</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') ?? $credentialGroup->username ?? '' }}" required autocomplete="username">

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <div class="input-group passwordInputGroup">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required aria-describedby="btn-showPassword" autocomplete="off">
                                <div class="input-group-append">
                                    <button id="genRandomPass" class="btn btn-secondary" type="button" tabIndex="-1"><i class="fas fa-random"></i></button>
                                    <button class="btn btn-secondary showHide" type="button" id="btn-showPassword" data-inputPasswordId="password" tabIndex="-1"><i class="far fa-eye"></i></button>
                                </div>
                            </div>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <div class="input-group passwordInputGroup">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required aria-describedby="btn-showPasswordConfirmation" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary showHide" type="button" id="btn-showPasswordConfirmation" data-inputPasswordId="password-confirm"><i class="far fa-eye"></i></button>
                                </div>
                            </div>
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