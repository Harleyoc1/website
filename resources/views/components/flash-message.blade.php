@props([
    'key',
    'class',
    'message'
])

<?php
    $key = explode(' ', $key);
    $class = explode(' ', $class);
?>

<div class="absolute left-[50%] transform-[translate(-50%,0)] bottom-5">
    @for($i = 0; $i < count($key); $i++)
        @if (session()->has($key[$i]))
            <div class="flash-message {{ $class[$i] }}">
                {{ $message[$i] ?? session()->get($key[$i]) }}
            </div>
        @endif
    @endfor
</div>
