<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">
            {{ $subtitle }}
        </h6>
    </div>
    <ul class="list-group list-group-flush">
        {{-- Check apakah yang di pass adalah collection --}}
        @if (is_a($items, 'Illuminate\Support\Collection'))
            {{-- Jika iya, maka loop --}}
            @foreach ($items as $item)
                <li class="list-group-item">
                    {{ $item }}
                </li>
            @endforeach
        @else
            {{-- Jika tidak maka print langsung --}}
            {{ $items }}
        @endif
    </ul>
</div>
