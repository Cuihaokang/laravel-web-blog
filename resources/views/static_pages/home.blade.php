@extends('layouts.default')
@section('content')
  @if (Auth::check())
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          @include('shared._status_form')
        </section>
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
      </aside>
    </div>
  @else
  <div class="jumbotron">
    <h1>Hello Laravel</h1>
    <p class="lead">
      你现在看到的是 <a href="https://learnku.com/courses/laravel-essential-training">Laravel 入门教程</a>的示例项目主页。
    </p>
    <p>一切将从这里开始</p>
    <p>
      <a href="{{ route('signup') }}" class="btn btn-lg btn-success" role="button">现在注册</a>
    </p>
  </div>
  @endif
@stop
