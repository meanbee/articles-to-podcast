<ul>    
@foreach($items as $item)
    <li>
        <a href="{{$item->item->url }}">{{ $item->item->title }}</td>
    </li>
@endforeach
</ul>