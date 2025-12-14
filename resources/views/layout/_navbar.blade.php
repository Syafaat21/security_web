<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-flex align-items-center">
            <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" style="width: 40px; height: 40px;" alt="User Image">
            <a href="#" class="d-block info ml-2" style="font-size: 14px;">{{ auth()->user()->name }}</a>
        </li>
    </ul>
</nav>
