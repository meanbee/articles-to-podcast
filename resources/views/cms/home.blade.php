@extends('layouts.master')

@section('title', 'Home')
@section('description', 'Converter to create mp3 podcasts to subcribe in itunes for your articles.')

@section('content')
<section class="main-inner">
    <div class="pocket-login-promo">
        <h1>Pocket Podcast</h1>
        <p>Subscribe to a podcast of your pocket articles and listen on the go!</p>
        @if(!$auth->check() )
            <a href="{{ url('/pocket/login') }}" class="pocket-login">Login with<span class="pocket-logo">Pocket</span></a>
        @endif
        <div class="audio-sample">
            <p>Using the latest in text to speech technology from <a href="http://www.ibm.com/smarterplanet/us/en/ibmwatson/">IBM Watson</a>.</p>
            <p>Sample
                <audio controls class="audio-sample-controls">
                    <source src="https://s3-eu-west-1.amazonaws.com/articles-to-podcast/28e90f17e70903a2e39c63d37461dd95.mp3" type="audio/mp3"
                </audio>
            </p>
        </div>
    </div>
    <div class="iphone-screenshot">
        <img class="podcast-app-screenshot" src="{{ @asset('assets/images/articlecast-screenshot.png') }}" srcset="{{ @asset('assets/images/articlecast-screenshot.png') }} 1x, {{ @asset('assets/images/articlecast-screenshot_2x.png') }} 2x" alt="Screenshot of the Articlecast Pocket feed in Apple Podcast App" width="223">
    </div>

</section>
@stop
@section('before_body_end')
@stop
