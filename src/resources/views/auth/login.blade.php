@extends('layouts.app-mini')

@section('content')
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="/">DnD<b>T</b></a>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control" placeholder="username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="remember_me" class="form-check-input">
                            <label for="remember_me" class="form-check-label">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </form>

                    <div class="social-auth-links text-center mb-3">
                        <p>- OR -</p>
                        <a href="{{ route('oauth.redirect', 'discord') }}" class="btn btn-block text-light" style="background-color: #7289da">
                            <i class="fab fa-discord mr-2"></i> Discord
                        </a>
                    </div>
                    <!-- /.social-auth-links -->

                    <p class="mb-1 text-center">
                        <a href="forgot-password.html">forgot my password</a>
                    </p>
                    <p class="mb-0 text-center">
                        <a href="{{ route('register') }}" class="text-center">Register</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
@endsection
