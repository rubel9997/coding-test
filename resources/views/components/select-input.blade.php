@props(['options' => []])

<select {{ $attributes->merge(['class' => 'block mt-1 w-full']) }}>
    @foreach ($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
