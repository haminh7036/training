@php
$menu = [
  [
    "name" => "User",
    "child" => [
      [
        "name" => "User Management",
        "url" => "/admin/user/user",
        "route" => route('admin.user.user.index'),
        "icon" => "fas fa-users"
      ],
    ]
  ],
  [
    "name" => "Order",
    "child" => [
      [
        "name" => "Customer Management",
        "url" => "/admin/order/customer",
        "route" => route('admin.order.customer.index'),
        "icon" => "fas fa-smile"
      ],
    ]
  ],
  [
    "name" => "Product",
    "child" => [
      [
        "name" => "Product Management",
        "url" => "/admin/product/product",
        "route" => route("admin.product.product.index"),
        "icon" => "fab fa-product-hunt"
      ]
    ]
  ]
];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">.
  <title>@yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
    @include('admin.layouts.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link elevation-4 text-center">
        <img src="{{asset('asset/logo.png')}}" alt="Logo" class="brand-image img-thumbnail elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Rivercrane</span>
    </a>

    <!-- Sidebar -->
    @include('admin.layouts.sidebar')
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-top: 2.3em;">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.1.0
    </div>
  </footer>

</div>
<!-- ./wrapper -->

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>
@yield('script')
</body>
</html>
