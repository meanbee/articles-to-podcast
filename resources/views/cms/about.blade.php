@extends('layouts.master')

@section('title', 'About')
@section('description', 'Find out more about why we created Articles to Podcast with Pocket.')

@section('content')
    <section class="main-inner">
        <p>Created on a <a href="http://www.meetup.com/Meanbee-Hack-Nights/">Meanbee hack night</a>.</p>

        <p>Problem: Lots of saved articles to read but I have more time to listen to audio, e.g. commuting, than I do to sit down and read. When I have time to read I'm normally distracted with other things.</p>

        <p>Solution: Use saved URLs in pocket account, visit URL and scrape body content, convert to audio, save as mp3, host and create podcast feed to be able to subscribe to in iTunes (or similar).</p>
    </section>
@stop
@section('before_body_end')

@stop
