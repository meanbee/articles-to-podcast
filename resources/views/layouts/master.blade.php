<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ asset('assets/images/favicon/128.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('assets/images/favicon/128.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ asset('assets/images/favicon/128.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('assets/images/favicon/128.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('assets/images/favicon/128.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/images/favicon/128.png') }}">
    <link rel="icon" href="{{ asset('assets/images/favicon/32.png') }}" sizes="32x32">
    <!--[if IE]><link rel="shortcut icon" href="{{ asset('assets/images/favicon/48.png') }}"><![endif]-->

    <meta name="msapplication-TileColor" content="#2f4aa0">

    <meta name="description" content="@yield('description')" />
    <title>@yield('title') - Articles to Podcast Converter</title>

    {{--<link rel="stylesheet" href="{{ asset('assets/css/styles.css'); }}" media="all">--}}

    <link rel="canonical" href="<?php echo URL::current() ?>" />

    @yield('head')

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-XXXXXXXXX-X', 'auto');
        ga('send', 'pageview');
    </script>
</head>
<body>
    <header role="banner">
        <div class="header-inner">

            <ul class="account-links">
                <li><a href="">Log in</a></li>
                <li><a href="">Sign up</a></li>
            </ul>

            <a class="logo" href="{{ URL::to('') }}"><h1>Article to Podcast Converter</h1></a>

            <nav role="navigation" id="navigation">
                <ul role="menubar" class="navigation-list">
                    <li><a href="{{ route('cms.home') }}" role="menuitem">Home</a></li>
                    <li><a href="{{ route('cms.about') }}" role="menuitem">About</a></li>
                    <li><a href="http://github.com/meanbee/articles-to-podcast" role="menuitem">Github</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main role="main">
        @if(Session::has('success'))
            <div class="alert-box success">
                <p>Success: {{ Session::get('success') }}</p>
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert-box error">
                <p>Error: {{ Session::get('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>
    <footer role="contentinfo">
        <div class="footer-inner">
            <p>Created by <a href="http://tgerulaitis.com/">Tomas Gerulaitis</a>, <a href="https://www.ashsmith.io/">Ash Smith</a>, <a href="https://www.nicksays.co.uk/">Nick Jones</a> &amp; <a href="http://tomrobertshaw.net/">Tom Robertshaw</a></p>
            <div class="footer-end">
                <p class="copyright">&copy; <?php echo date('Y') ?> Articles to Podcast Converter</p>
            </div>

        </div>
    </footer>
    <script type="text/javascript" src="{{ asset('assets/js/main.min.js') }}"></script>
    @yield('before_body_end')
</body>
</html>
