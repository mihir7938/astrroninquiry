<!DOCTYPE html>
<html lang="en">
<head>
    <title>Astrron Syystems</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{asset('img/favicon.ico')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{asset('adminlte/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/css/ekko-lightbox.css')}}">
	@yield('header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="loader">
        <div class="loader-inner">
            <img src="{{asset('img/loading.gif')}}" alt="" style="width: 100%;">
        </div>
    </div>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto align-items-center">
                @if(Auth::check())
                    <li class="nav-item mr-3">
                        Welcome {{Auth::user()->contact_person}},
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="{{route('logout')}}">
                            Logout
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{route('login')}}" class="brand-link">
              <img src="{{asset('img/small_logo.png')}}" alt="Logo" class="brand-image img-circle" style="opacity: .8">
              <span class="brand-text font-weight-light">Astrron Syystems</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        @if(Auth::check() && (Auth::user()->isUser()))
                            <li class="nav-item">
                                <a href="{{route('users.index')}}" class="nav-link {{(Route::currentRouteName() == 'users.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('users.inquiry.add')}}" class="nav-link {{(Route::currentRouteName() == 'users.inquiry.add') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-edit"></i>
                                    <p>Inquiry Form</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('users.inquiries')}}" class="nav-link {{(Route::currentRouteName() == 'users.inquiries') || (Route::currentRouteName() == 'users.inquiries.edit') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-question-circle"></i>
                                    <p>Inquiries</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('users.assign.inquiries')}}" class="nav-link {{(Route::currentRouteName() == 'users.assign.inquiries') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-question-circle"></i>
                                    <p>Assign Inquiries</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('users.team')}}" class="nav-link {{(Route::currentRouteName() == 'users.team') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>Team</p>
                                </a>
                            </li>
                        @elseif(Auth::check() && Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a href="{{route('admin.index')}}" class="nav-link {{(Route::currentRouteName() == 'admin.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item {{(Route::currentRouteName() == 'admin.inquiries') || (Route::currentRouteName() == 'admin.inquiry.add') || (Route::currentRouteName() == 'admin.inquiries.edit') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{(Route::currentRouteName() == 'admin.inquiries') || (Route::currentRouteName() == 'admin.inquiry.add') || (Route::currentRouteName() == 'admin.inquiries.edit') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-question-circle"></i>
                                    <p>Inquiries<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{route('admin.inquiries')}}" class="nav-link {{(Route::currentRouteName() == 'admin.inquiries') || (Route::currentRouteName() == 'admin.inquiries.edit') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>All Inquiries</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.inquiry.add')}}" class="nav-link {{(Route::currentRouteName() == 'admin.inquiry.add') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add New Inquiry</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item {{(Route::currentRouteName() == 'admin.cities') || (Route::currentRouteName() == 'admin.cities.add') || (Route::currentRouteName() == 'admin.cities.edit') || (Route::currentRouteName() == 'admin.business') || (Route::currentRouteName() == 'admin.business.add') || (Route::currentRouteName() == 'admin.business.edit') || (Route::currentRouteName() == 'admin.products') || (Route::currentRouteName() == 'admin.products.add') || (Route::currentRouteName() == 'admin.products.edit') || (Route::currentRouteName() == 'admin.status') || (Route::currentRouteName() == 'admin.status.add') || (Route::currentRouteName() == 'admin.status.edit') || (Route::currentRouteName() == 'admin.assign') || (Route::currentRouteName() == 'admin.assign.add') || (Route::currentRouteName() == 'admin.assign.edit') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{(Route::currentRouteName() == 'admin.cities') || (Route::currentRouteName() == 'admin.cities.add') || (Route::currentRouteName() == 'admin.cities.edit') || (Route::currentRouteName() == 'admin.business') || (Route::currentRouteName() == 'admin.business.add') || (Route::currentRouteName() == 'admin.business.edit') || (Route::currentRouteName() == 'admin.products') || (Route::currentRouteName() == 'admin.products.add') || (Route::currentRouteName() == 'admin.products.edit') || (Route::currentRouteName() == 'admin.status') || (Route::currentRouteName() == 'admin.status.add') || (Route::currentRouteName() == 'admin.status.edit') || (Route::currentRouteName() == 'admin.assign') || (Route::currentRouteName() == 'admin.assign.add') || (Route::currentRouteName() == 'admin.assign.edit') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>Masters<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    {{--<li class="nav-item">
                                        <a href="{{route('admin.cities')}}" class="nav-link {{(Route::currentRouteName() == 'admin.cities') || (Route::currentRouteName() == 'admin.cities.add') || (Route::currentRouteName() == 'admin.cities.edit') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Cities</p>
                                        </a>
                                    </li>--}}
                                    <li class="nav-item">
                                        <a href="{{route('admin.business')}}" class="nav-link {{(Route::currentRouteName() == 'admin.business') || (Route::currentRouteName() == 'admin.business.add') || (Route::currentRouteName() == 'admin.business.edit') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Business</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.products')}}" class="nav-link {{(Route::currentRouteName() == 'admin.products') || (Route::currentRouteName() == 'admin.products.add') || (Route::currentRouteName() == 'admin.products.edit') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Products</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.status')}}" class="nav-link {{(Route::currentRouteName() == 'admin.status') || (Route::currentRouteName() == 'admin.status.add') || (Route::currentRouteName() == 'admin.status.edit') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Status</p>
                                        </a>
                                    </li>
                                    {{--<li class="nav-item">
                                        <a href="{{route('admin.assign')}}" class="nav-link {{(Route::currentRouteName() == 'admin.assign') || (Route::currentRouteName() == 'admin.assign.add') || (Route::currentRouteName() == 'admin.assign.edit') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Assign Names</p>
                                        </a>
                                    </li>--}}
                                </ul>
                            </li>
                            <li class="nav-item {{(Route::currentRouteName() == 'admin.users') || (Route::currentRouteName() == 'admin.users.add') || (Route::currentRouteName() == 'admin.users.edit') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{(Route::currentRouteName() == 'admin.users') || (Route::currentRouteName() == 'admin.users.add') || (Route::currentRouteName() == 'admin.users.edit') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>Users<i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{route('admin.users')}}" class="nav-link {{(Route::currentRouteName() == 'admin.users') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>All Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.users.add')}}" class="nav-link {{(Route::currentRouteName() == 'admin.users.add') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add New User</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
        <footer class="main-footer">
            Copyright &copy; 2026 Astrron Syystems. All rights reserved.
        </footer>
    </div>
    <script src="{{asset('adminlte/js/jquery.min.js')}}"></script>
    <script src="{{asset('adminlte/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('adminlte/js/select2.full.min.js')}}"></script>
    <script src="{{asset('adminlte/js/bs-custom-file-input.min.js')}}"></script>
    <script src="{{asset('adminlte/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('adminlte/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminlte/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('adminlte/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminlte/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('adminlte/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('adminlte/js/jszip.min.js')}}"></script>
    <script src="{{asset('adminlte/js/buttons.html5.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"></script>
    <script src="{{asset('js/validation-additional.js')}}"></script>
    <script src="{{asset('adminlte/js/moment.min.js')}}"></script>
    <script src="{{asset('adminlte/js/jquery.inputmask.min.js')}}"></script>
    <script src="{{asset('adminlte/js/daterangepicker.js')}}"></script>
    <script src="{{asset('adminlte/js/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('adminlte/js/adminlte.js')}}"></script>
    <script src="{{asset('adminlte/js/ekko-lightbox.min.js')}}"></script>
     <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    @yield('footer')
</body>
</html>