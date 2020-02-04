@extends('roleManager.roleForm')

@section('cardHeader', __('Edit role'))

@section('sendTo', route('editRole', $role->id))