@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">重設密碼</div>

                <div class="panel-body">
                    <form id="modify_form" class="form-horizontal" role="form" method="POST" action="{{ url('/password/reseting') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">新密碼</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}">
                            </div>
                        </div>
                        <hr style="border-top: 1px solid #e5e5e5;">
                        <div class="form-group">
                            <label for="password_confirm" class="col-md-4 control-label">再次輸入密碼</label>

                            <div class="col-md-6">
                                <input id="password_confirm" type="password_confirm" class="form-control" name="password_confirm" value="{{ old('password_confirm') }}">
                            </div>
                        </div>
                        {{ Form::hidden('reseType',"own") }}
                        {{ Form::hidden('prev_url',session('prev_url')) }}
                        <hr style="border-top: 1px solid #e5e5e5;">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-refresh"></i> 重設
                                </button>
                                <span style="padding-left:30px;"></span>
                                 <a href="{{ url('/') }}" type="button" class="btn" style="    border-color: #337ab7">
                                    <i class="fa fa-ban"></i> 取消
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
