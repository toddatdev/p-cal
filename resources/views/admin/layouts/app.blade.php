<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <title>Dev Cal | @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/app.css')}}" />
    @stack('stylesheets')
</head>
<body>

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-white border-right" id=" sidebar-wrapper">
        @include('admin.layouts.partials.sidebar')
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        {{--  Top NavBar--}}
        @include('admin.layouts.partials.header')
        {{-- End Top NavBar--}}



        <div class="container-fluid p-4
{{--        vh-100--}}

         overflow-scroll scroll-bar-line" style="min-height: 90vh;">
            @yield('content')
        </div>

    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");

    toggleButton.onclick = function () {
        el.classList.toggle("toggled");
    };
</script>

@stack('scripts')

</body>
</html>
