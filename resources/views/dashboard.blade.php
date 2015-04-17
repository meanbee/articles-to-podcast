@extends('layouts.master')

@section('content')
<section class="main-inner">
    <h2>Dashboard</h2>

	<p>
		To subscribe to your podcast, <a href="{{ str_replace('http://', 'itpc://', route('podcast', array('id' => $user->id, 'secret' => $user->secret()))) }}">click here</a>.
	</p>
    <p>
        To add a new article <a href="{{ URL::route('items.create') }}">click here</a>
    </p>
</section>
@endsection
