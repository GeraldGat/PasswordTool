@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-4 col-md-5 col-6">
                            <div class="input-group mb-3">
                                <input id="searchBar" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-button" data-tableid="passwordTable">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="addon-wrapping"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->hasPermission('add_credential'))
                        <div class="col-lg-2 col-md-3 col-4">
                            <a href="{{ route('addCredentialForm') }}" class="btn btn-primary btn-block">Add</a>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="passwordTable" class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="orderAvailable" data-tableid="passwordTable" data-column="1" scope="col">{{ __("Name") }}</th>
                                        <th class="orderAvailable" data-tableid="passwordTable" data-column="2" scope="col">{{ __("Username") }}</th>
                                        <th scope="col">{{ __("Password") }}</th>
                                        @if(Auth::user()->hasPermission('share_credential') || Auth::user()->hasPermission('edit_credential') || Auth::user()->hasPermission('remove_credential'))
                                        <th scope="col"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($credentials as $credential)
                                    <tr>
                                        <td class="toSearchInto">{{ $credential->credential_group->for }}</td>
                                        <td class="toSearchInto">{{ $credential->credential_group->username }}</td>
                                        <td>
                                            <div class="input-group passwordInputGroup">
                                                <input id="pass{{ $credential->id }}" class="form-control" type="password" aria-describedby="btn-pass{{ $credential->id }}" readonly value="{{ $credential->password }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary showHide" type="button" id="btn-pass{{ $credential->id }}" data-inputPasswordId="pass{{ $credential->id }}"><i class="far fa-eye"></i></button>
                                                    <button class="btn btn-secondary directCopy" type="button" data-inputPasswordId="pass{{ $credential->id }}"><i class="far fa-copy"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                        @if(Auth::user()->hasPermission('share_credential') || Auth::user()->hasPermission('edit_credential') || Auth::user()->hasPermission('remove_credential'))
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="btn-action{{ $credential->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __("Actions") }}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btn-action{{ $credential->id }}">
                                                    @if(Auth::user()->hasPermission('edit_credential'))
                                                    <a class="dropdown-item" href="{{ route('editCredentialForm', $credential->id) }}">
                                                        {{ __("Edit") }}
                                                    </a>
                                                    @endif
                                                    @if(Auth::user()->hasPermission('share_credential'))
                                                    <a class="dropdown-item" href="{{ route('shareCredentialForm', $credential->id) }}">
                                                        {{ __("Share") }}
                                                    </a>
                                                    @endif
                                                    @if(Auth::user()->hasPermission('remove_credential'))
                                                    <a class="dropdown-item removeCredential" href="#" data-link="{{ route('removeCredential', $credential->id) }}">
                                                        {{ __("Remove") }}
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">{{ __("No password.") }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{ __('Are you sure you want to remove this credential for all the users?') }}
            </div>
            <div class="modal-footer">
                <a id="removeLink" href="#" class="btn btn-danger">{{ __('Remove') }}</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection