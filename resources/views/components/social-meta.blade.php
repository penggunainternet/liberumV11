@props(['title' => '', 'description' => '', 'image' => '', 'url' => ''])

{{-- Open Graph Meta Tags --}}
@if($title)
    <meta property="og:title" content="{{ $title }}">
    <meta name="twitter:title" content="{{ $title }}">
@endif

@if($description)
    <meta property="og:description" content="{{ $description }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="description" content="{{ $description }}">
@endif

@if($image)
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:image" content="{{ $image }}">
@endif

@if($url)
    <meta property="og:url" content="{{ $url }}">
@else
    <meta property="og:url" content="{{ url()->current() }}">
@endif

<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
