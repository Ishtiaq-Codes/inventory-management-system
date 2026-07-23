@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Your session has expired because you left the page open for too long without saving. Please refresh the page and try again.'))
