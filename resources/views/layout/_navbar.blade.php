<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2 mt-3 pb-3 mb-3 d-flex" alt="User Image">
            <a href="#" class="d-block info">{{ auth()->user()->name }}</a>
        </li>
    </ul>
</nav>
