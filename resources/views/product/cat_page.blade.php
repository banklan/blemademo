@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <router-view></router-view>
    @include('layouts.footer')
@endsection
