<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/adm/css/all.min.css">
    <link rel="stylesheet" href="/adm/css/adminlte.min.css">
    <link rel="stylesheet" href="/adm/css/jquery.datetimepicker.css">
    <link rel="stylesheet" href="/adm/css/jodit.min.css">
    <link rel="stylesheet" href="/adm/css/site.css">
    <link rel="icon" href="/img/favicon.svg">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper @guest() guest @endguest">
        @auth()
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div class="sidebar">
                <div class="user-panel d-flex">
                    <div class="info">
                        <a href="{{ route('logout') }}" class="d-block">
                            <i class="fas fa-sign-out-alt"></i>
                            Выход
                        </a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- parent
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard v2</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        -->
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}" class="nav-link">
                                <i class="fas fa-layer-group"></i>
                                <p>Заявки</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('articles.index') }}" class="nav-link">
                                <i class="far fa-newspaper"></i>
                                <p>Новости</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        @endauth
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('h1')</h1>
                        </div>
                        <div class="col-sm-6">
                            @include('admin.layouts.messages')
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </section>
        </div>

      <footer class="main-footer">
        <strong>Lionsale</strong>
      </footer>
    </div>
    @auth()
    <div class="modal fade" id="del-item" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Подтверждение удаления</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Вы действительно хотите удалить этот элемент?
                </div>
                <div class="modal-footer">                
                    <button type="button" class="btn btn-danger del-button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
    @endauth    
    <script src="/adm/js/jquery.min.js"></script>
    <script src="/adm/js/bootstrap.bundle.min.js"></script>
    <script src="/adm/js/adminlte.min.js"></script>
    <script src="/adm/js/jquery.datetimepicker.js"></script>
    <script src="/adm/js/jodit.min.js"></script>
    <script src="/adm/js/site.js"></script>
</body>
</html>