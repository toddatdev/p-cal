@if ($errors->any())
    <div {{ $attributes }}>
{{--        <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>--}}

        <ul class="list-unstyled mb-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
