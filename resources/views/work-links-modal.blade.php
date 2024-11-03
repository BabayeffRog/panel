<div>
    <h3>Çalışma Alanları</h3>
    <ul>
        @foreach($work_links as $link)
            <li><a href="{{ $link['field_url'] }}" target="_blank">{{ $link['field_url'] }}</a></li>
        @endforeach
    </ul>
</div>
