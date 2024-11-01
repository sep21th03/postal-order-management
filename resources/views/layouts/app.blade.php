<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - @yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/basic.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ url('assets/img/favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ url('assets/img/favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ url('assets/img/favicons/favicon-16x16.png') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/img/favicons/favicon.ico') }}">
  <link rel="manifest" href="{{ url('assets/img/favicons/manifest.json') }}">
  <meta name="msapplication-TileImage" content="{{ url('assets/img/favicons/mstile-150x150.png') }}">
  <meta name="theme-color" content="#ffffff">
  <script src="{{ url('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ url('assets/js/config.js') }}"></script>
  <link href="{{ url('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
  <link href="{{ url('assets/css/theme-rtl.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
  <link href="{{ url('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
  <link href="{{ url('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
  <link href="{{ url('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
  <script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
      var linkDefault = document.getElementById('style-default');
      var userLinkDefault = document.getElementById('user-style-default');
      linkDefault.setAttribute('disabled', true);
      userLinkDefault.setAttribute('disabled', true);
      document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
      var linkRTL = document.getElementById('style-rtl');
      var userLinkRTL = document.getElementById('user-style-rtl');
      linkRTL.setAttribute('disabled', true);
      userLinkRTL.setAttribute('disabled', true);
    }
  </script>
  <link href="{{ url('vendors/leaflet/leaflet.css') }}" rel="stylesheet">
  <link href="{{ url('vendors/leaflet.markercluster/MarkerCluster.css') }}" rel="stylesheet">
  <link href="{{ url('vendors/leaflet.markercluster/MarkerCluster.Default.css') }}" rel="stylesheet">
</head>
<body>
@include('layouts.partials.head')
@yield('content')
<script src="{{ url('vendors/popper/popper.min.js') }}"></script>
<script src="{{ url('vendors/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ url('vendors/anchorjs/anchor.min.js') }}"></script>
<script src="{{ url('vendors/is/is.min.js') }}"></script>
<script src="{{ url('vendors/fontawesome/all.min.js') }}"></script>
<script src="{{ url('vendors/lodash/lodash.min.js') }}"></script>
<script src="{{ url('vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ url('vendors/dayjs/dayjs.min.js') }}"></script>
<script src="{{ url('assets/js/phoenix.js') }}"></script>
<script src="{{ url('vendors/leaflet/leaflet.js') }}"></script>
<script src="{{ url('vendors/leaflet.markercluster/leaflet.markercluster.js') }}"></script>
<script src="{{ url('vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js') }}"></script>
<script src="{{ url('vendors/dropzone/dropzone-min.js') }}"></script>
<script src="{{ url('assets/js/phoenix.js') }}"></script>
<script src="{{ url('vendors/echarts/echarts.min.js') }}"></script>
<script src="{{ url('assets/js/ecommerce-dashboard.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
@yield('script')

@yield('modal')
</body>

</html>