<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Primary SEO Meta Tags --}}
        <meta name="title" content="Pani Padika — Full-Stack Developer">
        <meta name="description" content="Portofolio resmi Pani Padika, Full-Stack Web Developer. Menampilkan interaksi 3D ID Card, showcase projek Laravel + React, sertifikat, dan formulir kontak langsung.">
        <meta name="keywords" content="Pani Padika, Web Developer, Full-Stack Developer, Software Engineer, Laravel, React, Inertia.js, Three.js, Portfolio, Indonesia">
        <meta name="author" content="Pani Padika">
        <meta name="theme-color" content="#0B0F17">

        {{-- Open Graph / Facebook / WhatsApp / LinkedIn --}}
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="Pani Padika Portfolio">
        <meta property="og:title" content="Pani Padika — Full-Stack Developer">
        <meta property="og:description" content="Portofolio resmi Pani Padika, Full-Stack Web Developer. Menampilkan interaksi 3D ID Card, showcase projek Laravel + React, sertifikat, dan formulir kontak langsung.">
        <meta property="og:image" content="{{ asset('card-front.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('card-front.png') }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="Pani Padika — Full-Stack Developer 3D ID Card">

        {{-- Twitter / X --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="Pani Padika — Full-Stack Developer">
        <meta name="twitter:description" content="Portofolio resmi Pani Padika, Full-Stack Web Developer. Menampilkan interaksi 3D ID Card, showcase projek Laravel + React, sertifikat, dan formulir kontak langsung.">
        <meta name="twitter:image" content="{{ asset('card-front.png') }}">

        {{-- Always enforce dark mode for Cosmic Space theme --}}
        <script>
            document.documentElement.classList.add('dark');
        </script>

        {{-- Inline style to set default dark space & eliminate text edit cursor (|) across the entire application --}}
        <style>
            html, html.dark {
                background-color: #0B0F17;
                color-scheme: dark;
            }
            *, *::before, *::after {
                cursor: default;
                user-select: none;
                -webkit-user-select: none;
            }
            a, button, [role="button"], input[type="button"], input[type="submit"], input[type="reset"], input[type="checkbox"], input[type="radio"], select, option, summary, label[for], .cursor-pointer {
                cursor: pointer;
            }
            input[type="text"], input[type="email"], input[type="password"], input[type="search"], input[type="number"], input[type="tel"], input[type="url"], input[type="date"], input[type="datetime-local"], input:not([type]), textarea, [contenteditable="true"], [contenteditable=""], .cursor-text {
                cursor: text;
                user-select: text;
                -webkit-user-select: text;
            }
            .selectable-text, .select-text {
                user-select: text;
                -webkit-user-select: text;
            }
            button:disabled, input:disabled, textarea:disabled, select:disabled, [aria-disabled="true"], .cursor-not-allowed {
                cursor: not-allowed;
            }
        </style>

        <link rel="icon" href="/favicon.ico?v=2" sizes="any">
        <link rel="icon" href="/favicon.svg?v=2" type="image/svg+xml">
        <link rel="icon" href="/favicon.png?v=2" type="image/png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        <x-inertia::head>
            <title>Pani Padika — Full-Stack Developer</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased bg-[#0B0F17] text-slate-50 selection:bg-indigo-500/30 selection:text-indigo-200 overflow-x-clip min-h-screen">
        <x-inertia::app />
    </body>
</html>
