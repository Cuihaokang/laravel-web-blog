@extends('layouts.default')
@section('title','登录')

@section('content')
<div class="offset-md-2 col-ma-8">
    <div class="card">
        <div class="card-header">
            <h5>登录</h5>
        </div>
        <div class="card-body">
            @include('shared._errors')

            <form class="" action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="email">邮箱：</label>
                    <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">密码（<a href="{{ route('password.request') }}">忘记密码</a>）：</label>
                    <input type="password" name="password" value="{{ old('password') }}" class="form-control">
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="remember">记住我</label>
                </div>

                <button type="submit" class="btn btn-primary">登录</button>
            </form>

            <hr>

            <p>还没有账号？<a href="{{ route('signup') }}">现在注册！</a></p>
        </div>
    </div>
</div>
@stop
