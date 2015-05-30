@extends('layouts.master')

@section('content')
<section class="main-inner">
    <h2>Dashboard</h2>

	<p>
        <button class="button" onclick='window.location.href="{{ str_replace('http://', 'itpc://', route('podcast', array('id' => $user->id, 'secret' => $user->secret()))) }}"'>Subscribe</button>
	</p>

    <p>Articles are automatically fetched, convert to podcast episodes and added to your feed every hour.</p>


    @if (count($items) > 0)
        @include('partials.items', array('items' => $items))
        <p><button class="button" onclick='window.location.href= "{{ route('pocket.synchronise') }}"'>Update</button></p>
    @else
        <p><button class="button" onclick='window.location.href= "{{ route('pocket.synchronise') }}"'>Get pocket articles</button></p>
    @endif
</section>
@endsection
