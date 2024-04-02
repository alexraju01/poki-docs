<div class="d-poke-types">
    @foreach ($evolution['types'] as $type)
        <button class="d-button-{{  $type }}">{{ ucfirst( $type) }}</button>
    @endforeach
</div>
