<table>
    <thead>
    <tr>
        <th>Article</th>
        <th>Created at</th>
    </tr>
    </thead>
@foreach($items as $item)
    <tr>
        <td><a href="{{$item->item->url }}">{{ $item->item->title }}</td>
        <td>{{ $item->createdAt() }}</td>
    </tr>
@endforeach
</table>