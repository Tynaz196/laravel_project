
<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
</head>
<body>
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>