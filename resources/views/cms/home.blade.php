@extends('layouts.master')

@section('title', 'Home')
@section('description', 'Converter to create mp3 podcasts to subcribe in itunes for your articles.')

@section('content')
<section class="main-inner">
    <div class="pocket-login-promo">
        <h2>Blog reading stacking up?</h2>
        <p>Subscribe to a podcast feed of your pocket articles for listening on the go!</p>
        @if(!$auth->check() )
            <a href="{{ url('/pocket/login') }}" class="pocket-login">Login with<span class="pocket-logo">Pocket</span></a>
        @endif
    </div>
</section>
@stop
@section('before_body_end')

@stop