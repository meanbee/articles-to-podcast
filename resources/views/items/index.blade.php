@extends('layouts.master')

@section('content')
<table>
    <thead>
    <tr>
        <th>Item URL</th>
        <th>Created at</th>
    </tr>
    </thead>
@foreach($items as $item)
    <tr>
        <td>{{ $item->item()->url }}</td>
        <td>{{ $item->createdAt() }}</td>
    </tr>
@endforeach
</table>
@endsection
