@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Oops! Something went wrong on our servers. This usually happens when the system strictly blocks a dangerous action (like trying to delete a customer who has existing ledgers). Please go back and try again.'))
