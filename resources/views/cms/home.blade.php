@extends('layouts.master')

@section('title', 'Home')
@section('description', 'Converter to create mp3 podcasts to subcribe in itunes for your articles.')

@section('content')
<section class="main-inner">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
            <label class="control-label">Add Article Url</label>
            <input type="text" class="form-control" name="url" value="">
            <button type="submit" class="btn btn-primary">Add</button>

        </div>
    </form>

</section>
@stop
@section('before_body_end')

@stop