@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Manage Users') }}</div>

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-4 col-md-5 col-6">
                            <div class="input-group mb-3">
                                <input id="searchBar" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-button" data-tableid="userTable">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="addon-wrapping"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('manageUsers') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <table id="userTable" class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="orderAvailable" data-tableid="userTable" data-column="1" scope="col">{{ __("Email") }}</th>
                                            <th scope="col">{{ __("Role") }}</th>
                                            <th class="text-center" scope="col">{{ __("Disabled") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                        <tr>
                                            <input type="hidden" name="users[{{ $user->id }}][id]" value="{{ $user->id }}">
                                            <input type="hidden" id="changed-{{ $user->id }}" name="users[{{ $user->id }}][change]" value="0">
                                            <td class="toSearchInto">
                                                {{ $user->email }}
                                            </td>
                                            <td>
                                                <select name="users[{{ $user->id }}][role_id]" class="custom-select StateOnChange" data-onchangeid="changed-{{ $user->id }}">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $user->role->id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input class="StateOnChange" name="users[{{ $user->id }}][trashed]" data-onchangeid="changed-{{ $user->id }}" type="checkbox" {{ $user->trashed() ? 'checked' : '' }} >
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3">{{ __("No users.") }}</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm') }}
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