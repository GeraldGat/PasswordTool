@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Share credential') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="addSelector">{{ __('Email address:') }}</label>
                                <div class="input-group">
                                    <select class="custom-select" id="addSelector" aria-label="Example select with button addon" data-defineOption='<option value="%id%">%email%</option>'>
                                        <option value="Undefined" selected>{{ __('Choose...') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->email }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button id="addButton" data-selectorId="addSelector" data-sharedUserTableId="sharedUserTable" class="btn btn-secondary" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('shareCredential', $credentialId) }}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">{{ __("User") }}</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="sharedUserTable" data-count="{{ count($sharedUsers) }}" data-row='<tr id="sharedUser-%key%"><td>%email%<input type="hidden" name="sharedUsersList[]" value="%id%" data-email="%email%" /></td><td class="text-right"><button type="button" class="btn btn-secondary" data-selectorId="addSelector" data-removable="sharedUser-%key%" onclick="removeSharedUser(this)"><i class="fas fa-trash-alt"></i></button></td></tr>'>
                                        @forelse($sharedUsers as $key => $sharedUser)
                                        <tr id="sharedUser-{{ $key }}">
                                            <td>
                                                {{ $sharedUser->email }}
                                                <input type="hidden" name="sharedUsersList[]" value="{{ $sharedUser->id }}" data-email="{{ $sharedUser->email }}" />
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-secondary" data-selectorId="addSelector" data-removable="sharedUser-{{ $key }}" onclick="removeSharedUser(this)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">{{ __("No user.") }}</td>
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