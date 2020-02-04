@extends('credentialManager.credentialForm')

@section('cardHeader', __('Edit Credential'))

@section('sendTo', route('editCredential', $credentialId))