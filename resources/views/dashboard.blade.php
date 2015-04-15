@extends('layouts.master')

@section('content')
<section class="main-inner">
    <h2>Dashboard</h2>

	<p>
		To subscribe to your podcast, <a href="{{ str_replace('http://', 'itpc://', route('podcast', array('id' => $user->id, 'secret' => $user->secret()))) }}">click here</a>.
	</p>
</section>
@endsection
