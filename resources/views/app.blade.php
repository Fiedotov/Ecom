<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('google-tag-manager')
        @include('google-analytics')

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
      <link
          rel="icon"
          href="/img/cropped-DiscountLotsFavicon-32x32.png"
          sizes="32x32"
      >
      <link
          rel="icon"
          href="/img/cropped-DiscountLotsFavicon-192x192.png"
          sizes="192x192"
      >

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Frank+Ruhl+Libre:wght@500;800&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


        <!-- Scripts -->
        @routes

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @inertiaHead
    </head>
    <body class="font-sans antialiased text-gray-700">
        @inertia
    </body>
</html>
