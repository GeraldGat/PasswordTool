@extends('layouts.base')

@section('pageBody')

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @else
                    @if(Auth::user()->hasPermission('invite_user') || Auth::user()->hasPermission('manage_user_role'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ __('Manage users') }}</a>
                        <div class="dropdown-menu">
                            @if(Auth::user()->hasPermission('invite_user'))
                            <a class="dropdown-item" href="{{ route('inviteUserForm') }}">{{ __('Invite user') }}</a>
                            @endif
                            @if(Auth::user()->hasPermission('manage_user_role'))
                            <a class="dropdown-item" href="{{ route('manageUsersForm') }}">{{ __('Manage user\'s roles') }}</a>
                            @endif
                        </div>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('manage_role_permission'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ __('Manage roles') }}</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('addRoleForm') }}">{{ __('Add role') }}</a>
                            <a class="dropdown-item" href="{{ route('allRole') }}">{{ __('Edit role') }}</a>
                        </div>
                    </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->email }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    @yield('content')
</main>
</div>

@endsection