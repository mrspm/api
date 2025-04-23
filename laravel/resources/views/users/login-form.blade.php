@extends('admin.layouts.main')

@php $title = "Вход"; $h1 = ''; @endphp
@section('title'){{ $title }}@endsection
@section('h1'){{ $h1 }}@endsection

@section('content')

 <div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="javascript:void(0);" class="h1"><b>Вход</b></a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('login-user') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Пароль">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember" checked>
                            <label for="remember">Запомнить</label>
                        </div>
                    </div>          
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Вход</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection