<div class="{{ $title }}">
    <h2>{{ ucFirst($title) }}</h2>
    @foreach ($processedData as $typeName => $damageTypes)
        <div class="d-poke-types">
            @foreach ($damageTypes as $damageType)
                <button class="d-button-{{ strtolower($damageType) }}">{{ $damageType }}</button>
            @endforeach
        </div>
    @endforeach
</div>
