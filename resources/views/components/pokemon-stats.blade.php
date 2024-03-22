@props(['title', 'data'])


<div class="{{ $title }}">
    <h2>{{$title}}</h2>
    @foreach ($data as $typeName => $typeData)
        <div class="d-poke-types">
            @php
                $damageDirection = $title === 'strengths' ? 'double_damage_to' : 'double_damage_from';
            @endphp

            @foreach ($typeData[$damageDirection] as $damageType)
                <button class="d-button-{{ $damageType }}">{{ ucfirst($damageType) }}</button>
            @endforeach
        </div>
    @endforeach
</div>
