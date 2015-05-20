@extends('layouts.master')

@section('content')
<form action="{{ URL::route('items.store') }}" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <label for="url">Article URL</label>
    <input type="url" name="url" />
    {!! $errors->first('url', '<div class="alert alert-danger"><b>:message</b></div>') !!}
    <input type="submit" class="btn btn-primary" value="Save Article" />
</form>
@endsection
