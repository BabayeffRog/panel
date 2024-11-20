<ul>
    @foreach ($getState() as $link)
        @if (is_array($link) && array_key_exists('field_url', $link))
            <li>
                <a href="{{ $link['field_url'] }}" target="_blank" class="text-blue-500 underline hover:text-blue-700">
                    {{ $link['field_url'] }}
                </a>
            </li>
        @else
            <li>
                <a href="{{ $link }}"  target="_blank">{{ $link }}</a>  <!-- Dəyər sadəcə stringdirsə, onu birbaşa göstər -->
            </li>
        @endif
    @endforeach
</ul>
