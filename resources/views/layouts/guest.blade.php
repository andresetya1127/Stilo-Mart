<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - StiloMart</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
    class="bg-gradient-to-br from-yellow-500  to-orange-500 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        @yield('content')
    </div>
</body>

</html>
