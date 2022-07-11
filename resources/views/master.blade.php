<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Celtic Heroes Bot</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{{ asset('js/panelsnap.js') }}" defer></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.panelsnap = new PanelSnap();
            });
        </script>
    </head>
    <body>
        @yield('content')
    </body>
</html>