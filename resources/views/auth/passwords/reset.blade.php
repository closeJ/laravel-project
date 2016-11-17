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

                        {{ Form::checkbox('autoPass',1) }}<label for="password" class="form-control-label">自動創建密碼</label>
                        <hr style="border-top: 1px solid #e5e5e5;">
                        {{ Form::checkbox('check',1) }}
                        <label for="auto_email" class="form-control-label">信件通知 : </label>
                        {{ Form::text('auto_email',null,['placeholder' => '自動帶入預設email']) }}
                        {{ Form::hidden('id',$id,['id' => 'reset_id']) }}
                        {{ Form::hidden('prev_url',session('prev_url')) }}
                        {{ Form::hidden('reseType',"other") }}
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
