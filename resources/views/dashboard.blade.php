@extends('layouts.master')

@section('content')
<section class="main-inner">
    <h2>Dashboard</h2>

	<p>
        <a href="{{ str_replace('http://', 'itpc://', route('podcast', array('id' => $user->id, 'secret' => $user->secret()))) }}">Subscribe to your podcast</a>.
	</p>

    <p>
        <a href="{{ route('pocket.synchronise') }}">Synchronise with Pocket</a>
    </p>

    @if (count($items) > 0)
        @include('partials.items', array('items' => $items))
    @endif
</section>
@endsection
