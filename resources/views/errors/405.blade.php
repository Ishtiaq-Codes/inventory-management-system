@extends('errors::minimal')

@section('title', __('Method Not Allowed'))
@section('code', '405')
@section('message', __('Oops! The action you tried to perform is not allowed on this page. For example, you may have clicked a button without filling out required information (like selecting a Payment Method). Please go back and try again.'))
