@extends('layouts.master')

@section('content')
<section class="main-inner">
    <h2>Dashboard</h2>

	<p>
        <a class="button" href="{{ str_replace('http://', 'itpc://', route('podcast', array('id' => $user->id, 'secret' => $user->secret()))) }}">Subscribe</a>
	</p>

    <p>Articles are automatically fetched, convert to podcast episodes and added to your feed every hour.</p>
    <p>Once you've listened to them, you'll need to manually mark them as read in <a href="http://getpocket.com">Pocket</a>.</p>

    @if (count($items) > 0)
        <h3>Articles</h3>
        <p>These articles are currently in your feed.</p>
        @include('partials.items', array('items' => $items))
        <p><a class="button" href="{{ route('pocket.synchronise') }}">Update</a></p>
    @else
        <p><a class="button" href="{{ route('pocket.synchronise') }}">Get pocket articles</a></p>
    @endif
</section>
@endsection
