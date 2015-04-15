@extends('layouts.master')

@section('content')
<section class="main-inner">
    <h2>Dashboard</h2>

	<p>
		Your podcast URL is: <tt>{{ route('podcast', array('id' => $user->id, 'secret' => $user->secret())) }}</tt>.
	</p>
</section>
@endsection
