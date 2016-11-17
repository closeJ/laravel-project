@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-warning" role="alert">當註冊成功後會寄送帳號密碼到您的email，如有需要也可修改密碼。</div>
            <div class="panel panel-default">
                <div class="panel-heading">創建營運帳號</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/other/valian') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">您的名字</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">帳號</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">電子信箱</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="autoPass" class="col-md-4 control-label">自動創建密碼</label>

                            <div class="col-md-6">
                                {{ Form::checkbox('autoPass','auto',null,['id' => 'autoPass']) }}
                            </div>
                        </div>
                        {{ Form::hidden('role','營運')}}
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button id="register_sure" type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> 確定創建
                                </button>
                            </div>
                        </div>
                    </form>
                    @if(Session::has('alert-success'))
                        <div class="alert alert-success">{!! session('alert-success') !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
